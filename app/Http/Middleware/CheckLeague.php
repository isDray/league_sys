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
        $request->session()->put('user_id', '2616' );

        $LeagueId = $request->session()->get('user_id');
        
        /** 
         * 先確認是否真的為啟用的加盟會員 , 如果不是就直接中斷
         *
         **/
        $valid_league = DB::table('xyzs_users')->where('user_id',$LeagueId)->where('user_rank',5)->where('able',1)->first();

        if( $valid_league == NULL ){
         
            return redirect("/no_league");

        }

        $categorys = Lib_common::GetCategorys();

        $LeagueData = DB::table('xyzs_league as l')
                   ->leftJoin('xyzs_league_web as w', 'l.user_id', '=', 'w.user_id')
                   ->where('l.user_id' , $LeagueId)
                   ->first();
        

        $LeagueData = (array)$LeagueData;
        


        //var_dump( $LeagueData );
        View::share('categorys', $categorys); 

        View::share('LeagueData' , $LeagueData);
        
        // 左側區塊
        $LeftBlock = DB::table('xyzs_league_block_sort')->where('user_id', $LeagueId)->where('block_id',2)->first();
           
        $LeftBlock = (array)$LeftBlock;
        
        if( array_key_exists('sort', $LeftBlock) ){

            $LeftBlocks = unserialize( $LeftBlock['sort'] );
        
        }else{

            $LeftBlocks = $LeftBlock;
        }
        
        foreach ($LeftBlocks as $LeftBlockk => $LeftBlock) {
            
            $BlockName = DB::table('xyzs_league_block')->where('id',$LeftBlock)->first();

            if( $BlockName != NULL ){

                $LeftBlocks[$LeftBlockk] = $BlockName->name;

            }
        }   

        View::share('LeftBlocks' , $LeftBlocks);     
        
        $num_in_cart = 0;

        if( $request->session()->has('cart') ){
            
            foreach ($request->session()->get('cart') as $session_cart ) {
                $num_in_cart += $session_cart['num'];
            }
            View::share('Carts' , $request->session()->get('cart') );  

        }
        
        View::share('num_in_cart' , $num_in_cart );  

        return $next($request);
    }

}
