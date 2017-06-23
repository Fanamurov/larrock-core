<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormTextarea extends FBElement{

    public $typo;


    public function setTypo()
    {
        $this->typo = TRUE;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }
        return View::make('larrock::admin.formbuilder.textarea.editor', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data])->render();
    }
}