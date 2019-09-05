<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GoodsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 商品內頁
    |--------------------------------------------------------------------------
    |
    */
    public function show_goods( Request $request){
        
        // 從資料庫取出商品資訊
        $GoodsData = DB::table('xyzs_goods')->where('goods_id',$request->goods_id)->first();

        if( $GoodsData === NULL ){
            
            // 取消

        }

        $GoodsData = (array)$GoodsData;

        $goodsImg = DB::table('xyzs_goods_gallery')
                     ->where('goods_id', $request->goods_id )
                     ->get();
   
        $goodsImgs = json_decode($goodsImg,True);

        return view('web_show_goods',[ 'GoodsData' => $GoodsData ,'goodsImgs' => $goodsImgs ]);
    }
}
