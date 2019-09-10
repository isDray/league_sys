<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ajax 加入購物車
    |--------------------------------------------------------------------------
    |
    */
    public function add_to_cart( Request $request ){
        
        $Goods = DB::table('xyzs_goods')->where('goods_id',$request->goods_id)->first();

        if( $Goods === NULL){
            
            $MaxNum = 0;

        }else{
            
            $MaxNum = $Goods->goods_number;
        }

        // 檢驗
        $validator = Validator::make($request->all(), 
        [
            'goods_id' => 'required|exists:xyzs_goods,goods_id|bail',
            'number'      => "required|integer|max:$MaxNum|bail",


        ],
        [   'goods_id.required'=> '缺少商品編號',
            'goods_id.exists'  => '此商品不存在',
            'number.required'     => '缺少商品數量',
            'number.max'          => '商品數量不足',
            'number.integer'      => '商品數量必須為整數'
        ]);
        

        if( $validator->fails() ){

            return json_encode( ['res'=>false , 'data'=>$validator->errors()] );

        }
        
        // 存入購物車
        $goodsDetail = DB::table('xyzs_goods')->where('goods_id', $request->goods_id)->first();

        // 如果已經有購物車session , 做增減即可
        if( $request->session()->has('cart') ){

            $tmpcart = $request->session()->get('cart');
            
            // 如果已經有購物車了 , 則判斷此商品是否已經存在購物車內
            if( array_key_exists("$request->goods_id", $tmpcart) ) {

                $totalNum = $tmpcart[$request->goods_id]['num'] + $request->number;

                if( ($goodsDetail->goods_number - $totalNum ) < 0 ){
                
                    return json_encode( ['res'=>false , 'data'=>[['目前此商品庫存只剩'.$goodsDetail->goods_number.'個 , 請調整訂購數量']] ] );
                    exit;

                }  
                              
                

                $tmpcart[$request->goods_id]['num'] = $totalNum;
                $tmpcart[$request->goods_id]['goodsPrice'] = round($goodsDetail->shop_price);
                $tmpcart[$request->goods_id]['subTotal'] = round($goodsDetail->shop_price * $totalNum);

            }else{

                $tmpcart[ $goodsDetail->goods_id ] = [ 'name'      => $goodsDetail->goods_name,
                                                       'thumbnail' => $goodsDetail->goods_thumb,
                                                       'num'       => $request->number,
                                                       'goodsSn'   => $goodsDetail->goods_sn,
                                                       'goodsPrice'=> round($goodsDetail->shop_price),
                                                       'subTotal'  => round($request->number * $goodsDetail->shop_price),
                                                       'id'        => $goodsDetail->goods_id,
                                                     ];  
            }
        }else{
        // 如果沒有購物車session , 則立刻增加一組session
            
            $tmpcart[ $goodsDetail->goods_id ] = [ 'name'      => $goodsDetail->goods_name,
                                                   'thumbnail' => $goodsDetail->goods_thumb,
                                                   'num'       => $request->number,
                                                   'goodsSn'   => $goodsDetail->goods_sn,
                                                   'goodsPrice'=> round($goodsDetail->shop_price),
                                                   'subTotal'  => round($request->number * $goodsDetail->shop_price),
                                                   'id'        => $goodsDetail->goods_id,
                                                 ];            
        }        
        
        ksort($tmpcart);

        $request->session()->put('cart', $tmpcart);

        return json_encode( ['res' => true, 'data' => $tmpcart ] );

    }




    /*
    |--------------------------------------------------------------------------
    | ajax移除商品
    |--------------------------------------------------------------------------
    |
    */
    public function rm_from_cart( Request $request ){
        
        if( $request->session()->has('cart') ){
            
            $tmpcart = $request->session()->get('cart');
            
            // 如果要刪除的商品有在購物車內 , 就直接執行刪除動作
            if( array_key_exists( $request->goods_id , $tmpcart ) ){
                
                unset( $tmpcart[ $request->goods_id ] );
                ksort($tmpcart);

            }

            $request->session()->put('cart', $tmpcart);
            
            return json_encode( ['res'=>true , 'data' => $tmpcart ] );
        }  

    }




    /*
    |--------------------------------------------------------------------------
    | 購物車頁面
    |--------------------------------------------------------------------------
    |
    */
    public function cart( Request $request ){
        
        if( !$request->session()->has('cart') || count($request->session()->get('cart')) < 1 ){

            return redirect('/');

        }
        
        return view("web_cart");
    } 
}
