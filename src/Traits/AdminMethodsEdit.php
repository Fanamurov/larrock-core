<?php

namespace Larrock\Core\Traits;

use Breadcrumbs;
use JsValidator;
use Larrock\Core\Component;
use View;

trait AdminMethodsEdit{

    /**
     * @var Component
     */
    protected $config;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $data['data'] = $this->config->getModel()::findOrFail($id);
        $data['app'] = $this->config->tabbable($data['data']);

        $validator = JsValidator::make(Component::_valid_construct($this->config, 'update', $id));
        View::share('validator', $validator);

        Breadcrumbs::register('admin.'. $this->config->name .'.edit', function($breadcrumbs, $data){
            $current_level = NULL;
            $breadcrumbs->parent('admin.'. $this->config->name .'.index');
            if($data->get_category){
                if(isset($data->get_category->id)){
                    foreach($data->get_category->parent_tree as $item){
                        $breadcrumbs->push($item->title, '/admin/'. $this->config->name .'/'. $item->id);
                    }
                    $current_level = $this->config->getModel()->whereCategory($data->get_category->id)->orderBy('updated_at', 'DESC')->take('15')->get();
                }else{
                    foreach($data->get_category->first()->parent_tree as $item){
                        $breadcrumbs->push($item->title, '/admin/'. $this->config->name .'/'. $item->id);
                    }
                }
            }
            if($data->title){
                $breadcrumbs->push($data->title, '/admin/'. $this->config->getName() .'/'. $data->id, ['current_level' => $current_level]);
            }else{
                $breadcrumbs->push('Элемент');
            }
        });

        return view('larrock::admin.admin-builder.edit', $data);
    }
}