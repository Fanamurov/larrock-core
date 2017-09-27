<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Поле :attribute должно быть принято.',
    'active_url'           => 'Поле :attribute не валидный URL.',
    'after'                => 'Поле :attribute должно быть старше :date.',
    'after_or_equal'       => 'Поле :attribute должно быть старше или совпадать с :date.',
    'alpha'                => 'Поле :attribute должно иметь только буквы.',
    'alpha_dash'           => 'Поле :attribute должно иметь только буквы, цифры и дефисы.',
    'alpha_num'            => 'Поле :attribute должно иметь только буквы, цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'Поле :attribute должно быть ранее :date.',
    'before_or_equal'      => 'Поле :attribute должно быть ранее или равно :date.',
    'between'              => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file'    => 'Файл :attribute должен иметь размер от :min и до :max kilobytes.',
        'string'  => 'Поле :attribute должно иметь от :min и до :max символов.',
        'array'   => 'Поле :attribute должно иметь от :min и до :max значений.',
    ],
    'boolean'              => 'Значение поля :attribute должно быть true или false.',
    'confirmed'            => 'Значение поля :attribute должно быть подтверждено.',
    'date'                 => 'Значение поля :attribute не является валидной датой.',
    'date_format'          => 'Значение поля :attribute не является датой в формате :format.',
    'different'            => 'Значение поля :attribute и :other должны быть разными.',
    'digits'               => 'Значение поля :attribute должно быть :digits числом.',
    'digits_between'       => 'Значение поля :attribute должно быть от :min и до :max.',
    'dimensions'           => 'Изображение :attribute имеет не валидное соотношение сторон.',
    'distinct'             => 'Значение поля :attribute содержит повторящиеся значения.',
    'email'                => 'Значение поля :attribute должно быть email-адресом.',
    'exists'               => 'Значение поля selected :attribute должно быть валидным.',
    'file'                 => 'Значение поля :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute field должно иметь значение.',
    'image'                => 'Поля :attribute должно быть картинкой.',
    'in'                   => 'Значение поля :attribute не валидно.',
    'in_array'             => 'Значение поля :attribute не присутствует в :other.',
    'integer'              => 'Значение поля :attribute должно быть числом.',
    'ip'                   => 'Значение поля :attribute должно быть IP адресом.',
    'ipv4'                 => 'Значение поля :attribute должно быть IPv4 адресом.',
    'ipv6'                 => 'Значение поля :attribute должно быть IPv6 адресом.',
    'json'                 => 'Значение поля :attribute должно быть JSON строкой.',
    'max'                  => [
        'numeric' => 'Значение поля :attribute не может быть больше чем :max.',
        'file'    => 'Значение поля :attribute не может быть больше :max килобайт.',
        'string'  => 'Значение поля :attribute не может быть больше :max знаков.',
        'array'   => 'Значение поля :attribute не может быть больше :max значений.',
    ],
    'mimes'                => 'Поле :attribute должно быть формата: :values.',
    'mimetypes'            => 'Поле :attribute должно быть типа: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute должно быть меньше :min.',
        'file'    => 'Поле :attribute должно быть меньше :min килобайт.',
        'string'  => 'Поле :attribute должно быть меньше :min знаков.',
        'array'   => 'Поле :attribute должно иметь меньше :min значений.',
    ],
    'not_in'               => ':attribute не валиден.',
    'numeric'              => 'Поле :attribute должно быть номером.',
    'present'              => 'Поле :attribute должно присутствовать.',
    'regex'                => 'Формат поля :attribute не валиден.',
    'required'             => 'Значение :attribute обязательно для заполнения.',
    'required_if'          => 'Значение :attribute обязательно когда :other - :value.',
    'required_unless'      => 'Значение :attribute обязательно: если :other - :values.',
    'required_with'        => 'Значение :attribute обязательно когда :values присутствует.',
    'required_with_all'    => 'Значение :attribute обязательно когда :values присутствует.',
    'required_without'     => 'Значение :attribute обязательно когда :values не присутствует.',
    'required_without_all' => 'Значение :attribute не требуется, если одно из :values присутствует.',
    'same'                 => 'Поля :attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'Поле :attribute не должно быть больше :size.',
        'file'    => 'Поле :attribute не должно быть больше :size килобайт.',
        'string'  => 'Поле :attribute не должно быть больше :size знаков.',
        'array'   => 'Поле :attribute не должно быть больше :size значений.',
    ],
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть валидной временной зоной.',
    'unique'               => 'Поле :attribute должно быть уникальным.',
    'uploaded'             => 'Файл :attribute не возможно загрузить.',
    'url'                  => 'Ссылка :attribute не валидна.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'family' => 'Фамилия',
        'firstname' => 'Имя',
        'lastname' => 'Отчество',
        'date' => 'Дата',
        'birthdate' => 'Дата рождения',
        'place' => 'Место рождения',
        'tel' => 'Номер телефона',
        'education' => 'Образование',
        'worker' => 'Опыт работы',
        'comment' => 'Комментарий',
        'agree' => 'Согласие на обработку персональных данных',
        'code' => 'Код',
        'email' => 'Email',
        'cizizen' => 'Гражданство',
        'name' => 'Ваше имя',
        'contact' => 'Ваши контакты'
    ],

];
