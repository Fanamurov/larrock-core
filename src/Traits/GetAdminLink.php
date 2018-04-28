<?php

namespace Larrock\Core\Traits;

use Cache;

trait GetAdminLink
{
    public function getAdminUrlAttribute()
    {
        $cache_key = sha1('getAdminUrlAttribute'.$this->id.$this->config->name);

        return Cache::rememberForever($cache_key, function () {
            return '/admin/'.$this->config->name.'/'.$this->id.'/edit';
        });
    }
}
