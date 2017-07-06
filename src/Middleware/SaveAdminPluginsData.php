<?php

namespace Larrock\Core\Middleware;

use Larrock\Core\Component;
use Closure;

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
        $component = new Component();
        $component->savePluginsData($request);

        return $next($request);
    }
}