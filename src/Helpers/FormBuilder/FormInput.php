<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FormInput extends FBElement
{
    /** @var bool|null */
    public $typo;

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.input.input';

    /**
     * Включить типограф
     * @return $this
     */
    public function setTypo()
    {
        $this->typo = TRUE;
        return $this;
    }
}