<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FormTextarea extends FBElement
{
    /** @var null|bool */
    public $typo;

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.textarea.editor';

    /**
     * @return $this
     */
    public function setTypo()
    {
        $this->typo = TRUE;
        return $this;
    }

    /**
     * @return $this
     */
    public function setNotEditor()
    {
        $this->cssClass .= ' not-editor';
        return $this;
    }
}