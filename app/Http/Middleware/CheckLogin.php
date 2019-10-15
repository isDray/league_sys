<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Closure;

class CheckLogin
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
        if( empty($request->session()->get('user_id')) || ( empty($request->session()->get('login')) || $request->session()->get('login') != true ) ){
           
           $WantTo = Route::getFacadeRoot()->current()->uri();

           $request->session()->put('WantTo', $WantTo );
           
           return redirect('/login');
        }
        
        return $next($request);
    }
}
