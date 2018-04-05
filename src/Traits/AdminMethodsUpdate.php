<?php

namespace Larrock\Core\Traits;

use Validator;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Events\ComponentItemUpdated;
use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;

trait AdminMethodsUpdate
{
    /**
     * @var Component
     */
    protected $config;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $data = $this->config->getModel()::find($id);
        $data->fill($request->all());
        foreach ($this->config->rows as $row) {
            if (\in_array($row->name, $data->getFillable())) {
                if ($row instanceof FormCheckbox) {
                    $data->{$row->name} = $request->input($row->name, $row->default);
                }
                if ($row instanceof FormDate) {
                    $data->{$row->name} = $request->input('date', date('Y-m-d'));
                }
            }
        }

        $validator = Validator::make($data->toArray(), $this->config->getValid($id));
        if ($validator->fails()) {
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        if ($data->save()) {
            event(new ComponentItemUpdated($this->config, $data, $request));
            MessageLarrock::success('Материал '.$request->input('title').' изменен');
            \Cache::flush();

            return back();
        }

        MessageLarrock::danger('Материал '.$request->input('title').' не изменен');

        return back()->withInput();
    }
}
