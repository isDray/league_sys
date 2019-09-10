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
    | 調整購物車內數量
    |--------------------------------------------------------------------------
    |
    |
    */
    public function change_goods_num( Request $request ){
        
        if( $request->session()->has('cart') ){

            $tmpcart = $request->session()->get('cart');
            
            // 如果已經有購物車了 , 則判斷此商品是否已經存在購物車內
            if( array_key_exists("$request->goods_id", $tmpcart) ) {

                $goodsDetail = DB::table('xyzs_goods')->where('goods_id', $request->goods_id)->first();

                $totalNum = $request->wantNum;

                if( ($goodsDetail->goods_number - $totalNum ) < 0 ){
                
                    return json_encode( ['res' =>false , 'data'=>[['目前此商品庫存只剩'.$goodsDetail->goods_number.'個 , 請調整訂購數量' ]]] );

                }else{

                    $tmpcart[$request->goods_id]['num'] = $totalNum;
                    $tmpcart[$request->goods_id]['goodsPrice'] = round($goodsDetail->shop_price);
                    $tmpcart[$request->goods_id]['subTotal'] = round($goodsDetail->shop_price * $totalNum);   

                    ksort($tmpcart);
                    $request->session()->put('cart', $tmpcart);    

                    return json_encode( ['res' =>true , 'data'=>[['修改數量成功']]] );             
                }                  

            }else{

                return json_encode( ['res' =>false, 'data'=>[['無法進行此操作']]]);
            }            

        }else{

            return json_encode( ['res' =>false, 'data'=>[['無法進行此操作']]]);
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
        
        $GoodsNums = [];

        foreach( $request->session()->get('cart') as $cartk => $cart ){

            $TmpGoods = DB::table('xyzs_goods')->where('goods_id',$cart['id'])->first();

            if( $TmpGoods !== NULL ){
                
                $GoodsNums[ $cart['id'] ] = $TmpGoods->goods_number;

            }else{

                $GoodsNums[ $cart['id'] ] = 0;
            }
        }

        return view("web_cart" , ['GoodsNums'=>$GoodsNums]);
    } 




    /*
    |--------------------------------------------------------------------------
    | 結帳頁面
    |--------------------------------------------------------------------------
    |
    */
    public function checkout( Request $request ){
        // dd( $request->session()->all() );
        // 模擬綠界付款 
        // 取出付款資料
        /*$paytest = DB::table('payment')->where('pay_code','allpay_card')->first();
        
        $tmpPayCfg = $this->unserialize_config( $paytest->pay_config );

        36 * 1599

        if( isset($tmpPayCfg['allpay_card_account']) && !empty($tmpPayCfg['allpay_card_account']) ){

            $tmpPayCfg['allpay_card_iv']  = trim( $this->ecEncryptDecrypt( $tmpPayCfg['allpay_card_account'] , $tmpPayCfg['allpay_card_iv'] , 1));

            $tmpPayCfg['allpay_card_key'] = trim( $this->ecEncryptDecrypt( $tmpPayCfg['allpay_card_account'] , $tmpPayCfg['allpay_card_key'], 1));

        } */
        /*$tmpPayCfg["allpay_card_test_mode"] => "yes"
        $tmpPayCfg["allpay_card_account"] => "2000132"
        $tmpPayCfg["allpay_card_iv"] => "5294y06JbISpM5x9"
        $tmpPayCfg["allpay_card_key"] =>"v77hoKGq4kWxNNIS"          
        
        dd( $tmpPayCfg );     */

        // 如果根本沒有購物車 , 直接返回首頁
        if( $request->session()->has('cart') && count($request->session()->get('cart')) > 0 ){
            
            // 取得國家級縣市
            $countrys = $this->get_regions();
            $countrys = json_decode($countrys,true);
            
            if( $request->session()->has('chsCountry') ){

                $tmpCountry = $request->session()->get('chsCountry');

            }else{

                $request->session()->put('chsCountry', 1);
                $tmpCountry = 1;
            }

            // $request->session()->put('chsCountry', 833);
            // $request->session()->forget('chsCountry');
            if( $tmpCountry == 1){
                
                $provinces = $this->get_regions( 1 , $tmpCountry );
                $provinces = json_decode($provinces,true);
            
            }else{

                $provinces = false;
            }
            
            // 設定預設的州

            if( $request->session()->has('chsProvince') ){
               
                $tmpProvince = $request->session()->get('chsProvince');

            }else{
                
                $tmpProvince = 807;
            }
            
            if( ($tmpProvince == 807 && $tmpCountry ==1) || ($tmpProvince == 808 && $tmpCountry ==1) ){
                
                $citys = $this->get_regions( 2 , $tmpProvince);
                $citys = json_decode($citys,true);
            
            }else{
             
                $citys = false;
            }
            
            // 選取
            if( $tmpCountry != 1 ){
                $tmpProvince = 0;
            }

            $region = array($tmpCountry, $tmpProvince , 0, 0);

            $shipping_list     = $this->available_shipping_list($region);
            

            $shipping_list = json_decode( $shipping_list , true );
            
            // 移除不要用的配送方式
            $no_display = array('flat_lan','ecan_lan','hct','hct_shun','kerry_tj','acac'); 

            foreach ($shipping_list AS $key => $val){   
                
                $no_shipping_display = in_array( $val['shipping_code'] , $no_display) ? '1':'';
                
                if($no_shipping_display == 1 ){
                     
                    unset($shipping_list[$key]);

                }else{

                    // 計算價格
                    $shipping_cfg = $this->unserialize_config($val['configure']);
                    //var_dump($val['shipping_code']);

                    
                    
                    // 根據是否有購物車session , 決定計算運費的資料
                    if( $request->session()->has('cart') ){
                        
                        $calcCart = $request->session()->get('cart');

                    }else{
                        
                        $calcCart = array();
                    }

                    // 計算運費
                    $tmpfee = $this->shipping_fee( $calcCart ,$shipping_cfg );
                    
                    $shipping_list[$key]['shipping_fee'] = $tmpfee['fee'];
                    $shipping_list[$key]['shipping_fee_free'] = $tmpfee['free'];


                }            

            }
            
            // 取得付款資料
            $payment_list = $this->available_payment_list(1,'');

            if( isset($payment_list) ){

                foreach ($payment_list as $key => $payment){
                    /*
                    if ($payment['is_cod'] == '1'){
                        
                        $payment_list[$key]['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment['format_pay_fee'] . '</span>';
                    }
                    */

                    /* 如果有易寶神州行支付 如果訂單金額大於300 則不顯示 */
                    if ($payment['pay_code'] == 'yeepayszx' && $total['amount'] > 300){
                        
                        unset($payment_list[$key]);
                    }
                    
                    /* 如果為其它付款，則不顯示*/
                    if ($payment['pay_code'] == 'other_cod'){
                        
                        unset($payment_list[$key]);
                    } 

                }
            }

            return view('web_checkout')->with([ 'title'     => '填寫資料收貨',
                                            'countrys'  => $countrys,
                                            'provinces' => $provinces,
                                            'citys'     => $citys,
                                            'shipping_list' => $shipping_list,
                                            'payment_list'  => $payment_list
                                          ]);            


        }else{

            // 如果購物車不存在直接跳回首頁
            return redirect('/');

        }
    }




    /*
    |--------------------------------------------------------------------------
    | 取得國家列表
    |--------------------------------------------------------------------------
    |
    */
    public function get_regions($type = 0, $parent = 0){

        // $sql = 'SELECT region_id, region_name FROM ' . $GLOBALS['ecs']->table('region') .
        //     " WHERE region_type = '$type' AND parent_id = '$parent'";
        
        $returnData = DB::table('xyzs_region')
                      ->where('region_type', $type )
                      ->where('parent_id', $parent )
                      ->get();

        return $returnData;
    }




    /*
    |--------------------------------------------------------------------------
    | 取得可用的配送方式
    |--------------------------------------------------------------------------
    |
    */
    public function available_shipping_list($region_id_list){

        $returnData = DB::table('xyzs_shipping')
                        ->leftJoin('xyzs_shipping_area', 'xyzs_shipping_area.shipping_id', '=', 'xyzs_shipping.shipping_id')
                        ->leftJoin('xyzs_area_region', 'xyzs_area_region.shipping_area_id', '=', 'xyzs_shipping_area.shipping_area_id')

                        ->select(['xyzs_shipping.shipping_id', 'xyzs_shipping.shipping_code', 'xyzs_shipping.shipping_name' , 'xyzs_shipping.shipping_desc' , 'xyzs_shipping.insure', 'xyzs_shipping.support_cod', 'xyzs_shipping_area.configure'])
                        /*->table('shipping_area as a')
                        ->table('area_region as r')*/
                        ->whereIn('xyzs_area_region.region_id', $region_id_list )
                        ->where('xyzs_shipping.enabled',1)
                        ->orderBy('xyzs_shipping.shipping_order', 'asc')
                        ->get();

        return $returnData;
    }




    /*
    |--------------------------------------------------------------------------
    | 拆解設定
    |--------------------------------------------------------------------------
    |
    |
    */   
    public function unserialize_config($cfg){

        if (is_string($cfg) && ($arr = unserialize($cfg)) !== false){
            
            $config = array();

            foreach ($arr AS $key => $val){

                $config[$val['name']] = $val['value'];
            }

            return $config;
        
        }else{
            
            return false;
        }
    }  




    /*
    |--------------------------------------------------------------------------
    | 計算運費
    |--------------------------------------------------------------------------
    | 此運費計算模式為享愛網計費方式的精簡版本 , 由於在服飾網沒有會
    | 員等級 , 單純以價格計算 , 所以採用相對簡單的方式
    |
    */
    public function shipping_fee( $calcCarts , $cfg ){
        
        // 預設目前訂單為0元
        $total = 0;
        
        // 計算總價
        foreach ($calcCarts as $calcCartk => $calcCart) {
            
            $total += $calcCart['subTotal'];

        }
        
        if( $total >= $cfg['free_money'] ){

            return ['fee'=>0 , 'free'=>$cfg['free_money'] ];
        
        }else{

            return ['fee'=>$cfg['base_fee'] , 'free'=>$cfg['free_money'] ];
        }

    }  



    /*
    |--------------------------------------------------------------------------
    | 可用付款方式
    |--------------------------------------------------------------------------
    |
    */
    public function available_payment_list($support_cod, $cod_fee = 0, $is_online = false){
        
        $sqlData = DB::table('xyzs_payment')
                        ->select('pay_id', 'pay_code', 'pay_name', 'pay_fee', 'pay_desc', 'pay_config', 'is_cod')
                        ->where('enabled','=' , 1)
                        ->orderBy('pay_order', 'asc');
        

        if (!$support_cod){   

            // $sql .= 'AND is_cod = 0 '; // 如果不支持货到付款

            $sqlData->where('is_cod','=',0);
        }
        
        if ($is_online){

            // $sql .= "AND is_online = '1' ";
            $sqlData->where('is_online','=',1);
        }

        $returnDatas = $sqlData->get();
        
        $returnDatas = json_decode( $returnDatas , true );

        return $returnDatas;
    }           




    /*----------------------------------------------------------------
     | 修改選取的地區
     |----------------------------------------------------------------
     |
     |
     */
    public function areaChange( Request $request ){
        
        if( $request->type == 1){

            $request->session()->put('chsCountry', $request->area);

            return json_encode( [true,''] );

        }

        if( $request->type == 2){

            $request->session()->put('chsProvince', $request->area);

            return json_encode( [true,''] );            

        }

        if( $request->type == 3){

            $request->session()->put('chsCity', $request->area);

            return json_encode( [true,''] );            

        }        
    }



    /*----------------------------------------------------------------
     | 修改選取的配送方式
     |----------------------------------------------------------------
     |
     */
    public function shipChange( Request $request ){
        

        $request->session()->put('chsShip', $request->ship);

        return json_encode( [true,''] );

        
    }    
}
