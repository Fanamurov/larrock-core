<?php

namespace Larrock\Core\Plugins;

use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;

trait PluginSeoTrait
{
    /**
     * Подключение плагина SEO
     * @return $this
     */
    public function addPluginSeo()
    {
        $row = new FormInput('seo_title', 'Title материала');
        $this->rows['seo_title'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormInput('seo_description', 'Description материала');
        $this->rows['seo_description'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormTextarea('seo_keywords', 'Keywords материала');
        $this->rows['seo_keywords'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setCssClass('not-editor uk-width-1-1');

        $this->plugins_backend['seo']['rows'] = $rows_plugin;

        $row = new FormInput('url', 'URL материала');
        $this->rows['url'] = $row->setTab('seo', 'SEO')
            ->setValid('max:155|required|unique:'. $this->table .',url,:id')->setCssClass('uk-width-1-1')->setFillable();

        return $this;
    }
}