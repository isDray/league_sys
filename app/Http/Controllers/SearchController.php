<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Cookie\CookieJar;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 搜尋功能
    |--------------------------------------------------------------------------
    |
    |
    */
    public function search( Request $request ,$keyword = " " , $cat_sort_item = 'add_time' , $cat_sort_way = 'asc', $now_page = 1 , $per_page = 20 ){
    
        $keyword = !empty($request->keyword)? $request->keyword:$keyword;

        $CatSortItem = !empty( $request->cat_sort_item )? $request->cat_sort_item : $cat_sort_item;
        
        $CatSortWay  = !empty( $request->cat_sort_way )? $request->cat_sort_way : $cat_sort_way;

        $SortItemArr = ['add_time','shop_price'];
        
        $SortWayArr  = ['asc' , 'desc'];
 
        if( !in_array($CatSortItem, $SortItemArr) ){
            
            $CatSortItem = 'add_time';
        }

        if( !in_array($CatSortWay, $SortWayArr) ){
            
            $CatSortWay = 'asc';
        }  
         
        // 計算按紐路徑
        if( $CatSortWay == 'asc' ){

            $NextCatSortWay = 'desc';

        }else{

            $NextCatSortWay = 'asc';
        }  
        
        if( $CatSortItem == 'add_time'){
            
            $AddTimeURL = "/search/$keyword/add_time/$NextCatSortWay/$now_page/$per_page";

            $PriceUrl   = "/search/$keyword/shop_price/$CatSortWay/$now_page/$per_page";

        }elseif( $CatSortItem == 'shop_price'){

            $AddTimeURL = "/search/$keyword/add_time/$CatSortWay/$now_page/$per_page";
            
            $PriceUrl   = "/search/$keyword/shop_price/$NextCatSortWay/$now_page/$per_page";

        }      
        // 起始筆數
        $StartRow = ( $now_page - 1 ) * $per_page;

        // 撈出符合分類之商品
        $CondQuery = DB::table('xyzs_goods AS g')
                   ->leftJoin("xyzs_league_googs_hash as lgh","g.goods_id","=","lgh.goods_id")
                   ->leftJoin("xyzs_league_hash as lh","lgh.hash_id","=","lh.id")
                   ->select("g.*",DB::raw( "ROUND(shop_price) as shop_price" ))
                   ->where('g.is_on_sale','1')
                   ->where('g.goods_number','>',0)
                   /*->where(function( $query  )use ($CatArr){
                       $query->whereIn('g.cat_id',$CatArr)
                             ->orWhereIn('c.cat_id',$CatArr);
                   })*/
                   ->where('g.goods_name','like', '%'.$keyword.'%')
                   ->orWhere('g.goods_sn','like', '%'.$keyword.'%')
                   ->orWhere('lh.hashtag','like', '%'.$keyword.'%')
                   ->groupBy('g.goods_id');

        $CondQuery->orderBy($CatSortItem,$CatSortWay);
        
        $TotalRow = $CondQuery->get();                   
        
        $TotalRow = count( $TotalRow );
   
        // 產生分頁
        $target = "/search/$keyword/$CatSortItem/$CatSortWay/";

        $Pages = Lib_common::create_page(  $target , $TotalRow , $now_page , $per_page , 5 );

        $CondQuery->skip( $StartRow )->take( $per_page );

        $Goods = $CondQuery->get(); 

        $Goods = json_decode( $Goods , true );
        
        $yearMonth = date('Y年n月');

        return view('web_category',[ 'Goods' => $Goods , 
                                     'Pages' => $Pages ,
                                     'CatSortItem' => $CatSortItem , 
                                     'CatSortWay'  => $CatSortWay  ,
                                     'AddTimeURL'  => $AddTimeURL  ,
                                     'PriceUrl'    => $PriceUrl,
                                     'title'        => "{$keyword}-搜尋{$keyword}-{$keyword}比較-情趣用品搜尋,{$yearMonth}",
                                     'keywords'     => "{$keyword},情趣用品搜尋,搜尋{$keyword},{$keyword}比較",
                                     'description'  => "情趣用品搜尋{$keyword}提供您最豐富的情趣用品種類及品牌,{$yearMonth}搜尋有關{$keyword}的資料共有{$TotalRow}筆,繼續使用搜尋探索更多關於{$keyword}的情趣用品",
                                     'page_header'  => "{$yearMonth}{$keyword}在情趣用品搜尋中所有商品",   

                                    ]);              
    }

    /*
    |--------------------------------------------------------------------------
    | 最新商品
    |--------------------------------------------------------------------------
    |
    */
    public function newest( Request $request , $now_page = 1 , $per_page = 20){
        
        $CatSortItem = 'add_time';
        $CatSortWay  = 'desc';

        $StartRow = ( $now_page - 1 ) * $per_page;

        $CondQuery = DB::table('xyzs_goods AS g')
                   ->select("g.*",DB::raw( "ROUND(shop_price) as shop_price" ))
                   ->where('g.is_on_sale','1')
                   ->where('g.goods_number','>',0)
                   ->groupBy('g.goods_id');

        $CondQuery->orderBy($CatSortItem,$CatSortWay);
        
        $TotalRow = $CondQuery->get();                   
        
        $TotalRow = count( $TotalRow );    

        // 產生分頁
        $target = "/new_arrival/";

        $Pages = Lib_common::create_page(  $target , $TotalRow , $now_page , $per_page , 5 );

        $CondQuery->skip( $StartRow )->take( $per_page );

        $Goods = $CondQuery->get(); 

        $Goods = json_decode( $Goods , true );
        
        $yearMonth = date('Y年n月');

        return view('web_category',[ 'Goods' => $Goods , 
                                     'Pages' => $Pages ,
                                     'CatSortItem' => $CatSortItem , 
                                     'CatSortWay'  => $CatSortWay  ,
                                     'title'        => "最新情趣用品推薦-{$yearMonth}",
                                     'keywords'     => "{$yearMonth}最新情趣用品,最新上市,新發售情趣用品,第一手情趣用品資訊",
                                     'description'  => "最新最刺激的情趣用品清單,讓您直接掌握所有最新上市的情趣用品",
                                     'page_header'  => "最新情趣商品推薦-{$yearMonth}",   
                                     'new_arrival'  => true,
                                    ]);             
    }
}
