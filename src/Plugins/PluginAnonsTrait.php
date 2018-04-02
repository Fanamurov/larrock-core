<?php

namespace Larrock\Core\Plugins;

use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;

trait PluginAnonsTrait
{
    /**
     * Плагин для генерации анонса новости для блока анонс новости.
     * @param int $categoryAnons    ID категории с анонсами
     * @return $this
     */
    public function addAnonsToModule($categoryAnons)
    {
        $row = new FormCheckbox('anons_merge', 'Сгенерировать для анонса заголовок и ссылку на новость');
        $this->rows['anons_merge'] = $rows_plugin[] = $row->setTab('anons', 'Создать анонс');

        $row = new FormTextarea('anons_description', 'Текст для анонса новости в модуле');
        $this->rows['anons_description'] = $rows_plugin[] = $row->setTab('anons', 'Создать анонс')->setTypo();

        $this->settings['anons_category'] = $categoryAnons;
        $this->plugins_backend['anons']['rows'] = $rows_plugin;

        return $this;
    }
}
