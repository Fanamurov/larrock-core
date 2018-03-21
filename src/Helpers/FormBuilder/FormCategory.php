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
     * @param $row_settings
     * @param $data
     * @return mixed
     * @throws LarrockFormBuilderRowException
     */
    public function render($row_settings, $data)
    {
        if( !isset($row_settings->connect->model, $row_settings->connect->relation_name)){
            throw new LarrockFormBuilderRowException('Поля model, relation_name не установлены через setConnect()');
        }

        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }

        $row_settings->options = collect();
        /** @var \Eloquent $model */
        $model = new $row_settings->connect->model;
        if(isset($row_settings->connect->where_key)){
            $model = $model::where($row_settings->connect->where_key, '=', $row_settings->connect->where_value);
        }
        if($get_options = $model::get(['id', 'parent', 'level', 'title'])){
            foreach($get_options as $get_options_value){
                $row_settings->options->push($get_options_value);
            }
        }

        $selected = $data->{$row_settings->connect->relation_name};
        if(\count($selected) === 1 && isset($selected->id)){
            $once_category[] = $selected;
            $selected = $once_category;
        }

        if($selected === NULL
            && isset($data->{$row_settings->name})
            && ($get_category = LarrockCategory::getModel()->whereId($data->{$row_settings->name})->first())){
            $selected[] = $get_category;
        }

        $tree = new Tree;
        $row_settings->options = $tree->buildTree($row_settings->options, 'parent');

        return View::make('larrock::admin.formbuilder.tags.categoryTree', ['row_key' => $row_settings->name,
            'row_settings' => $row_settings, 'data' => $data, 'selected' => $selected])->render();
    }
}