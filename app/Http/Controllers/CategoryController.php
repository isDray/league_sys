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

        // 確認是否為母分類
        $IfRoot = DB::table('xyzs_category')->where('cat_id',$CatId)->first();

        $CatArr = [ $CatId ];
        
          
        if( $IfRoot->parent_id == 0 ){

            $AllChildCates = DB::table('xyzs_category')->where('parent_id',$CatId)->get();
            
            foreach ($AllChildCates as $AllChildCatek => $AllChildCate ) {
                
                array_push( $CatArr , $AllChildCate->cat_id );
            }

        }
        // 過濾不想要的字串
        $IfRoot->keywords = str_replace('愛戀99', '', $IfRoot->keywords);
        $IfRoot->keywords = str_replace('享愛網', '', $IfRoot->keywords);
        $IfRoot->keywords = str_replace('享愛', '', $IfRoot->keywords);
        $IfRoot->keywords = str_replace('性易購', '', $IfRoot->keywords);

        $IfRoot->cat_desc = str_replace('愛戀99', '', $IfRoot->cat_desc);
        $IfRoot->cat_desc = str_replace('享愛網', '', $IfRoot->cat_desc);
        $IfRoot->cat_desc = str_replace('享愛', '', $IfRoot->cat_desc);     
        $IfRoot->cat_desc = str_replace('性易購', '', $IfRoot->cat_desc);    

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
                   ->select("g.*",DB::raw( "ROUND(shop_price) as shop_price" ))
                   ->leftJoin('xyzs_goods_cat AS c', 'g.goods_id', '=', 'c.goods_id')
                   ->where('g.is_on_sale','1')
                   ->where('g.goods_number','>',0)
                   ->where(function( $query  )use ($CatArr){
                       $query->whereIn('g.cat_id',$CatArr)
                             ->orWhereIn('c.cat_id',$CatArr);
                   })
                   ->groupBy('g.goods_id');
            
        $CondQuery->orderBy($CatSortItem,$CatSortWay);
        
        $TotalRow = $CondQuery->get();                   
        
        $TotalRow = count( $TotalRow );
        
        // 產生分頁
        $target = "/category/$CatId/$CatSortItem/$CatSortWay/";

        $Pages = Lib_common::create_page(  $target , $TotalRow , $now_page , $per_page , 5 );

        $CondQuery->skip( $StartRow )->take( $per_page );

        $Goods = $CondQuery->get(); 
        
        $Goods = json_decode( $Goods , true );
        
        $yearMonth = date('Y年n月');

        $FastCat = Lib_common::_categoryRoot( $request->cat_id );

        return view('web_category',[ 'Goods' => $Goods , 
        	                         'Pages' => $Pages ,
        	                         'CatSortItem' => $CatSortItem , 
        	                         'CatSortWay'  => $CatSortWay  ,
                                     'AddTimeURL'  => $AddTimeURL  ,
                                     'PriceUrl'    => $PriceUrl,
                                     'title'        => "{$IfRoot->cat_name}-情趣用品",
                                     'keywords'     => "{$IfRoot->keywords}",
                                     'description'  => "{$IfRoot->cat_desc}",
                                     'page_header'  => "{$IfRoot->cat_name}-{$yearMonth}情趣用品精選推薦",        
                                     'FastCat'      => $FastCat

        	                        ]);
    }


}
