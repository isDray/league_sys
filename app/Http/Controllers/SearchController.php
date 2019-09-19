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
    public function search( Request $request ,$keyword='', $cat_sort_item = 'add_time' , $cat_sort_way = 'asc', $now_page = 1 , $per_page = 20 ){
        
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
            
            $AddTimeURL = "/search/$request->keyword/add_time/$NextCatSortWay/$now_page/$per_page";

            $PriceUrl   = "/search/$request->keyword/shop_price/$CatSortWay/$now_page/$per_page";

        }elseif( $CatSortItem == 'shop_price'){

            $AddTimeURL = "/search/$request->keyword/add_time/$CatSortWay/$now_page/$per_page";
            
            $PriceUrl   = "/search/$request->keyword/shop_price/$NextCatSortWay/$now_page/$per_page";

        }      
        // 起始筆數
        $StartRow = ( $now_page - 1 ) * $per_page;

        // 撈出符合分類之商品
        $CondQuery = DB::table('xyzs_goods AS g')
                   ->select("g.*",DB::raw( "ROUND(shop_price) as shop_price" ))
                   ->where('g.is_on_sale','1')
                   /*->where(function( $query  )use ($CatArr){
                       $query->whereIn('g.cat_id',$CatArr)
                             ->orWhereIn('c.cat_id',$CatArr);
                   })*/
                   ->where('g.goods_name','like', '%'.$keyword.'%')
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

        return view('web_category',[ 'Goods' => $Goods , 
                                     'Pages' => $Pages ,
                                     'CatSortItem' => $CatSortItem , 
                                     'CatSortWay'  => $CatSortWay  ,
                                     'AddTimeURL'  => $AddTimeURL  ,
                                     'PriceUrl'    => $PriceUrl
                                    ]);              
    }
}
