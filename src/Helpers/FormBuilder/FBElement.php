<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;
use Illuminate\Database\Eloquent\Model;

class FBElement
{
    /** @var string */
    public $name;

    /** @var string */
    public $title;

    /** @var string */
    public $cssClassGroup = 'uk-width-1-1';

    /** @var string */
    public $cssClass = 'uk-width-1-1';

    /** @var string */
    public $default;

    /** @var array */
    public $tab = ['main' => 'Заголовок, описание'];

    /** @var string */
    public $valid;

    /** @var null|bool */
    public $inTableAdmin;

    /** @var null|bool */
    public $inTableAdminEditable;

    /** @var string */
    public $help;

    /** @var null|bool */
    public $fillable;

    /** @var null|bool */
    public $filtered;

    /** @var null|bool */
    public $sorted;

    /** @var string Место где используется (в каталоге место вывода) */
    public $template;

    /** @var string */
    public $templateAdmin;

    /** @var Model|null */
    public $data;

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.input.hidden';

    /** @var bool|null Показывать поле в мобильном виде в списке материалов админки */
    public $mobileAdminVisible;

    /**
     * FBElement constructor.
     * @param string $name  Название поля для компонента (например: в БД)
     * @param string $title Имя поля для вывода
     */
    public function __construct(string $name, string $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    /**
     * Установка данных модели (при редактировании материалов).
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Установка имени шаблона FormBuilder для отрисовки поля.
     * @param $template
     * @return $this
     */
    public function setFBTemplate($template)
    {
        $this->FBTemplate = $template;

        return $this;
    }

    /**
     * Можно передать в качестве параметра имя поля, тогда оно будет браться из БД.
     * @param $default
     * @return $this
     */
    public function setDefaultValue($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Выводить поле в таблице контента в админке.
     * @return $this
     */
    public function setInTableAdmin()
    {
        $this->inTableAdmin = true;

        return $this;
    }

    /**
     * Выводить поле на редактирование в таблице контента в админке.
     * @return $this
     */
    public function setInTableAdminEditable()
    {
        $this->inTableAdminEditable = true;

        return $this;
    }

    /**
     * Добавление правил валидации.
     * @param string    $valid
     * @return $this
     */
    public function setValid(string $valid)
    {
        if ($this->valid) {
            $this->valid .= '|'.$valid;
        } else {
            $this->valid = $valid;
        }

        return $this;
    }

    /**
     * Алиас к setValid. Устанавливает обязательность для заполнения.
     * @return FBElement
     */
    public function isRequired()
    {
        return $this->setValid('required');
    }

    /**
     * Установка класса для группы поля в редактировании материала.
     * @param string    $class
     * @return $this
     */
    public function setCssClassGroup(string $class)
    {
        $this->cssClassGroup = $this->cssClass.' '.$class;

        return $this;
    }

    /**
     * Установка класса для группы поля в редактировании материала.
     * @param string    $class
     * @return $this
     */
    public function setCssClass(string $class)
    {
        $this->cssClass = $this->cssClass.' '.$class;

        return $this;
    }

    /**
     * Указание имени и заголовка для таба, где показывать поле на странице редактирования материала.
     * @param string    $tab_name
     * @param string    $tab_title
     * @return $this
     */
    public function setTab(string $tab_name, string $tab_title)
    {
        $this->tab = [$tab_name => $tab_title];

        return $this;
    }

    /**
     * Вывод текста описания для поля в интерфейсе.
     * @param string $text
     * @return $this
     */
    public function setHelp(string $text)
    {
        $this->help = $text;

        return $this;
    }

    /**
     * Поле может использоваться в фильтрах.
     * @return $this
     */
    public function setFiltered()
    {
        $this->filtered = true;

        return $this;
    }

    /**
     * Поле может использоваться в сортировках.
     * @return $this
     */
    public function setSorted()
    {
        $this->sorted = true;

        return $this;
    }

    /**
     * Указание шаблона для вывода поля (в каталоге место для вывода).
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function setTemplateAdmin($template)
    {
        $this->templateAdmin = $template;

        return $this;
    }

    /**
     * Добавить поле в fillable модели компонента (добавлять значения поля в БД компонента).
     * @return $this
     */
    public function setFillable()
    {
        $this->fillable = true;

        return $this;
    }

    /**
     * Сделать поле видимым при выводе списков материалов админки при мобильных.
     * @return $this
     */
    public function setMobileAdminVisible()
    {
        $this->mobileAdminVisible = true;

        return $this;
    }

    /**
     * Отрисовка элемента формы.
     * @return string
     */
    public function __toString()
    {
        if (! isset($this->data->{$this->name}) && $this->default) {
            $this->data->{$this->name} = $this->default;
        }

        return View::make($this->FBTemplate, ['row_key' => $this->name,
            'row_settings' => $this, 'data' => $this->data, ])->render();
    }
}
