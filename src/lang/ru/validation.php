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
    'dimensions'           => 'Значение поля :attribute has invalid image dimensions.',
    'distinct'             => 'Значение поля :attribute field has a duplicate value.',
    'email'                => 'Значение поля :attribute должно быть email-адресом.',
    'exists'               => 'Значение поля selected :attribute is invalid.',
    'file'                 => 'Значение поля :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute field должно иметь значение.',
    'image'                => 'Поля :attribute должно быть картинкой.',
    'in'                   => 'Значение поля selected :attribute is invalid.',
    'in_array'             => 'Значение поля :attribute field does not exist in :other.',
    'integer'              => 'Значение поля :attribute must be an integer.',
    'ip'                   => 'Значение поля :attribute must be a valid IP address.',
    'ipv4'                 => 'Значение поля :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'Значение поля :attribute must be a valid IPv6 address.',
    'json'                 => 'Значение поля :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'Значение поля :attribute may not be greater than :max.',
        'file'    => 'Значение поля :attribute may not be greater than :max kilobytes.',
        'string'  => 'Значение поля :attribute may not be greater than :max characters.',
        'array'   => 'Значение поля :attribute may not have more than :max items.',
    ],
    'mimes'                => 'Поле :attribute must be a file of type: :values.',
    'mimetypes'            => 'Поле :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute должно быть меньше :min.',
        'file'    => 'Поле :attribute должно быть меньше :min kilobytes.',
        'string'  => 'Поле :attribute должно быть меньше :min characters.',
        'array'   => 'Поле :attribute должно иметь меньше :min значений.',
    ],
    'not_in'               => 'Поле selected :attribute is invalid.',
    'numeric'              => 'Поле :attribute must be a number.',
    'present'              => 'Поле :attribute field must be present.',
    'regex'                => 'Поле :attribute format is invalid.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute field is required when :other is :value.',
    'required_unless'      => 'Поле :attribute field is required unless :other is in :values.',
    'required_with'        => 'Поле :attribute field is required when :values is present.',
    'required_with_all'    => 'Поле :attribute field is required when :values is present.',
    'required_without'     => 'Поле :attribute field is required when :values is not present.',
    'required_without_all' => 'Поле :attribute field is required when none of :values are present.',
    'same'                 => 'Поля :attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'Поле :attribute не должно быть больше :size.',
        'file'    => 'Поле :attribute не должно быть больше :size kb.',
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
