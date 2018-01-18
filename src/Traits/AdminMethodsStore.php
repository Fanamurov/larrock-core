<?php

namespace Larrock\Core\Traits;

use Illuminate\Http\Request;
use Larrock\Core\Component;
use Redirect;
use Validator;
use Session;

trait AdminMethodsStore
{
    /**
     * @var Component
     */
    protected $config;

    /**
     * @var bool Разрешать ли делать редиректы
     * Если трейт используется не в стандартных целях
     */
    protected $allow_redirect = TRUE;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(array_key_exists('url', $this->config->rows) &&
            $search_blank = $this->config->getModel()::whereUrl($request->get('url'))->first()){
            Session::push('message.danger', 'Материал с таким URL "'. $request->get('url') .'" уже существует');
            if($this->allow_redirect){
                return redirect()->to('/admin/'. $this->config->name .'/'. $search_blank->id. '/edit');
            }else{
                return NULL;
            }
        }

        $validator = Validator::make($request->all(), Component::_valid_construct($this->config->valid));
        if($validator->fails()){
            if($this->allow_redirect){
                return back()->withInput($request->except('password'))->withErrors($validator);
            }else{
                Session::push('message.danger', 'Валидация данных не пройдена '. var_dump($validator));
                return NULL;
            }
        }

        $data = $this->config->getModel();
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
            \Cache::flush();
            Session::push('message.success', 'Материал '. $request->input('title') .' добавлен');
            if($this->allow_redirect){
                return Redirect::to('/admin/'. $this->config->name .'/'. $data->id .'/edit')->withInput();
            }else{
                return $data;
            }
        }

        Session::push('message.danger', 'Материал '. $request->input('title') .' не добавлен');
        if($this->allow_redirect){
            return back()->withInput();
        }

        return NULL;
    }
}