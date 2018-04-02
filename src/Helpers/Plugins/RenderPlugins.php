<?php

namespace Larrock\Core\Helpers\Plugins;

use LarrockBlocks;
use Illuminate\Database\Eloquent\Model;

/**
 * Плагин замены шорткатов плагинов на их данные внутри полей компонентов
 * Class RenderPlugins.
 */
class RenderPlugins
{
    /** @var string html-код поля модели для замены шорткатов плагинов на их данные */
    public $rendered_html;

    /** @var null|Model Модель контента */
    protected $model;

    public function __construct($html, $model = null)
    {
        $this->rendered_html = $html;
        $this->model = $model;
    }

    /**
     * Вставка блоков.
     * @return $this
     * @throws \Throwable
     */
    public function renderBlocks()
    {
        $re = "/{Блок\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        foreach ($matches['type'] as $key => $match) {
            $name = $matches['name'][$key];
            if ($matched_block = LarrockBlocks::getModel()->whereUrl($matches['name'][$key])->first()) {
                $this->rendered_html = preg_replace('/<p>{Блок\\['.$match.']='.$name.'}<\/p>/',
                    view('larrock::front.plugins.renderBlock.'.$match, ['data' => $matched_block])->render(), $this->rendered_html);
                $this->rendered_html = preg_replace('/{Блок\\['.$match.']='.$name.'}/',
                    view('larrock::front.plugins.renderBlock.'.$match, ['data' => $matched_block])->render(), $this->rendered_html);
            }
        }

        return $this;
    }

    /**
     * Вставка галерей изображений.
     * @return $this
     * @throws \Throwable
     */
    public function renderImageGallery()
    {
        $re = "/{Фото\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        if (isset($matches['type'][0])) {
            $images = $this->model->getImages;
        }
        foreach ($matches['type'] as $key => $match) {
            $name = $matches['name'][$key];
            //Собираем изображения под каждую найденную галерею
            $matched_images['images'] = [];
            foreach ($images as $image) {
                if ($image->getCustomProperty('gallery') === $matches['name'][$key]) {
                    $matched_images['images'][] = $image;
                }
            }
            $this->rendered_html = preg_replace('/<p>{Фото\\['.$match.']='.$name.'}<\/p>/',
                view('larrock::front.plugins.photoGallery.'.$match, $matched_images)->render(), $this->rendered_html);
            $this->rendered_html = preg_replace('/{Фото\\['.$match.']='.$name.'}/',
                view('larrock::front.plugins.photoGallery.'.$match, $matched_images)->render(), $this->rendered_html);
        }

        return $this;
    }

    /**
     * Вставка галерей файлов.
     * @return $this
     * @throws \Throwable
     */
    public function renderFilesGallery()
    {
        $re = "/{Файлы\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        if (isset($matches['type'][0])) {
            $files = $this->model->getFiles;
        }
        foreach ($matches['type'] as $key => $match) {
            $name = $matches['name'][$key];
            //Собираем изображения под каждую найденную галерею
            $matched_files['files'] = [];
            foreach ($files as $file) {
                if ($file->getCustomProperty('gallery') === $matches['name'][$key]) {
                    $matched_files['files'][] = $file;
                }
            }
            $this->rendered_html = preg_replace('/<p>{Файлы\\['.$match.']='.$name.'}<\/p>/',
                view('larrock::front.plugins.fileGallery.'.$match, $matched_files)->render(), $this->rendered_html);
            $this->rendered_html = preg_replace('/{Файлы\\['.$match.']='.$name.'}/',
                view('larrock::front.plugins.fileGallery.'.$match, $matched_files)->render(), $this->rendered_html);
        }

        return $this;
    }
}
