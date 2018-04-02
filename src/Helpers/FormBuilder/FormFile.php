<?php

namespace Larrock\Core\Helpers\FormBuilder;

class FormFile extends FBElement
{
    /** @var bool|null Множественная загрузка файлов */
    public $multiple;

    /** @var null|string Фильтр на типы файлов */
    public $accept;

    /** @var string Имя шаблона FormBuilder для отрисовки поля */
    public $FBTemplate = 'larrock::admin.formbuilder.input.file';

    /**
     * Разрешить множественную загрузку файлов.
     * @return $this
     */
    public function setMultiple()
    {
        $this->multiple = true;

        return $this;
    }

    /**
     * Устанавливает фильтр на типы файлов, которые вы можете отправить через поле загрузки файлов.
     * @param string $fileTypes
     * @return $this
     */
    public function setAccept(string $fileTypes)
    {
        $this->accept = $fileTypes;

        return $this;
    }
}
