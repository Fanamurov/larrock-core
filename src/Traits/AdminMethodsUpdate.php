<?php

namespace Larrock\Core\Traits;

use Validator;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Events\ComponentItemUpdated;
use Larrock\Core\Helpers\FormBuilder\FormDate;

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
        $validate_data_array = $request->all();
        $data = $this->config->getModel()::find($id);
        $data->fill($request->all());

        foreach ($this->config->rows as $row) {
            if ($row->fillable && ! isset($data->{$row->name})) {
                if ($row instanceof FormDate) {
                    $data->{$row->name} = $request->input('date', date('Y-m-d'));
                } else {
                    $data->{$row->name} = $request->input($row->name, $row->default);
                }
                $validate_data_array[$row->name] = $data->{$row->name};
            }
        }

        $validator = Validator::make($validate_data_array, $this->config->getValid($id));
        if ($validator->fails()) {
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        $data->save();
        event(new ComponentItemUpdated($this->config, $data, $request));
        MessageLarrock::success('Материал '.$request->input('title').' изменен');
        \Cache::flush();

        return back();
    }
}
