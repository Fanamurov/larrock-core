<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use View;

trait AdminMethodsIndex{
    /**
     * @var Component
     */
    protected $config;


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if(array_key_exists('position', $this->config->rows)){
            $data['data'] = $this->config->getModel()::orderBy('position', 'DESC')->paginate(30);
        }else{
            $data['data'] = $this->config->getModel()::paginate(30);
        }
        return view('larrock::admin.admin-builder.index', $data);
    }
}