<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use Larrock\Core\Helpers\Tree;
use LarrockCategory;
use View;

class FormCategory extends FBElement
{
    /** @var mixed */
    public $options;

    /** @var null|integer */
    public $max_items;

    /** @var null|bool */
    public $allow_empty;

    /** @var null|mixed */
    public $connect;

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.tags.categoryTree';

    /**
     * @param null|integer $max
     * @return $this
     */
    public function setMaxItems($max = null)
    {
        $this->max_items = $max;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAllowEmpty()
    {
        $this->allow_empty = TRUE;
        return $this;
    }

    /**
     * Установка связи поля с какой-либо моделью
     * Сейчас применяется в FormSelect, FormCategory
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
     * Отрисовка элемента формы
     * @return string
     */
    public function __toString()
    {
        if( !isset($this->connect->model, $this->connect->relation_name)){
            throw new LarrockFormBuilderRowException('Поля model, relation_name не установлены через setConnect()');
        }

        if( !isset($this->data->{$this->name}) && $this->default){
            $this->data->{$this->name} = $this->default;
        }

        $this->options = collect();
        /** @var \Eloquent $model */
        $model = new $this->connect->model;
        if(isset($this->connect->where_key)){
            $model = $model::where($this->connect->where_key, '=', $this->connect->where_value);
        }
        if($get_options = $model::get(['id', 'parent', 'level', 'title'])){
            foreach($get_options as $get_options_value){
                $this->options->push($get_options_value);
            }
        }

        $selected = $data->{$this->connect->relation_name};
        if(\count($selected) === 1 && isset($selected->id)){
            $once_category[] = $selected;
            $selected = $once_category;
        }

        if($selected === NULL
            && isset($data->{$this->name})
            && ($get_category = LarrockCategory::getModel()->whereId($data->{$this->name})->first())){
            $selected[] = $get_category;
        }

        $tree = new Tree;
        $this->options = $tree->buildTree($this->options, 'parent');

        return View::make($this->FBTemplate, ['row_key' => $this->name,
            'row_settings' => $this, 'data' => $this->data, 'selected' => $selected])->render();
    }
}