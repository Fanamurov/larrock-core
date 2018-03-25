<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FormDate extends FBElement
{
    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.input.date';

    public function __construct(string $name, string $title)
    {
        parent::__construct($name, $title);
        $this->setDefaultValue(date('Y-m-d'));
    }
}