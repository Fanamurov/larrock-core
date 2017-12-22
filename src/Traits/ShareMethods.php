<?php

namespace Larrock\Core\Traits;

trait ShareMethods
{
    /**
     * Расшаривание переменных в шаблон, указывающих на использование методов управления контентом
     */
    public function shareMethods()
    {
        if(method_exists($this, 'create')){
            \View::share('allowCreate', TRUE);
        }
        if(method_exists($this, 'index')){
            \View::share('allowIndex', TRUE);
        }
        if(method_exists($this, 'store')){
            \View::share('allowStore', TRUE);
        }
        if(method_exists($this, 'destroy')){
            \View::share('allowDestroy', TRUE);
        }
        if(method_exists($this, 'edit')){
            \View::share('allowEdit', TRUE);
        }
        if(method_exists($this, 'update')){
            \View::share('allowUpdate', TRUE);
        }
    }
}