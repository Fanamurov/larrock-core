<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use LarrockCategory;

trait AdminMethodsShow
{
    /** @var Component */
    protected $config;

    /**
     * Показ подразделов/материалов раздела внутри компонента
     *
     * @param int   $id     ID раздела
     * @return \View
     */
    public function show($id)
    {
        $data['app_category'] = LarrockCategory::getConfig();
        $data['category'] = LarrockCategory::getModel()->whereId($id)->with(['get_child', 'get_parent'])->firstOrFail();
        $data['data'] = $this->config->getModel()->whereHas('get_category', function ($q) use ($id){
            $q->where('category.id', '=', $id);
        })->orderByDesc('position')->orderBy('updated_at', 'ASC')->paginate('50');
        return view('larrock::admin.admin-builder.categories', $data);
    }
}