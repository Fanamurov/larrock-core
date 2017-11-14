<?php
namespace Larrock\Core\Traits;

use Larrock\Core\Models\Seo;

trait GetSeo{

    public function get_seo()
    {
        return $this->hasOne(Seo::class, 'seo_id_connect', 'id')->whereSeoTypeConnect($this->config->name);
    }

    public function getGetSeoTitleAttribute()
    {
        if($get_seo = Seo::whereSeoUrlConnect($this->url)->whereSeoTypeConnect($this->config->name)->first()){
            if($get_seo->seo_title){
                return $get_seo->seo_title;
            }
        }
        if($get_seo = Seo::whereSeoIdConnect($this->id)->whereSeoTypeConnect($this->config->name)->first()){
            if($get_seo->seo_title){
                return $get_seo->seo_title;
            }
        }
        return $this->title;
    }
}