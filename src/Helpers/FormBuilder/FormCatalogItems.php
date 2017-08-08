<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormCatalogItems extends FBElement {

    public $options;
    public $model_link;
    public $link_key;
    public $max_items;

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

    public function render($row_settings, $data)
    {
        $tags = \DB::table($row_settings->connect['table']);
        if(isset($row_settings->connect['where'])){
            $tags->where($row_settings->connect['where'], '=', $row_settings->connect['where_value']);
        }
        $tags = $tags->get();
        $selected = $data->{$row_settings->connect->relation_name};
        return View::make('larrock::admin.formbuilder.tags.tags', ['tags' => $tags, 'data' => $data, 'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
    }
}