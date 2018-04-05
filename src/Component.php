<?php

namespace Larrock\Core;

use View;
use JsValidator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Plugins\PluginSeoTrait;
use Larrock\Core\Plugins\PluginAnonsTrait;
use Larrock\Core\Helpers\FormBuilder\FBElement;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;

class Component
{
    use PluginSeoTrait, PluginAnonsTrait;

    /** @var string */
    public $name;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var string */
    public $table;

    /** @var array */
    public $rows = [];

    /** @var array */
    public $customMediaConversions;

    /** @var Model */
    public $model;

    /** @var bool */
    public $active = true;

    /** @var array */
    public $plugins_backend;

    /** @var array */
    public $plugins_front;

    /** @var array */
    public $settings;

    /** @var null|bool */
    public $searchable;

    /** @var array|Collection */
    public $tabs;

    /** @var array|Collection */
    public $tabs_data;

    /** @var mixed */
    public $valid;

    /** @return Component */
    public function getConfig()
    {
        return $this;
    }

    /** @return string */
    public function getName() :string
    {
        return $this->name;
    }

    /** @return string */
    public function getTitle() :string
    {
        return $this->title;
    }

    /** @return Model */
    public function getModel()
    {
        return new $this->model;
    }

    /** @return string */
    public function getModelName()
    {
        return $this->model;
    }

    /** @return string */
    public function getTable() :string
    {
        return $this->table;
    }

    /**
     * @param FBElement $FBElement
     * @return Component
     */
    public function setRow($FBElement)
    {
        $this->rows[$FBElement->name] = $FBElement;

        return $this;
    }

    /** @return array */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Получение правил валидации.
     *
     * @param null|int $edit
     * @return array
     */
    public function getValid($edit = null)
    {
        $rules = [];
        foreach ($this->rows as $rows_value) {
            if (! empty($rows_value->valid)) {
                $rules[$rows_value->name] = $rows_value->valid;
                if ($edit && $rows_value->name === 'url') {
                    $rules[$rows_value->name] = str_replace(
                        'unique:'.$this->table,
                        'unique:'.$this->table.','.$rows_value->name.','.$edit,
                        $rules[$rows_value->name]
                    );
                }
            }
        }

        return $rules;
    }

    /**
     * @param array $rows
     * @return array
     */
    public function addFillableUserRows(array $rows)
    {
        $fillable_rows = $rows;
        if ($this->rows && \is_array($this->rows)) {
            foreach ($this->rows as $key => $row) {
                if ($row->fillable) {
                    $fillable_rows[] = $key;
                }
            }
        }

        return $fillable_rows;
    }

    /**
     * Получение fillable-полей модели компонента из его конфига.
     * @return array
     */
    public function getFillableRows()
    {
        $fillable_rows = [];
        foreach ($this->rows as $key => $row) {
            if ($row->fillable) {
                $fillable_rows[] = $key;
            }
        }

        return $fillable_rows;
    }

    /**
     * Добавление поля указания веса.
     * @return $this
     */
    public function addPosition()
    {
        $row = new FormInput('position', 'Вес');
        $this->rows['position'] = $row->setTab('main', 'Дата, вес, активность')->setValid('integer|nullable')
            ->setDefaultValue(0)->setInTableAdminEditable()->setFillable()->setCssClassGroup('uk-width-1-3');

        return $this;
    }

    /**
     * Добавления поля для указания опубликованности.
     * @return $this
     */
    public function addActive()
    {
        $row = new FormCheckbox('active', 'Опубликован');
        $this->rows['active'] = $row->setTab('main', 'Дата, вес, активность')
            ->setValid('integer|max:1|nullable')->setDefaultValue(1)->setInTableAdminEditable()->setFillable()
            ->setCssClassGroup('uk-width-1-3');

        return $this;
    }

    /**
     * Алиас для добавления полей веса и активности.
     * @return $this
     */
    public function addPositionAndActive()
    {
        $this->addPosition();
        $this->addActive();

        return $this;
    }

    /**
     * Получение конфигурации компонента
     * Вывод в шаблон переменной $app с конфигом компонента, переменной $validator для JSValidation.
     * @return $this
     */
    public function shareConfig()
    {
        $this->tabs = collect();
        $this->tabs_data = collect();
        View::share('app', $this);
        $this->valid = $this->getValid();
        View::share('validator', JsValidator::make($this->getValid()));

        return $this;
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineFrontMiddlewares());.
     * @param null $user_middlewares
     * @return array
     */
    public function combineFrontMiddlewares($user_middlewares = null)
    {
        $middleware = ['web', 'GetSeo', 'AddMenuFront', 'AddBlocksTemplate', 'ContactCreateTemplate'];
        if ($config = config('larrock.middlewares.front')) {
            $middleware = array_merge($middleware, $config);
        }
        if (file_exists(base_path().'/vendor/fanamurov/larrock-discount')) {
            $middleware[] = 'DiscountsShare';
        }
        if ($user_middlewares) {
            $middleware = array_merge($middleware, $user_middlewares);
        }

        return array_unique($middleware);
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineAdminMiddlewares());.
     * @param null $user_middlewares
     * @return array
     */
    public function combineAdminMiddlewares($user_middlewares = null)
    {
        $middleware = ['web', 'level:2', 'LarrockAdminMenu'];
        if ($config = config('larrock.middlewares.admin')) {
            $middleware = array_merge($middleware, $config);
        }
        if ($user_middlewares) {
            $middleware = array_merge($middleware, $user_middlewares);
        }

        return array_unique($middleware);
    }

    /**
     * Подключение плагина загрузки фото.
     * @return $this
     */
    public function addPluginImages()
    {
        $this->plugins_backend['images'] = 'images';

        return $this;
    }

    /**
     * Метод для добавления в модель новых пресетов картинок для Medialibrary.
     * @param array $conversions
     */
    public function addCustomMediaConversions(array $conversions)
    {
        $this->customMediaConversions = $conversions;
    }

    /**
     * Подключение плагина загрузки файлов.
     * @return $this
     */
    public function addPluginFiles()
    {
        $this->plugins_backend['files'] = 'files';

        return $this;
    }

    /**
     * Вывод данных полей компонента для табов.
     * @param Model $data
     * @return $this
     */
    public function tabbable($data)
    {
        if (isset($this->plugins_backend['seo']) && $plugin_data = $data->getSeo) {
            //Присоединяем данные от плагинов
            $this->rows['seo_title']->default = $plugin_data->seo_title;
            $this->rows['seo_description']->default = $plugin_data->seo_description;
            $this->rows['seo_keywords']->default = $plugin_data->seo_keywords;
        }

        $this->tabs = collect();
        $this->tabs_data = collect();
        foreach ($this->rows as $row_value) {
            $this->tabs->put(key($row_value->tab), current($row_value->tab));

            if (! isset($this->tabs_data[key($row_value->tab)])) {
                $this->tabs_data->put(key($row_value->tab), $row_value->setData($data));
            } else {
                $this->tabs_data[key($row_value->tab)] .= $row_value->setData($data);
            }
        }

        return $this;
    }

    /**
     * Перезапись конфига компонента (например внутри контроллера).
     * @param $option
     * @param $config
     * @return $this
     */
    public function overrideComponent($option, $config)
    {
        $this->{$option} = $config;

        return $this;
    }

    /**
     * Перезапись конфига поля компонента (например внутри контроллера).
     * @param string $key
     * @param FBElement $row
     * @return $this
     */
    public function overrideRow($key, $row)
    {
        $this->removeRow($key);

        return $this->setRow($row);
    }

    /**
     * Удаление поля из компонента (например внутри контроллера).
     * @param string $key
     * @return $this
     */
    public function removeRow($key)
    {
        if (array_key_exists($key, $this->rows)) {
            unset($this->rows[$key]);
        }

        return $this;
    }

    /**
     * Разрешить поиск по материалам компонента.
     * @return $this
     */
    public function isSearchable()
    {
        $this->searchable = true;

        return $this;
    }

    /**
     * Формирование пунктов меню компонента в админке.
     * @return string
     */
    public function renderAdminMenu()
    {
        return '';
    }

    /**
     * Метод встаивания данных компонента в карту сайта sitemap.xml.
     * @return null
     */
    public function createSitemap()
    {
        return null;
    }

    /**
     * Метод встаивания данных компонента в rss-feed.
     * @return null
     */
    public function createRSS()
    {
        return null;
    }

    /**
     * Данные для поиска по материалам компонента.
     * @param null|bool $admin Если TRUE - для поиска будут доступны вообще все элементы (не только опубликованные)
     * @return null
     */
    public function search($admin = null)
    {
        return null;
    }
}
