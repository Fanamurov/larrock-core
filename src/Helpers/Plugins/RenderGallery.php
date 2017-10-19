<?php

namespace Larrock\Core\Helpers\Plugins;

class RenderGallery
{
    /**
     * Прикрепление отображения фото-галереи внутри материала
     * @param $modelResult
     * @return mixed
     */
    public function renderGallery($modelResult)
    {
        $re = "/{Фото\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";

        if(isset($modelResult->short)){
            preg_match_all($re, $modelResult->short, $matches);
            foreach($matches['type'] as $key => $match){
                $name = $matches['name'][$key];
                //Собираем изображения под каждую найденную галерею
                $matched_images['images'] = [];
                foreach($modelResult->getImages as $image){
                    if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                        $matched_images['images'][] = $image;
                    }
                }
                $modelResult->short = preg_replace('/{Фото\\[\\w+\\X+]='.$name.'}/',
                    view('larrock::front.plugins.photoGallery.'. $match, $matched_images)->render(), $modelResult->short);
            }
        }

        if(isset($modelResult->description)){
            preg_match_all($re, $modelResult->description, $matches);
            foreach($matches['type'] as $key => $match){
                $name = $matches['name'][$key];
                //Собираем изображения под каждую найденную галерею
                $matched_images['images'] = [];
                foreach($modelResult->getImages as $image){
                    if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                        $matched_images['images'][] = $image;
                    }
                }
                $modelResult->description = preg_replace('/{Фото\\[\\w+\\X+]='.$name.'}/',
                    view('larrock::front.plugins.photoGallery.'. $match, $matched_images)->render(), $modelResult->description);
            }
        }

        return $modelResult;
    }

    /**
     * Прикрепление отображения фото-галереи внутри материала
     * @param $modelResult
     * @return mixed
     */
    public function renderFilesGallery($modelResult)
    {
        $re = "/{Файлы\\[(?P<type>[a-zA-Z0-9-_а-яА-Я\s]+)]=(?P<name>[a-zA-Z0-9-_а-яА-Я\s]+)}/u";

        if(isset($modelResult->short)){
            preg_match_all($re, $modelResult->short, $matches);
            foreach($matches['type'] as $key => $match){
                $name = $matches['name'][$key];
                //Собираем изображения под каждую найденную галерею
                $matched_images['files'] = [];
                foreach($modelResult->getFiles as $image){
                    if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                        $matched_images['files'][] = $image;
                    }
                }
                $modelResult->short = preg_replace('/{Файлы\\[[a-zA-z]*]='.$name.'}/',
                    view('larrock::front.plugins.fileGallery.'. $match, $matched_images)->render(), $modelResult->short);
            }
        }

        if(isset($modelResult->description)){
            preg_match_all($re, $modelResult->description, $matches);
            foreach($matches['type'] as $key => $match){
                $name = $matches['name'][$key];
                //Собираем изображения под каждую найденную галерею
                $matched_images['files'] = [];
                foreach($modelResult->getFiles as $image){
                    if($image->getCustomProperty('gallery') === $matches['name'][$key]){
                        $matched_images['files'][] = $image;
                    }
                }
                $modelResult->description = preg_replace('/{Файлы\\[[a-zA-z]*]='.$name.'}/',
                    view('larrock::front.plugins.fileGallery.'. $match, $matched_images)->render(), $modelResult->description);
            }
        }
        return $modelResult;
    }
}