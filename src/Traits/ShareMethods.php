<?php

namespace Larrock\Core\Traits;

trait ShareMethods
{
    /** Расшаривание переменных в шаблон, указывающих на использование методов управления контентом */
    public function shareMethods()
    {
        if (method_exists($this, 'create')) {
            \View::share('allowCreate', true);
        }
        if (method_exists($this, 'index')) {
            \View::share('allowIndex', true);
        }
        if (method_exists($this, 'store')) {
            \View::share('allowStore', true);
        }
        if (method_exists($this, 'destroy')) {
            \View::share('allowDestroy', true);
        }
        if (method_exists($this, 'edit')) {
            \View::share('allowEdit', true);
        }
        if (method_exists($this, 'update')) {
            \View::share('allowUpdate', true);
        }
        if (method_exists($this, 'show')) {
            \View::share('allowShow', true);
        }
    }
}
