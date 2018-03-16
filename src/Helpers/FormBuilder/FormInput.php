<?php

namespace Larrock\Core\Helpers\FormBuilder;

use View;

class FormInput extends FBElement {

    /** @var bool|null */
    public $typo;

    /**
     * @return $this
     */
    public function setTypo()
    {
        $this->typo = TRUE;
        return $this;
    }

    /**
     * @param $row_settings
     * @param $data
     * @return mixed
     */
    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default){
            $data->{$row_settings->name} = $row_settings->default;
        }
        return View::make('larrock::admin.formbuilder.input.input', ['row_key' => $row_settings->name,
            'row_settings' => $row_settings, 'data' => $data])->render();
    }
}