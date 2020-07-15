<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
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
        
        $sub_member = $request->get('sub_member');

        // 先檢查有無登入會員
        if( !empty($sub_member) )
        {
            $viewed_goods = DB::table('xyzs_league_viewd_goods')->where('member_id',$sub_member)->first();
            
            // 如果本身已經有   
            if( $viewed_goods )
            {
                $tmp_viewed_goods = unserialize( $viewed_goods->viewed_goods ); 

                if( !in_array( $request->goods_id , $tmp_viewed_goods ) )
                {
                    array_unshift( $tmp_viewed_goods, $request->goods_id );
                }
                
                if( $tmp_viewed_goods > 10 )
                {
                    $tmp_viewed_goods = array_slice( $tmp_viewed_goods , 0 , 10 );
                }

                $tmp_viewed_goods = serialize( $tmp_viewed_goods );

                DB::table('xyzs_league_viewd_goods')
                    ->where('id', $viewed_goods->id)
                    ->update(['viewed_goods' => $tmp_viewed_goods ]);
            }
            else 
            {    
                $tmp_viewed_goods = serialize([$request->goods_id]);
                
                DB::table('xyzs_league_viewd_goods')->insert(
                   ['member_id' => $sub_member , 'viewed_goods' => $tmp_viewed_goods]
                );
            } 
        }
        else
        {
            //Cookie::queue(Cookie::make('name', 'value', $minutes));
            // 檢查cookie是否存在
            if( Cookie::get('viewed_goods') !== null )
            {
                $tmp_viewed_goods = Cookie::get('viewed_goods');
                 
                if( !in_array( $request->goods_id , $tmp_viewed_goods ) )
                {
                    array_unshift( $tmp_viewed_goods, $request->goods_id );
                }
                
                if( $tmp_viewed_goods > 10 )
                {
                    $tmp_viewed_goods = array_slice( $tmp_viewed_goods , 0 , 10 );
                }
                                
                Cookie::queue('viewed_goods',  $tmp_viewed_goods , 86400);
            }
            else
            {
                Cookie::queue('viewed_goods',  [$request->goods_id] , 86400);
            }
        }

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

