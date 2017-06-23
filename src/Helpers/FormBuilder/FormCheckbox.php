<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormCheckbox extends FBElement {

    public $checked;

    public function setChecked()
    {
        $this->checked = TRUE;
        return $this;
    }

    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }
        return View::make('larrock::admin.formbuilder.checkbox.default', ['row_key' => $row_settings->name, 'row_settings' => $row_settings, 'data' => $data])->render();
    }
}