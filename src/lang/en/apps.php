<?php

return [

	'update' => array(
		'success' => 'Content :name successfully changed',
		'error' => 'Content :name not changed',
        'nothing' => 'Content :name not required changes'
	),

    'delete' => array(
        'success' => 'Content :name успешно удален',
        'error' => 'Content :name не удален'
    ),

    'create' => [
        'success' => 'Content :name successfully created',
        'success-temp' => 'Draft material added successfully',
        'error' => 'Failed to add content :name',
    ],

    //Обновление поля
    'row' => [
        'update' => 'Поле :name успешно изменено',
        'error' => 'Ошибка! Поле :name не изменено',
        'blank' => 'Передано текущее значение поля :name. Ничего не изменено',
        '404' => 'Параметр :name не передан',
    ],

    //Параметры
    'param' => [
        '404' => 'Параметр :name не передан',
    ],

    //Обновление других данных
    'data' => [
        'update' => 'Данные :name обновлены',
        'error' => 'Ошибка! Данные :name не обновлены',
        'blank' => 'Данные :name не требуют обновления',
    ],

    'cache' => [
        'clear' => 'Кеш успешно очищен'
    ],

    //Загрузка файлов
    'upload' => [
        'success' => 'Файл :name успешно загружен',
        'error' => 'Ошибка! Файл :name не загружен',
        'not_valid' => 'Ошибка! Файл :name не валиден для загрузки'
    ],

    //Удаление файлов
    'delete' => [
        'file' => 'Файл удален',
        'file_name' => 'Файл :name удален',
        'files' => 'Файлы удалены',
    ],

    '404' => 'Данные не найдены',
];
