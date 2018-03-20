<?php

namespace Larrock\Core\Traits;

use Breadcrumbs;
use JsValidator;
use Larrock\Core\Component;
use View;

trait AdminMethodsEdit
{
    /** @var Component */
    protected $config;

    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return View
     * @throws \DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException
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
            if($data->getCategory){
                if(isset($data->getCategory->id)){
                    foreach($data->getCategory->parent_tree as $item){
                        $active = ' [Не опубликован!]';
                        if($item->active === 1){
                            $active = '';
                        }
                        $breadcrumbs->push($item->title . $active, '/admin/'. $this->config->name .'/'. $item->id);
                    }
                    $current_level = $this->config->getModel()->whereCategory($data->getCategory->id)->orderBy('updated_at', 'DESC')->take('15')->get();
                }else{
                    foreach($data->getCategory->first()->parent_tree as $item){
                        $active = ' [Не опубликован!]';
                        if($item->active === 1){
                            $active = '';
                        }
                        $breadcrumbs->push($item->title . $active, '/admin/'. $this->config->name .'/'. $item->id);
                    }
                }
            }else{
                if($data->parent){
                    $breadcrumbs->push($data->getConfig()->title, '/admin/'. $data->getConfig()->name .'/'. $data->parent);
                }
            }
            if($data->title){
                $active = ' [Не опубликован!]';
                if($data->active === 1){
                    $active = '';
                }
                $breadcrumbs->push($data->title . $active, '/admin/'. $this->config->getName() .'/'. $data->id, ['current_level' => $current_level]);
            }else{
                $breadcrumbs->push('Элемент');
            }
        });
        return view('larrock::admin.admin-builder.edit', $data);
    }
}