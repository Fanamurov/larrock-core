<?php

namespace Larrock\Core\Plugins;

use Larrock\ComponentFeed\Models\Feed;
use Larrock\Core\Models\Link;
use LarrockAdminSeo;
use LarrockFeed;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Models\Seo;

/**
 * Аттач/детач данных плагинов и связанных полей
 * Class ComponentPlugin
 * @package Larrock\Core\Plugins
 */
class ComponentPlugin
{

    /**
     * Сохранение связей полей и данных плагинов
     * @param $event
     * @throws \Exception
     */
    public function attach($event)
    {
        $this->attachRows($event);
        $this->attachSeo($event);
        $this->attachAnonsModule($event);
    }

    /**
     * Сохранение данных сео-плагина
     * @param $event
     * @throws \Exception
     * @return \Larrock\Core\Models\Seo|Seo|null
     */
    protected function attachSeo($event)
    {
        /** @var Seo $seo */
        $seo = LarrockAdminSeo::getModel()->whereSeoIdConnect($event->model->id)->whereSeoTypeConnect($event->component->name)->first();

        if($seo){
            if( !empty($event->request->get('seo_title')) ||
                !empty($event->request->get('seo_description')) ||
                !empty($event->request->get('seo_seo_keywords'))){
                $seo->seo_id_connect = $event->model->id;
                $seo->seo_url_connect = $event->request->get('url_connect');
                $seo->seo_title = $event->request->get('seo_title');
                $seo->seo_description = $event->request->get('seo_description');
                $seo->seo_keywords = $event->request->get('seo_keywords');
                $seo->seo_type_connect = $event->component->name;
                if($seo->save()){
                    MessageLarrock::success('SEO обновлено');
                    return $seo;
                }
            }else{
                $seo->delete();
                MessageLarrock::success('SEO удалено');
                return $seo;
            }
        }else{
            if( !empty($event->request->get('seo_title')) ||
                !empty($event->request->get('seo_description')) ||
                !empty($event->request->get('seo_seo_keywords'))){
                $seo = LarrockAdminSeo::getModel();
                $seo->seo_id_connect = $event->request->get('id_connect');
                $seo->seo_url_connect = $event->request->get('url_connect');
                $seo->seo_title = $event->request->get('seo_title');
                $seo->seo_description = $event->request->get('seo_description');
                $seo->seo_keywords = $event->request->get('seo_keywords');
                $seo->seo_type_connect = $event->request->get('type_connect');
                if($seo->save()){
                    MessageLarrock::success('SEO добавлено');
                    return $seo;
                }
            }
        }
        return null;
    }

    /**
     * Создание анонса материала через модуль anonsCategory
     * @param $event
     * @return bool|Feed|null
     * @throws \Exception
     */
    protected function attachAnonsModule($event)
    {
        if( !$event->request->has('_jsvalidation') && ($event->request->has('anons_merge') || !empty($event->request->get('anons_description')))){
            if( !config('larrock.feed.anonsCategory')){
                MessageLarrock::danger('larrock.feed.anonsCategory не задан. Анонс создан не будет');
                return TRUE;
            }
            /** @var Feed $anons */
            $anons = LarrockFeed::getModel();
            $anons->title = $event->request->get('title');
            $anons->url = 'anons_'. $event->model->id .''. random_int(1,9999);
            $anons->category = LarrockFeed::getConfig()->settings['anons_category'];
            $anons->user_id = \Auth::id();
            $anons->active = 1;
            $anons->position = LarrockFeed::getModel()->whereCategory(LarrockFeed::getConfig()->settings['anons_category'])->max('position') +1;

            if($event->request->has('anons_merge')){
                $original = LarrockFeed::getModel()->whereId($event->request->get('id_connect'))->first();
                $anons->short = '<a href="'. $original->full_url .'">'. $event->request->get('title') .'</a>';
            }else{
                $anons->short = $event->request->get('anons_description');
            }

            if($anons->save()){
                MessageLarrock::success('Анонс добавлен');
                return $anons;
            }
            MessageLarrock::danger('Анонс не добавлен');
        }
        return null;
    }

    /**
     * Сохранение связей полей
     * @param $event
     */
    protected function attachRows($event)
    {
        if(\is_array($event->component->rows)) {
            foreach ($event->component->rows as $row) {
                if (isset($row->modelParent, $row->modelChild)) {
                    $add_params = ['model_parent' => $row->modelParent, 'model_child' => $row->modelChild];

                    if($event->request->has($row->name) && \is_array($event->request->get($row->name))){
                        foreach ($event->request->get($row->name) as $value){
                            $params[$value] = $add_params;
                        }
                        if(isset($params)){
                            $event->model->getLink($row->modelChild)->sync($params);
                        }
                    }
                }
            }
        }
    }

    /**
     * Удаление всех связей материала компонента
     * @param $event
     */
    public function detach($event)
    {
        $this->detachRows($event);
        $this->detachPlugins($event);
    }

    /**
     * Удаление связей полей
     * @param $event
     */
    protected function detachRows($event)
    {
        if(\is_array($event->component->rows)){
            foreach ($event->component->rows as $row){
                if(isset($row->modelParent, $row->modelChild)){
                    //Получаем id прилинкованных данных
                    $getLink = $event->model->getLink($row->modelChild)->get();

                    //Удаляем связи
                    $event->model->linkQuery($row->modelChild)->delete();

                    //Удаляем поля в modelChild если у поля указана опция setDeleteIfNoLink и больше связей к этому материалу нет
                    if($row->deleteIfNoLink){
                        //Проверяем остались ли связи до modelChild от modelParent
                        foreach ($getLink as $link){
                            $linkModel = new Link();
                            if( !$linkModel::whereIdChild($link->id)->whereModelChild($row->modelChild)->first()){
                                $modelChild = new $row->modelChild();
                                $modelChild->whereId($link->id)->delete();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Удаление данных плагинов
     * @param $event
     */
    protected function detachPlugins($event)
    {
        if(\is_array($event->component->plugins_backend)){
            foreach ($event->component->plugins_backend as $key_plugin => $value_plugin){
                //Detach SEO plugin
                if($key_plugin === 'seo' && $seo = LarrockAdminSeo::getModel()->whereSeoIdConnect($event->model->id)
                        ->whereSeoTypeConnect($event->component->name)->first()){
                    $seo->delete();
                }
            }
        }
    }

}