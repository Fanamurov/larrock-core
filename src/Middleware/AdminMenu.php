<?php

namespace Larrock\Core\Middleware;

use Larrock\Core\Helpers\AdminMenuBuilder;
use Closure;
use Route;
use View;

class AdminMenu
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
    	$menu = new AdminMenuBuilder();
        if(Route::current()){
            View::share('top_menu', $menu->top_menu());
        }

        return $next($request);
    }
}
