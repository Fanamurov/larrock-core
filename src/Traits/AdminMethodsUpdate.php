<?php

namespace Larrock\Core\Traits;

use Illuminate\Http\Request;
use Larrock\Core\Component;
use Validator;
use Session;

trait AdminMethodsUpdate{

    /**
     * @var Component
     */
    protected $config;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Component::_valid_construct($this->config, 'update', $id));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        $data = $this->config->getModel()::find($id);
        $data->fill($request->all());
        foreach ($this->config->rows as $row){
            if(in_array($row->name, $data->getFillable())){
                if(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormCheckbox'){
                    $data->{$row->name} = $request->input($row->name, NULL);
                }
                if(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormDate'){
                    $data->{$row->name} = $request->input('date', date('Y-m-d'));
                }
            }
        }

        if($data->save()){
            $this->config->actionAttach($this->config, $data, $request);
            Session::push('message.success', 'Материал '. $request->input('title') .' изменен');
            \Cache::flush();
            return back();
        }

        Session::push('message.danger', 'Материал '. $request->input('title') .' не изменен');
        return back()->withInput();
    }
}