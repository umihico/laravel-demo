<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$args)
    {
        $correct_user = $args[0];
        $correct_password = $args[1];
        switch (true) {
            case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
            case $_SERVER['PHP_AUTH_USER'] !== $correct_user:
            case $_SERVER['PHP_AUTH_PW'] !== $correct_password:
                header('WWW-Authenticate: Basic realm="Access denied"');
                header('Content-Type: text/plain; charset=utf-8');
                die('Not authorized');
        }
        return $next($request);
    }
}
