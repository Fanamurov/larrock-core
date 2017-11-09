<?php

namespace Larrock\Core\Traits;

use Illuminate\Http\Request;

trait AdminMethodsCreate{

    /**
     * Creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( !method_exists($this, 'store')){
            return abort(403, 'AdminMethodsStore not found in this Controller');
        }
        $post_rows = [
            'title' => 'Новый материал',
            'url' => 'novyy-material'
        ];

        if($request->has('category')){
            $post_rows['category'] = $request->get('category');
        }

        foreach ($this->config->rows as $row){
            if($row->default){
                $post_rows[$row->name] = $row->default;
            }
            if($row->name === 'user_id'){
                $post_rows[$row->name] = \Auth::id();
            }
        }

        if(array_key_exists('position', $this->config->rows)){
            $post_rows['active'] = 0;
        }
        $test = Request::create('/admin/'. $this->config->name, 'POST', $post_rows);
        return $this->store($test);
    }
}