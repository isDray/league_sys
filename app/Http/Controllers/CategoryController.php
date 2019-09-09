<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 商品分類頁面
    |--------------------------------------------------------------------------
    |
    */
    public function category( Request $request , $cat_id , $now_page = 1 , $per_page = 20 ){
        
        // 分類ID
        $CatId = $request->cat_id;
       
        // 起始筆數
        $StartRow = ( $now_page - 1 ) * $per_page;

        // 撈出符合分類之商品
        $CondQuery = DB::table('xyzs_goods AS g')
                   ->leftJoin('xyzs_goods_cat AS c', 'g.goods_id', '=', 'c.goods_id')
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

        return view('web_category',['Goods' => $Goods , 'Pages' => $Pages ]);
    }
}
