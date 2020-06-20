<?php

namespace App\Http\Middleware;

use App\HttpRequest;
use Closure;

class CollectHttpRequestStat
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $now = now();
        $start = floatval($now->timestamp . "." . str_pad($now->milli, 3, "0", STR_PAD_LEFT));
        $response = $next($request);
        $now = now();
        $end = floatval($now->timestamp . "." . str_pad($now->milli, 3, "0", STR_PAD_LEFT));
        $path = $request->path();
        $excludes = [
            'login',  //Postデータにパスワードが含まれるため、ログを禁止
        ];
        if (!in_array($path, $excludes)) {
            try {
                $stat = new HttpRequest();
                $stat->name = env('APP_NAME', 'Laravel');
                $stat->path = $path;
                $stat->milliseconds = ($end - $start) * 1000;
                $stat->data = json_encode($request->all());
                $stat->method = $request->method();
                $stat->save();
            } catch (\Exception $exception) {
                report($exception);
            }
        }
        return $response;
    }
}
