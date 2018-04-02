<?php

namespace Larrock\Core\Traits;

use Cache;
use Larrock\Core\Models\Seo;

trait GetSeo
{
    public function getSeo()
    {
        return $this->hasOne(Seo::class, 'seo_id_connect', 'id')->whereSeoTypeConnect($this->config->name);
    }

    public function getGetSeoTitleAttribute()
    {
        $cache_key = sha1('getGetSeoTitleAttribute'.$this->id.$this->config->name);

        return Cache::rememberForever($cache_key, function () {
            if (($get_seo = Seo::whereSeoUrlConnect($this->url)->whereSeoTypeConnect($this->config->name)->first()) && $get_seo->seo_title) {
                return $get_seo->seo_title;
            }
            if (($get_seo = Seo::whereSeoIdConnect($this->id)->whereSeoTypeConnect($this->config->name)->first()) && $get_seo->seo_title) {
                return $get_seo->seo_title;
            }

            return $this->title;
        });
    }
}
