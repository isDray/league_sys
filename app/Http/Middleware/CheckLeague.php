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
        /**
         * 根據網域名稱去判斷當前的加盟會員
         *  
         **/
        $nowdomain = trim( \Request::server ("SERVER_NAME") );

        $ldres = DB::table('xyzs_league_domain')->where('domain',$nowdomain)->first();

        if( $ldres != NULL ){

            $current_league = $ldres->user_id;

        }else{
            
            $current_league = '2610';
        }
        
        // 判斷加盟會員是否還處於可以使用之狀態
        if( !empty($current_league) ){
            
            $chk_league_able = DB::table('xyzs_users')->where('user_id',$current_league)->where('user_rank',5)->select('able')->first();
            
            // 如果根本找不到會員 , 或者able !== 1 ( 表示未啟用 ) , 就當作無此加盟商
            if( $chk_league_able === NULL || $chk_league_able->able !== 1){

                $current_league = '';

            }


        }

        $request->session()->put('league_id', $current_league );

        $LeagueId = $request->session()->get('league_id');
        
        /** 
         * 先確認是否真的為啟用的加盟會員 , 如果不是就直接中斷
         *
         **/
        $valid_league = DB::table('xyzs_users')->where('user_id',$LeagueId)->where('user_rank',5)->where('able',1)->first();

        if( $valid_league == NULL ){
         
            return redirect("/register");

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
            
            //echo explode('_', $LeftBlock)[0];

        

            $BlockName = DB::table('xyzs_league_block')->where('id',explode('_', $LeftBlock)[0])->first();

            if( $BlockName != NULL ){
                
                if( !empty(explode('_', $LeftBlock)[1]) )
                {
                    $LeftBlocks[$LeftBlockk] = [ 0=>$BlockName->name , 1 => explode('_', $LeftBlock)[1] ];
                }
                else
                {
                    $LeftBlocks[$LeftBlockk] = $BlockName->name;
                }

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

        // 產生麵包屑
        
        
        View::share('Breadcrum' , Lib_common::_getBreadcrumb() );

        /**
         * 判斷是否驗證過18歲
         **/
        if( !isset( $_COOKIE['over18'] ))
        {   
            if( !empty($LeagueData['ml']) && file_exists( public_path("over18_pic/{$LeagueData['user_id']}/{$LeagueData['ml']}") ) )
            {
                
                View::share('over18_l' , "over18_pic/{$LeagueData['user_id']}/{$LeagueData['ml']}" ); 

            }else{

                View::share('over18_l' , "over18_pic/0/ml.png" ); 
            }

            if( !empty($LeagueData['mr']) && file_exists( public_path("over18_pic/{$LeagueData['user_id']}/{$LeagueData['mr']}") ) )
            {
                View::share('over18_r' , "over18_pic/{$LeagueData['user_id']}/{$LeagueData['mr']}" ); 

            }else{
                
                View::share('over18_r' , "over18_pic/0/mr.png" );
            }            

            View::share('over18' , false );  

        }
        else
        {
            
            View::share('over18' , true );
        }

        return $next($request);
    }

}
