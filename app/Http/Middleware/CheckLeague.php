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
        
        $LeagueId = $request->session()->get('user_id');
        // 左側區塊
        $LeftBlock = DB::table('xyzs_league_block_sort')->where('user_id', $LeagueId)->where('block_id',2)->first();
        
        $LeftBlock = (array)$LeftBlock;

        $LeftBlocks = unserialize( $LeftBlock['sort'] );
        
        foreach ($LeftBlocks as $LeftBlockk => $LeftBlock) {
            
            $BlockName = DB::table('xyzs_league_block')->where('id',$LeftBlock)->first();

            if( $BlockName != NULL ){

                $LeftBlocks[$LeftBlockk] = $BlockName->name;

            }
        }   

        View::share('LeftBlocks' , $LeftBlocks);     

        return $next($request);
    }
}
