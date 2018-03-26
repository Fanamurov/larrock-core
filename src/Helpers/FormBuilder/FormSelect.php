<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use View;

class FormSelect extends FormSelectKey
{
    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.select.value';

    /** @var null|bool Разрешить создавать новые элементы */
    public $allowCreate;

    /**
     * Разрешить создавать новые элементы
     * @return $this
     */
    public function setAllowCreate()
    {
        $this->allowCreate = TRUE;
        return $this;
    }
}