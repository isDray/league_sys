<?php

namespace App\Http\Middleware;
use App\Cus_lib\Lib_common;
use Closure;
use DB;
use View;
class CheckLeague
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
        $request->session()->put('user_id', '2610' );
        
        $categorys = Lib_common::GetCategorys();

        $LeagueData = DB::table('xyzs_league as l')
                   ->leftJoin('xyzs_league_web as w', 'l.user_id', '=', 'w.user_id')
                   ->first();

        $LeagueData = (array)$LeagueData;


        //var_dump( $LeagueData );
        View::share('categorys', $categorys); 
        View::share('LeagueData' , $LeagueData);

        return $next($request);
    }
}
