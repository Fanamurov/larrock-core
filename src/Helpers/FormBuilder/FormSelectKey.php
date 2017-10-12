<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

/**
 * Select где в value следует ключ (он и сохраняется), а на выводе значение из массива options
 * Class FormSelectKey
 * @package Larrock\Core\Helpers\FormBuilder
 */
class FormSelectKey extends FBElement {

    public $options;
    public $option_title;
    public $option_key;

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

    public function setOptionsKey($row)
    {
        $this->option_key = $row;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
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
        }else{
            $selected[] = $data->{$row_settings->name};
        }

        return View::make('larrock::admin.formbuilder.select.key', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data, 'selected' => $selected])->render();
    }
}