<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormDate extends FBElement {

    public function render($row_settings, $data)
    {
        $row_settings->default = date('Y-m-d');
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }
        return View::make('larrock::admin.formbuilder.input.date', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data])->render();
    }
}