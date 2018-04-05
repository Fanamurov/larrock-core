<?php

namespace Larrock\Core\Traits;

use Session;
use Redirect;
use Validator;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Events\ComponentItemStored;
use Larrock\Core\Helpers\FormBuilder\FormDate;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;

trait AdminMethodsStore
{
    /** @var Component */
    protected $config;

    /**
     * @var bool Разрешать ли делать редиректы
     * Возвращать пользователя на страницу вместо ответа api
     */
    protected $allow_redirect = true;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $this->config->getModel();
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

        $validator = Validator::make($data->toArray(), $this->config->getValid());
        if ($validator->fails()) {
            if ($this->allow_redirect) {
                if (array_key_exists('url', $validator->failed())) {
                    $search = $this->config->getModel()::whereUrl($data->url)->first();
                    MessageLarrock::danger('Материал с тарим url уже существует: /admin/'.$this->config->name.'/'.$search->id.'/edit');
                }

                return back()->withInput($request->except('password'))->withErrors($validator);
            }

            return response()->json(['status' => 'danger', 'message' => 'Материал не прошел валидацию']);
        }

        if ($data->save()) {
            event(new ComponentItemStored($this->config, $data, $request));
            \Cache::flush();
            Session::push('message.success', 'Материал '.$request->input('title').' добавлен');
            if ($this->allow_redirect) {
                return Redirect::to('/admin/'.$this->config->name.'/'.$data->id.'/edit')->withInput();
            }

            return $data;
        }

        Session::push('message.danger', 'Материал '.$request->input('title').' не добавлен');

        if ($this->allow_redirect) {
            return back()->withInput();
        }

        return null;
    }
}
