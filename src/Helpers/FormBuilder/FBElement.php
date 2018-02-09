<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;

class FBElement
{
    public $name;
    public $title;
    public $css_class_group = 'uk-width-1-1';
    public $css_class = 'uk-width-1-1';
    public $default;
    public $tab = ['main' => 'Заголовок, описание'];
    public $valid;
    public $in_table_admin;
    public $in_table_admin_ajax_editable;
    public $help;
    public $fillable;
    public $connect;
    public $attached;
    public $filtered;
    public $sorted;
    /** @var string  Место где используется (в каталоге место вывода) */
    public $template;
    public $template_admin;


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
     * Можно передать в качестве параметра имя поля, тогда оно будет браться из БД
     * @param $default
     * @return $this
     */
    public function setDefaultValue($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Выводить поле в таблице контента в админке
     * @return $this
     */
    public function setInTableAdmin()
    {
        $this->in_table_admin = TRUE;
        return $this;
    }

    /**
     * Выводить поле на редактирование в таблице контента в админке
     * @return $this
     */
    public function setInTableAdminAjaxEditable()
    {
        $this->in_table_admin_ajax_editable = TRUE;
        return $this;
    }

    /**
     * Добавление правил валидации
     * @param string    $valid
     * @return $this
     */
    public function setValid(string $valid)
    {
        if($this->valid){
            $this->valid .= '|'. $valid;
        }else{
            $this->valid = $valid;
        }
        return $this;
    }

    /**
     * Алиас к setValid. Устанавливает обязательность для заполнения
     * @return FBElement
     */
    public function isRequired()
    {
        return $this->setValid('required');
    }

    /**
     * Установка класса для группы поля в редактировании материала
     * @param string    $class
     * @return $this
     */
    public function setCssClassGroup(string $class)
    {
        $this->css_class_group = $this->css_class .' '. $class;
        return $this;
    }

    /**
     * Установка класса для группы поля в редактировании материала
     * @param string    $class
     * @return $this
     */
    public function setCssClass(string $class)
    {
        $this->css_class = $this->css_class .' '. $class;
        return $this;
    }

    /**
     * Указание имени и заголовка для таба, где показывать поле на странице редактирования материала
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
     * Вывод текста описания для поля в интерфейсе
     * @param string    $help
     * @return $this
     */
    public function setHelp(string $text)
    {
        $this->help = $text;
        return $this;
    }

    /**
     * Установка связи поля с какой-либо моделью
     * @param Model $model
     * @param null $relation_name
     * @param null $group_by
     * @return $this
     */
    public function setConnect($model, $relation_name = NULL, $group_by = NULL)
    {
        $this->connect = collect();
        $this->connect->model = $model;
        $this->connect->relation_name = $relation_name;
        $this->connect->group_by = $group_by;
        return $this;
    }

    /**
     * Установка опции выборки значений для setConnect()
     * @param string $key
     * @param string $value
     * @return $this
     * @throws LarrockFormBuilderRowException
     */
    public function setWhereConnect(string $key, string $value)
    {
        if( !isset($this->connect->model)){
            throw new LarrockFormBuilderRowException('У поля '. $this->name .' сначала нужно определить setConnect');
        }
        $this->connect->where_key = $key;
        $this->connect->where_value = $value;
        return $this;
    }

    /**
     * Поле вносит изменение в связанные модели
     * @return $this
     */
    public function setAttached()
    {
        $this->attached = TRUE;
        return $this;
    }

    /**
     * Поле может использоваться в фильтрах
     * @return $this
     */
    public function setFiltered()
    {
        $this->filtered = TRUE;
        return $this;
    }

    /**
     * Поле может использоваться в сортировках
     * @return $this
     */
    public function setSorted()
    {
        $this->sorted = TRUE;
        return $this;
    }

    /**
     * Указание шаблона для вывода поля (в каталоге место для вывода)
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
        $this->template_admin = $template;
        return $this;
    }

    /**
     * Добавить поле в fillable модели компонента (добавлять значения поля в БД компонента)
     * @return $this
     */
    public function setFillable()
    {
        $this->fillable = TRUE;
        return $this;
    }
}