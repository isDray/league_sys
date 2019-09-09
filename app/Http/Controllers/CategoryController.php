<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Cookie\CookieJar;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 商品分類頁面
    |--------------------------------------------------------------------------
    |
    */
    public function category( Request $request, $cat_id , $cat_sort_item = 'add_time' , $cat_sort_way = 'asc', $now_page = 1 , $per_page = 20 ){

        // 分類ID
        $CatId = $request->cat_id;

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
            
            $AddTimeURL = "/category/$cat_id/add_time/$NextCatSortWay/$now_page/$per_page";

            $PriceUrl   = "/category/$cat_id/shop_price/$CatSortWay/$now_page/$per_page";

        }elseif( $CatSortItem == 'shop_price'){

            $AddTimeURL = "/category/$cat_id/add_time/$CatSortWay/$now_page/$per_page";
            
            $PriceUrl   = "/category/$cat_id/shop_price/$NextCatSortWay/$now_page/$per_page";

        }      
        // 起始筆數
        $StartRow = ( $now_page - 1 ) * $per_page;

        // 撈出符合分類之商品
        $CondQuery = DB::table('xyzs_goods AS g')
                   ->leftJoin('xyzs_goods_cat AS c', 'g.goods_id', '=', 'c.goods_id')
                   ->where('g.is_on_sale','1')
                   ->where(function( $query  )use ($CatId){
                       $query->where('g.cat_id',$CatId)
                             ->orWhere('c.cat_id',$CatId);
                   });
            
        $CondQuery->orderBy($CatSortItem,$CatSortWay);
        
        $TotalRow = $CondQuery->get();                   
        
        $TotalRow = count( $TotalRow );
        
        // 產生分頁
        $target = "/category/$CatId/$CatSortItem/$CatSortWay/";

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




    /*
    |--------------------------------------------------------------------------
    | 商品分類排序結果
    |--------------------------------------------------------------------------
    |
    */
    public function category_sort( Request $request, $cat_id , $now_page = 1 , $per_page = 20 ){
        \Cookie::queue('RRR', 'AAA', 10);
        \Cookie::queue(\Cookie::forget('RRR'));
        // 分類ID
        $CatId = $request->cat_id;
       
        // 起始筆數
        $StartRow = ( $now_page - 1 ) * $per_page;

        // 撈出符合分類之商品
        $CondQuery = DB::table('xyzs_goods AS g')
                   ->leftJoin('xyzs_goods_cat AS c', 'g.goods_id', '=', 'c.goods_id')
                   ->where('g.is_on_sale','1')
                   ->where(function( $query  )use ($CatId){
                       $query->where('g.cat_id',$CatId)
                             ->orWhere('c.cat_id',$CatId);
                   });
        $TotalRow = $CondQuery->get();                   
        
        $TotalRow = count( $TotalRow );
        
        // 產生分頁
        $target = "/category/$CatId/";

        $Pages = Lib_common::create_page(  $target , $TotalRow , $now_page , $per_page , 5 );

        $CondQuery->skip( $StartRow )->take( $per_page );

        $Goods = $CondQuery->get(); 
        
        $Goods = json_decode( $Goods , true );

        return view('web_category',['Goods' => $Goods , 'Pages' => $Pages ]);
    }
}
