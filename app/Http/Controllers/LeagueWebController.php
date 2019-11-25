<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cus_lib\Lib_bonus;
use DB;


class LeagueWebController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 網站首頁
    |--------------------------------------------------------------------------
    |
    */
    public function index( Request $request ){
        
        $urlarr = explode('/',url()->previous());
        

        if( trim(end( $urlarr )) == 'over18' ){
            
            setcookie('over18',true,time()+86400);
             
        }else{
            
            if( !isset( $_COOKIE['over18'] )){

                return redirect('/over18');
            }            
        }
    



        $LeagueId = $request->session()->get('league_id');
        
        // 中央區塊
        $CenterBlock = DB::table('xyzs_league_block_sort')->where('user_id', $LeagueId)->where('block_id',1)->first();
        
        $CenterBlock = (array)$CenterBlock;
        
        if( array_key_exists('sort', $CenterBlock) ){
            
            $CenterBlocks = unserialize( $CenterBlock['sort'] );

        }else{

            $CenterBlocks = $CenterBlock;
        }
        
        foreach ($CenterBlocks as $CenterBlockk => $CenterBlock) {
            
            $BlockName = DB::table('xyzs_league_block')->where('id',$CenterBlock)->first();

            if( $BlockName != NULL ){

                $CenterBlocks[$CenterBlockk] = $BlockName->name;

            }
        }  
        //$request->session()->forget('cart');
        
        return view('web_index', [ 'CenterBlocks' => $CenterBlocks ,
                                   'title'        => '情趣用品-情趣睡衣-情趣商品-成人情趣道具-線上購買',
                                   'keywords'     => '情趣用品,情趣睡衣,情趣睡衣',
                                   'description'  => '樣式最多的情趣用品線上購買的平台 , 精選跳蛋、變頻按摩棒、情趣睡衣、自慰飛機杯、情趣娃娃、潤滑液等多款情趣商品,想要找提升情趣的用品',
                                   'page_header'  => "情趣用品首頁-推薦商品-熱銷商品-新品上市-各式情趣用品分類,",
                                 ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 網站文章
    |--------------------------------------------------------------------------
    |
    */
    public function article( Request $request ){
        
        $article = DB::table('xyzs_article')->where('article_id',$request->article_id)->first();

        if( $article ){
            $article_title = $article->title;
            $article = $article->content;

        }else{

            return redirect("/");
        }

        return view( 'web_atricle' , ['article'=>$article , 'article_title'=>$article_title]);
    }
    
    


    /*
    |--------------------------------------------------------------------------
    | 網站過橋頁
    |--------------------------------------------------------------------------
    |
    */
    public function over18( Request $request ){
        
        $LeagueId = $request->session()->get('league_id');

        return view('over18');
    }
    



    /*
    |--------------------------------------------------------------------------
    | 檢測頁面
    |--------------------------------------------------------------------------
    |
    */
    public function test( Request $request ){
        
        /**
         * 開始使用優惠券
         **/
        /*
        $ori = 0;

        $rt  = 1.10;

        $switch = true;
        
        $totaly = 0;

        $targ   = 5000;

        while ( $switch ) {
            
            $ori += 20;
            
            $ori =  $rt * $ori;
            
            $totaly += 1;

            if( $ori >= $targ ){
            
                $switch = false;
            }
        }
        
        echo $totaly;
        */
        

    

        // 可折扣的金額
        $bonus_price = Lib_bonus::_useBonus( '1000018995' , 6 );
         
        
        
    }

}
