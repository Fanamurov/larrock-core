<?php

namespace Larrock\Core\Traits;

use Session;
use Redirect;
use Validator;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Events\ComponentItemStored;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (array_key_exists('url', $this->config->rows) &&
            $search_blank = $this->config->getModel()::whereUrl($request->get('url'))->first()) {
            Session::push('message.danger', 'Материал с таким URL "'.$request->get('url').'" уже существует');
            if ($this->allow_redirect) {
                return redirect()->to('/admin/'.$this->config->name.'/'.$search_blank->id.'/edit');
            }

            return null;
        }

        $validator = Validator::make($request->all(), Component::_valid_construct($this->config->valid));
        if ($validator->fails()) {
            if ($this->allow_redirect) {
                return back()->withInput($request->except('password'))->withErrors($validator);
            }
            $message = '';
            foreach ($validator->getMessageBag()->all() as $error) {
                $message .= $error.' ';
            }
            Session::push('message.danger', 'Валидация данных не пройдена '.$message);

            return null;
        }

        $data = $this->config->getModel();
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
