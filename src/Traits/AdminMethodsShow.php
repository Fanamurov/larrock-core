<?php

namespace Larrock\Core\Traits;

use Cache;
use LarrockCategory;
use Larrock\Core\Component;

trait AdminMethodsShow
{
    /** @var Component */
    protected $config;

    /**
     * Показ подразделов/материалов раздела внутри компонента.
     *
     * @param int   $id     ID раздела
     * @return \View
     */
    public function show($id)
    {
        $data['app_category'] = LarrockCategory::getConfig();
        $cache_key = sha1('AdminMethodsShowCategory'.$id.$this->config->getModelName());
        $data['category'] = Cache::rememberForever($cache_key, function () use ($id) {
            return LarrockCategory::getModel()->whereId($id)->with(['getChild', 'getParent'])->firstOrFail();
        });
        $cache_key = sha1('AdminMethodsShowData'.$id.$this->config->getModelName());
        $data['data'] = Cache::rememberForever($cache_key, function () use ($id) {
            return $this->config->getModel()->whereHas('getCategory', function ($q) use ($id) {
                $q->where('category.id', '=', $id);
            })->get();
        });

        return view('larrock::admin.admin-builder.categories', $data);
    }
}
