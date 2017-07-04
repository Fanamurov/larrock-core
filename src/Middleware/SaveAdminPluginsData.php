<?php

namespace Larrock\Core\Middleware;

use Larrock\Core\Component;
use Larrock\Core\Helpers\AdminMenuBuilder;
use Closure;
use Route;
use View;

class SaveAdminPluginsData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user() && auth()->user()->role()->first()->level === 3){
            $component = new Component();
            $component->savePluginsData($request);
        }

        return $next($request);
    }
}