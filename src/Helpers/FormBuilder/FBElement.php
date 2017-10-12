<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FBElement{

    public $name;
    public $title;
    public $css_class_group = 'uk-width-1-1';
    public $css_class = 'uk-width-1-1';
    public $default;
    public $tab;
    public $valid;
    public $in_table_admin;
    public $in_table_admin_ajax_editable;
    public $help;
    public $fillable;

    public $connect;
    public $attached;
    public $user_select;

    public $filtered;
    public $sorted;
    /** @var string  Место где используется (в каталоге место вывода) */
    public $template;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
        $this->tab = ['main' => 'Заголовок, описание'];
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

    public function setInTableAdmin()
    {
        $this->in_table_admin = TRUE;
        return $this;
    }

    public function setInTableAdminAjaxEditable()
    {
        $this->in_table_admin_ajax_editable = TRUE;
        return $this;
    }

    public function setValid($valid)
    {
        if($this->valid){
            $this->valid .= $valid;
        }else{
            $this->valid = $valid;
        }
        return $this;
    }

    public function isRequired()
    {
        if($this->valid){
            $this->valid .= '|required';
        }else{
            $this->valid = 'required';
        }
        return $this;
    }

    public function setCssClassGroup($class)
    {
        $this->css_class_group = $this->css_class .' '. $class;
        return $this;
    }

    public function setCssClass($class)
    {
        $this->css_class = $this->css_class .' '. $class;
        return $this;
    }

    public function setTab($tab_name, $tab_title)
    {
        $this->tab = [$tab_name => $tab_title];
        return $this;
    }

    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }

    public function setConnect($model, $relation_name = NULL, $group_by = NULL)
    {
        $this->connect = collect();
        $this->connect->model = $model;
        $this->connect->relation_name = $relation_name;
        $this->connect->group_by = $group_by;
        return $this;
    }

    public function setWhereConnect($key, $value)
    {
        if(!isset($this->connect->model)){
            abort('404', 'У поля '. $this->name .' сначала нужно определить setConnect');
        }
        $this->connect->where_key = $key;
        $this->connect->where_value = $value;
        return $this;
    }

    public function setAttached()
    {
        $this->attached = TRUE;
        return $this;
    }

    public function setFrontPlace($place)
    {
        $this->front_place = $place;
        return $this;
    }

    public function getElement()
    {
        return $this;
    }

    public function setFiltered()
    {
        $this->filtered = TRUE;
        return $this;
    }

    public function setSorted()
    {
        $this->sorted = TRUE;
        return $this;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function setUserSelect()
    {
        $this->user_select = TRUE;
        return $this;
    }

    public function setFillable()
    {
        $this->fillable = TRUE;
        return $this;
    }
}