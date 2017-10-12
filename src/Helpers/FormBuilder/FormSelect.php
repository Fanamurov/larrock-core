<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormSelect extends FBElement {

    public $options;
    public $option_title;
    public $allowCreate;

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setOptionsTitle($row)
    {
        $this->option_title = $row;
        return $this;
    }

    public function setAllowCreate()
    {
        $this->allowCreate = TRUE;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default && $row_settings->default !== NULL){
            $data->{$row_settings->name} = $row_settings->default;
        }

        if($row_settings->connect){
            if( !$row_settings->options){
                $row_settings->options = collect();
            }else{
                $row_settings->options = collect($row_settings->options);
            }
            $model = new $row_settings->connect->model;
            $get_options_query = $model;
            if(isset($row_settings->connect->where_key) && $row_settings->connect->where_key){
                $get_options_query = $get_options_query->where($row_settings->connect->where_key, '=', $row_settings->connect->where_value);
            }

            if(isset($row_settings->connect->group_by) && $row_settings->connect->group_by){
                $get_options_query = $get_options_query->groupBy([$row_settings->connect->group_by]);
            }

            if($get_options = $get_options_query->get()){
                foreach($get_options as $get_options_value){
                    $row_settings->options->push($get_options_value);
                }
            }
        }else{
            $row_settings->options = collect($row_settings->options);
        }

        $selected = [];
        if(\Request::input($row_settings->name)){
            $selected[] = \Request::input($row_settings->name);
        }

        return View::make('larrock::admin.formbuilder.select.value', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data, 'selected' => $selected])->render();
    }
}