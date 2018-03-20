<?php

namespace Larrock\Core;

use Illuminate\Http\Request;
use Larrock\ComponentFeed\Models\Feed;
use Larrock\Core\Models\Seo;
use LarrockAdminSeo;
use LarrockFeed;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Illuminate\Database\Eloquent\Model;
use JsValidator;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Models\Link;
use View;

class Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var string */
    public $table;

    /** @var array */
    public $rows;

    public $customMediaConversions;

    /** @var Model */
    public $model;

    /** @var bool */
    public $active = TRUE;
    public $plugins_backend;
    public $plugins_front;
    public $settings;
    public $searchable;

    public $tabs;
    public $tabs_data;

    public $valid;

    /** @return Component */
    public function getConfig()
    {
        return $this;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return string */
    public function getTitle()
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
    public function getTable()
    {
        return $this->table;
    }

    /** @return array */
    public function getRows()
    {
        return $this->rows;
    }

    public function getValid()
    {
        return self::_valid_construct($this);
    }

    /**
     * @param array $rows
     * @return array
     */
    public function addFillableUserRows(array $rows)
    {
        $fillable_rows = $rows;
        if($this->rows && \is_array($this->rows)){
            foreach ($this->rows as $key => $row){
                if($row->fillable){
                    $fillable_rows[] = $key;
                }
            }
        }
        return $fillable_rows;
    }

    /**
     * Получение fillable-полей модели компонента из его конфига
     * @return array
     */
    public function getFillableRows()
    {
        $fillable_rows = [];
        foreach ($this->rows as $key => $row){
            if($row->fillable){
                $fillable_rows[] = $key;
            }
        }
        return $fillable_rows;
    }

    /**
     * Добавление поля указания веса
     * @return $this
     */
    public function addPosition()
    {
        $row = new FormInput('position', 'Вес');
        $this->rows['position'] = $row->setTab('main', 'Дата, вес, активность')->setValid('integer')
            ->setDefaultValue(0)->setInTableAdminAjaxEditable()->setFillable()->setCssClassGroup('uk-width-1-3');
        return $this;
    }

    /**
     * Добавления поля для указания опубликованности
     * @return $this
     */
    public function addActive()
    {
        $row = new FormCheckbox('active', 'Опубликован');
        $this->rows['active'] = $row->setTab('main', 'Дата, вес, активность')
            ->setValid('integer|max:1')->setDefaultValue(1)->setInTableAdminAjaxEditable()->setFillable()
            ->setCssClassGroup('uk-width-1-3');
        return $this;
    }

    /**
     * Алиас для добавления полей веса и активности
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
     * Вывод в шаблон переменной $app с конфигом компонента, переменной $validator для JSValidation
     * @return $this
     */
    public function shareConfig()
    {
        $this->tabs = collect();
        $this->tabs_data = collect();
        View::share('app', $this);
        $this->valid = self::_valid_construct($this);
        View::share('validator', JsValidator::make($this->valid));
        return $this;
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineFrontMiddlewares());
     * @param null $user_middlewares
     * @return array
     */
    public function combineFrontMiddlewares($user_middlewares = NULL)
    {
        $middleware = ['web', 'GetSeo'];
        if($config = config('larrock.middlewares.front')){
            $middleware = array_merge($middleware, $config);
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-menu')){
            $middleware[] = 'AddMenuFront';
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-blocks')){
            $middleware[] = 'AddBlocksTemplate';
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-discount')){
            $middleware[] = 'DiscountsShare';
        }
        if($user_middlewares){
            $middleware = array_merge($middleware, $user_middlewares);
        }
        return array_unique($middleware);
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineAdminMiddlewares());
     * @param null $user_middlewares
     * @return array
     */
    public function combineAdminMiddlewares($user_middlewares = NULL)
    {
        $middleware = ['web', 'level:2', 'LarrockAdminMenu', 'SiteSearchAdmin'];
        if($config = config('larrock.middlewares.admin')){
            $middleware = array_merge($middleware, $config);
        }
        if($user_middlewares){
            $middleware = array_merge($middleware, $user_middlewares);
        }
        return array_unique($middleware);
    }

    /**
     * Подключение плагина SEO
     * @return $this
     */
    public function addPluginSeo()
    {
        $row = new FormInput('seo_title', 'Title материала');
        $this->rows['seo_title'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormInput('seo_description', 'Description материала');
        $this->rows['seo_description'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormTextarea('seo_keywords', 'Keywords материала');
        $this->rows['seo_keywords'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')->setCssClass('not-editor uk-width-1-1');

        $this->plugins_backend['seo']['rows'] = $rows_plugin;

        $row = new FormInput('url', 'URL материала');
        $this->rows['url'] = $row->setTab('seo', 'SEO')
            ->setValid('max:155|required|unique:'. $this->table .',url,:id')->setCssClass('uk-width-1-1')->setFillable();

        return $this;
    }

    /**
     * Подключение плагина загрузки фото
     * @return $this
     */
    public function addPluginImages()
    {
        $this->plugins_backend['images'] = 'images';
        return $this;
    }

    /**
     * Метод для добавления в модель новых пресетов картинок для Medialibrary
     * @param array $conversions
     */
    public function addCustomMediaConversions(array $conversions)
    {
        $this->customMediaConversions = $conversions;
    }

    /**
     * Подключение плагина загрузки файлов
     * @return $this
     */
    public function addPluginFiles()
    {
        $this->plugins_backend['files'] = 'files';
        return $this;
    }

    /**
     * Плагин для генерации анонса новости для блока анонс новости
     * @param int $categoryAnons    ID категории с анонсами
     * @return $this
     */
    public function addAnonsToModule($categoryAnons)
    {
        $row = new FormCheckbox('anons_merge', 'Сгенерировать для анонса заголовок и ссылку на новость');
        $this->rows['anons_merge'] = $rows_plugin[] = $row->setTab('anons', 'Создать анонс');

        $row = new FormTextarea('anons_description', 'Текст для анонса новости в модуле');
        $this->rows['anons_description'] = $rows_plugin[] = $row->setTab('anons', 'Создать анонс')->setTypo();

        $this->settings['anons_category'] = $categoryAnons;
        $this->plugins_backend['anons']['rows'] = $rows_plugin;
        return $this;
    }

    /**
     * Вспомогательный метод построения правил валидации из конфига полей компонента
     * @param array|object $config
     * @param string $action create|update
     * @param null|string|integer   $id
     * @return array
     */
    public static function _valid_construct($config, $action = 'create', $id = NULL)
    {
        $rules = array();
        if(isset($config->rows)){
            foreach($config->rows as $rows_value){
                if( !empty($rows_value->valid)){
                    $rules[$rows_value->name] = $rows_value->valid;
                    if($action === 'update'){
                        $rules[$rows_value->name] = str_replace(':id', $id, $rules[$rows_value->name]);
                    }else{
                        $rules[$rows_value->name] = str_replace(',:id', '', $rules[$rows_value->name]);
                    }
                }
            }
        }
        return $rules;
    }

    /**
     * Вывод данных полей компонента для табов
     * @param Model $data
     * @return $this
     */
    public function tabbable($data)
    {
        if($this->plugins_backend){
            $this->addDataPlugins($data);
        }

        $this->tabs = collect();
        foreach($this->rows as $row_value){
            $this->tabs->put(key($row_value->tab), current($row_value->tab));
        }
        foreach($this->tabs as $tab_key => $tab_value){
            $render = '';
            foreach($this->rows as $row_value){
                $class_name = \get_class($row_value);
                $load_class = new $class_name($row_value->name, $row_value->title);

                if(key($row_value->tab) === $tab_key){
                    if(method_exists($load_class, 'render')){
                        $render .= $load_class->render($row_value, $data);
                    }else{
                        $this->tabs_data->put($tab_key, '<p>'. $class_name .' не определен для отрисовки</p>');
                    }
                }
            }
            $this->tabs_data->put($tab_key, $render);
        }
        return $this;
    }

    /**
     * Присоединяем данные от плагинов
     * @param Model $data
     */
    public function addDataPlugins($data)
    {
        if($this->plugins_backend && \is_array($this->plugins_backend)){
            foreach ($this->plugins_backend as $key_plugin => $value_plugin){
                if($key_plugin === 'seo' && $plugin_data = $data->getSeo){
                    foreach ($this->rows as $key => $value){
                        if($value->name === 'seo_title' || $value->name === 'seo_description' || $value->name === 'seo_keywords'){
                            $this->rows[$key]->default = $plugin_data->{$value->name};
                        }
                    }
                }
            }
        }
    }

    /**
     * Перезапись конфига компонента (например внутри контроллера)
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
     * Разрешить поиск по материалам компонента
     * @return $this
     */
    public function isSearchable()
    {
        $this->searchable = TRUE;
        return $this;
    }

    /**
     * Формирование пунктов меню компонента в админке
     * @return string
     */
    public function renderAdminMenu()
    {
        return '';
    }

    /**
     * Метод встаивания данных компонента в карту сайта sitemap.xml
     * @return null
     */
    public function createSitemap()
    {
        return NULL;
    }

    /**
     * Метод встаивания данных компонента в rss-feed
     * @return null
     */
    public function createRSS()
    {
        return NULL;
    }

    /**
     * Данные для поиска по материалам компонента
     * @param null|bool $admin Если TRUE - для поиска будут доступны вообще все элементы (не только опубликованные)
     * @return null
     */
    public function search($admin)
    {
        return NULL;
    }
}