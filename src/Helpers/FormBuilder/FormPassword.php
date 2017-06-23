<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormPassword extends FBElement {

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }
        return View::make('larrock::admin.formbuilder.input.password', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data])->render();
    }
}