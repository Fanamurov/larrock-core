<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use LarrockCategory;

trait AdminMethodsIndex
{
    /** @var Component */
    protected $config;

    /**
     * Display a listing of the resource.
     *
     * @return \View
     */
    public function index()
    {
        if(isset($this->config->rows['category'])){
            $data['app_category'] = LarrockCategory::getConfig();
            $data['categories'] = LarrockCategory::getModel()->whereComponent($this->config->name)->whereLevel(1)
                ->orderBy('position', 'DESC')->orderBy('updated_at', 'ASC')->with(['getChild', 'getParent'])->paginate(30);
            return view('larrock::admin.admin-builder.categories', $data);
        }

        if(array_key_exists('position', $this->config->rows)){
            $data['data'] = $this->config->getModel()::orderBy('position', 'DESC')->paginate(30);
        }else{
            $data['data'] = $this->config->getModel()::paginate(30);
        }
        return view('larrock::admin.admin-builder.index', $data);
    }
}