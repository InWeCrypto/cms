<?php

namespace App\Http\Middleware;

class GlobalConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $langs = ['en','zh'];
        if($request->hasHeader('Lang') && in_array($lang = $request->header('Lang'), $langs)){
            \App::setLocale($lang);
        }

        return $next($request);
    }
}
