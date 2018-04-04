<?php

namespace Larrock\Core\Traits;

trait ShareMethods
{
    /**
     * Расшаривание переменных в шаблон, указывающих на использование методов управления контентом
     * @return array
     */
    public function shareMethods()
    {
        $allowed = [];
        if (method_exists($this, 'create')) {
            \View::share('allowCreate', true);
            $allowed[] = 'allowCreate';
        }
        if (method_exists($this, 'index')) {
            \View::share('allowIndex', true);
            $allowed[] = 'allowIndex';
        }
        if (method_exists($this, 'store')) {
            \View::share('allowStore', true);
            $allowed[] = 'allowStore';
        }
        if (method_exists($this, 'destroy')) {
            \View::share('allowDestroy', true);
            $allowed[] = 'allowDestroy';
        }
        if (method_exists($this, 'edit')) {
            \View::share('allowEdit', true);
            $allowed[] = 'allowEdit';
        }
        if (method_exists($this, 'update')) {
            \View::share('allowUpdate', true);
            $allowed[] = 'allowUpdate';
        }
        if (method_exists($this, 'show')) {
            \View::share('allowShow', true);
            $allowed[] = 'allowShow';
        }

        return $allowed;
    }
}
