<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FormButton extends FBElement
{
    /** @var string Тип кнопки (submit|button|reset) */
    public $buttonType = 'submit';

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.button.button';

    /**
     * Указываем тип кнопки (submit|button|reset).
     * @param $buttonType
     * @return $this
     */
    public function setButtonType($buttonType)
    {
        $this->buttonType = $buttonType;

        return $this;
    }
}
