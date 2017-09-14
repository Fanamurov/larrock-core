<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Larrock\Core\Helpers\Tree;
use Larrock\ComponentCategory\Facades\LarrockCategory;
use View;

class FormCategory extends FBElement {

    public $options;
    public $max_items;
    public $allow_empty;

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setMaxItems($max = null)
    {
        $this->max_items = $max;
        return $this;
    }

    public function setAllowEmpty()
    {
        $this->allow_empty = TRUE;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }

        $row_settings->options = collect();
        $model = new $row_settings->connect->model;
        if(isset($row_settings->connect->where_key)){
            $model = $model->where($row_settings->connect->where_key, '=', $row_settings->connect->where_value);
        }
        if($get_options = $model->get(['id', 'parent', 'level', 'title'])){
            foreach($get_options as $get_options_value){
                $row_settings->options->push($get_options_value);
            }
        }

        $selected = $data->{$row_settings->connect->relation_name};
        if(count($selected) === 1 && isset($selected->id)){
            $once_category[] = $selected;
            $selected = $once_category;
        }

        if($selected === NULL){
            if(isset($data->{$row_settings->name})){
                if($get_category = LarrockCategory::getModel()->whereId($data->{$row_settings->name})->first()){
                    $selected[] = $get_category;
                }
            }
        }

        $tree = new Tree;
        $row_settings->options = $tree->build_tree($row_settings->options, 'parent');

        return View::make('larrock::admin.formbuilder.tags.categoryTree', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data, 'selected' => $selected])->render();
    }
}