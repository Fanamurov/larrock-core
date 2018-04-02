<?php

namespace Larrock\Core\Traits;

use Session;
use Validator;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Events\ComponentItemUpdated;

trait AdminMethodsUpdate
{
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
        if ($validator->fails()) {
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        $data = $this->config->getModel()::find($id);
        $data->fill($request->all());
        foreach ($this->config->rows as $row) {
            if (\in_array($row->name, $data->getFillable())) {
                if ($row instanceof \Larrock\Core\Helpers\FormBuilder\FormCheckbox) {
                    $data->{$row->name} = $request->input($row->name, null);
                }
                if ($row instanceof \Larrock\Core\Helpers\FormBuilder\FormDate) {
                    $data->{$row->name} = $request->input('date', date('Y-m-d'));
                }
            }
        }

        if ($data->save()) {
            event(new ComponentItemUpdated($this->config, $data, $request));
            Session::push('message.success', 'Материал '.$request->input('title').' изменен');
            \Cache::flush();

            return back();
        }

        Session::push('message.danger', 'Материал '.$request->input('title').' не изменен');

        return back()->withInput();
    }
}
