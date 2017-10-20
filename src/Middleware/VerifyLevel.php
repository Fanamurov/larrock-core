<?php

namespace Larrock\Core\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Session;
use Ultraware\Roles\Exceptions\LevelDeniedException;
use Alert;

class VerifyLevel
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $level
     * @return mixed
     * @throws \Ultraware\Roles\Exceptions\LevelDeniedException
     */
    public function handle($request, Closure $next, $level)
    {
        if ($this->auth->check() && $this->auth->user()->level() >= $level) {
            return $next($request);
        }

        Session::push('message.danger', 'У вас недостаточный уровень для выполнения операции');
        return redirect('/login');

        //throw new LevelDeniedException($level);
    }
}