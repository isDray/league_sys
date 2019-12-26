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

        // 針對如果有1分鐘影片作呈現
        $IsIOS = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");

        if( $IsIOS ){

            $GoodsData['isios'] = true;

        }
        else
        {

            $GoodsData['isios'] = false;

        }

        if( !empty( $GoodsData['video_name'] ) ){
        
            if( file_exists("video/". explode('.', $GoodsData['goods_sn'])[1].".mp4") )
            {
 
                $GoodsData['o_video_exist'] = true;
        
            }
            else
            {
            
                $GoodsData['o_video_exist'] = false;
            }

            $GoodsData['o_video_path'] = explode('.', $GoodsData['goods_sn'])[1].".mp4";

            $GoodsData['o_video_path_zip'] = explode('.', $GoodsData['goods_sn'])[1].".zip";        

        }
        else
        {

            $GoodsData['o_video_exist'] = false;
        }
    
        if( file_exists( "video/".explode('.', $GoodsData['goods_sn'])[1]."-2.mp4" ) && $_SESSION['user_rank'] == 3 )
        {
        
            $GoodsData['one_minute_video'] = true;

            $GoodsData['one_minute_video_path'] = explode('.', $GoodsData['goods_sn'])[1]."-2.mp4";

            $GoodsData['one_minute_video_path_zip'] = explode('.', $GoodsData['goods_sn'])[1]."-2.zip";

        }
        else
        {
        
            $GoodsData['one_minute_video'] = false;
        
            $GoodsData['one_minute_video_path'] = '';

            $GoodsData['one_minute_video_path_zip'] ='';
        }         

        return view('web_show_goods',[ 'GoodsData' => $GoodsData ,
                                       'goodsImgs' => $goodsImgs ,
                                       'title'     => $GoodsData['goods_name']
                                     ]);
    }
}
