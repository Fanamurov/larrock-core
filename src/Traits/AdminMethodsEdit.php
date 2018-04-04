<?php

namespace Larrock\Core\Traits;

use View;
use JsValidator;
use Larrock\Core\Component;

trait AdminMethodsEdit
{
    /** @var Component */
    protected $config;

    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
        $data['data'] = $this->config->getModel()::findOrFail($id);
        $data['app'] = $this->config->tabbable($data['data']);

        $validator = JsValidator::make(Component::_valid_construct($this->config, 'update', $id));
        View::share('validator', $validator);

        return view('larrock::admin.admin-builder.edit', $data);
    }
}
