<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Closure;

class CheckMemberLogin
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
        if( empty($request->session()->get('member_id')) || ( empty($request->session()->get('member_login')) || $request->session()->get('member_login') != true ) ){
           
           $WantTo = Route::getFacadeRoot()->current()->uri();

           $request->session()->put('WantTo', $WantTo );
           
           return redirect('/member_login');
        }
                
        return $next($request);
    }
}
