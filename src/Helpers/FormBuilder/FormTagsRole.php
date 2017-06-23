<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormTagsRole extends FBElement {

    public $options;
    public $model_link;
    public $link_key;
    public $max_items = 99999;

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setModelLink($link_name, $link_key)
    {
        $this->model_link = $link_name;
        $this->link_key = $link_key;
        return $this;
    }

    public function setMaxItems($max = null)
    {
        $this->max_items = $max;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if($row_settings->connect){
            $model = new $row_settings->connect->model;
            if(isset($row_settings->connect->where_key)){
                $model->where($row_settings->connect->where_key, '=', $row_settings->connect->where_value);
            }
            $tags = $model->get();
            $selected = collect();
            if($data){
                if($row_settings->connect->relation_name){
                    $selected = $data->{$row_settings->connect->relation_name};
                }else{
                    $selected = $data->{$row_settings->name};
                }
            }
        }else{
            return response('Метод connect не определен');
        }
        return View::make('larrock::admin.formbuilder.tags.tagsRole', ['tags' => $tags, 'data' => $data, 'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
    }
}