<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;

class CheckSelfMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   echo $request->mem
        return $next($request);
    }
}
