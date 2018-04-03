<?php

namespace Larrock\Core\Middleware;

use View;
use Route;
use Closure;
use Larrock\Core\Helpers\AdminMenuBuilder;

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
        View::share('top_menu', $menu->topMenu());

        return $next($request);
    }
}
