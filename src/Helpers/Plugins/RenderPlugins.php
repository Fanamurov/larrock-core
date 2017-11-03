<?php

namespace Larrock\Core\Helpers\Plugins;

use LarrockBlocks;

class RenderPlugins
{
    public $rendered_html;
    protected $model;

    public function __construct($html, $model = NULL)
    {
        $this->rendered_html = $html;
        $this->model = $model;
    }

    public function renderBlocks()
    {
        $re = "/{Блок\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        foreach($matches['type'] as $key => $match){
            $name = $matches['name'][$key];
            if($matched_block = LarrockBlocks::getModel()->whereUrl($matches['name'][$key])->first()){
                $this->rendered_html = preg_replace('/<p>{Блок\\[\\w+\\X+]='.$name.'}<\/p>/',
                    view('larrock::front.plugins.renderBlock.'. $match, ['data' => $matched_block])->render(), $this->rendered_html);
                $this->rendered_html = preg_replace('/{Блок\\[\\w+\\X+]='.$name.'}/',
                    view('larrock::front.plugins.renderBlock.'. $match, ['data' => $matched_block])->render(), $this->rendered_html);
            }
        }
        return $this;
    }

    public function renderImageGallery()
    {
        $re = "/{Фото\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        foreach($matches['type'] as $key => $match){
            $name = $matches['name'][$key];
            //Собираем изображения под каждую найденную галерею
            $matched_images['images'] = [];
            foreach($this->model->getImages as $image){
                if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                    $matched_images['images'][] = $image;
                }
            }
            $this->rendered_html = preg_replace('/<p>{Фото\\[\\w+\\X+]='.$name.'}<\/p>/',
                view('larrock::front.plugins.photoGallery.'. $match, $matched_images)->render(), $this->rendered_html);
            $this->rendered_html = preg_replace('/{Фото\\[\\w+\\X+]='.$name.'}/',
                view('larrock::front.plugins.photoGallery.'. $match, $matched_images)->render(), $this->rendered_html);
        }
        return $this;
    }

    public function renderFilesGallery()
    {
        $re = "/{Файлы\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";
        preg_match_all($re, $this->rendered_html, $matches);
        foreach($matches['type'] as $key => $match){
            $name = $matches['name'][$key];
            //Собираем изображения под каждую найденную галерею
            $matched_files['files'] = [];
            foreach($this->model->getFiles as $image){
                if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                    $matched_files['files'][] = $image;
                }
            }
            $this->rendered_html = preg_replace('/<p>{Файлы\\[\\w+\\X+]='.$name.'}<\/p>/',
                view('larrock::front.plugins.fileGallery.'. $match, $matched_files)->render(), $this->rendered_html);
            $this->rendered_html = preg_replace('/{Файлы\\[\\w+\\X+]='.$name.'}/',
                view('larrock::front.plugins.fileGallery.'. $match, $matched_files)->render(), $this->rendered_html);
        }
        return $this;
    }
}