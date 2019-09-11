<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Cus_lib\Lib_common;

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
    | 訂單寫入資料庫
    |--------------------------------------------------------------------------
    | 
    |
    */

    public function done( Request $request ){
        
        // 檢查購物車有商品 , 如果沒有購物車或者沒有商品就視為違法操作直接導回首頁
        if( !$request->session()->has('cart') || count( $request->session()->get('cart') ) < 1 ){
            
            return redirect('/');
        }  


        // 驗證機制 , 根據配送方式不同需要驗證的欄位也不同
        if( !isset( $request->shipping ) || empty( $request->shipping ) ){

            return redirect()->back()->withErrors(['shipping'=>['尚未選取付費方式']]);
        }

        // 根據不同配送配送方式採取不同檢驗
        if( in_array( $request->shipping , ['17','18','19'] )){
            
            $validationCond = [
                'shipping'    => 'required', 
                'super_name2' => 'required',
                'super_addr2' => 'required',
                'super_type'  => 'required',
                'super_no2'   => 'required',
                'super_consignee' => 'required',
                'super_mobile'    => 'required|regex:/^09[0-9]{8}$/',
                'super_email'     => 'nullable|email',
                'payment'      => 'required', 
                'carruer_type' => 'required',
            ];
            
            $validationMsg = [   'shipping.required' => '配送方式尚未選取',
                'super_name2.required' => '超商尚未選取',
                'super_addr2.required' => '超商地址尚未選取',
                'super_type.required'  => '超商地址尚未選取',
                'super_no2.required'   => '超商地址尚未選取',
                'super_consignee.required' => '收貨人姓名為必填',
                'super_mobile.required' => '手機欄位為必填',
                'super_mobile.regex'=> '手機格式錯誤',
                'super_email.email' => '電子郵件格式錯誤',
                'payment.required'  => '付款方式為必選',
                'carruer_type.required' => '電子發票需選取'
            ];

            // 自然人憑證
            if(  $request->carruer_type == 2 ){

                $validationCond['ei_code'] = 'required|regex:/^[A-Z]{2}[0-9]{14}$/';

                $validationMsg['ei_code.required'] = '自然人憑證需填寫';

                $validationMsg['ei_code.regex'] = '自然人憑證格式錯誤';
            }

            // 手機載具
            if(  $request->carruer_type == 3 ){

                $validationCond['ei_code'] = 'required|regex:/^\/{1}[0-9A-Z\.\-\+]{7}$/';

                $validationMsg['ei_code.required'] = '手機載具需填寫';

                $validationMsg['ei_code.regex'] = '手機載具格式錯誤';                
            }            
            
            if( !empty($request->inv_payee) || !empty($request->inv_content) ){

                $validationCond['inv_payee']   = 'required';

                $validationCond['inv_content'] = 'required';

                $validationMsg['inv_payee.required']   = '如果需開立統編 , 統編為必填';

                $validationMsg['inv_content.required'] = '如果需開立統編 , 公司抬頭為必填';

            }
            $validator = Validator::make($request->all(), $validationCond , $validationMsg );

            if ($validator->fails()) {
                
                //var_dump($validator->errors());
                
            }
        }else{
            $validationCond = [
                'shipping'    => 'required', 
                'consignee'   => 'required',
                'address'     => 'required',
                'mobile'    => 'required|regex:/^09[0-9]{8}$/',
                'email'     => 'nullable|email',
                'payment'      => 'required', 
                'carruer_type' => 'required',
            ];
            
            $validationMsg = [   
                'shipping.required' => '配送方式尚未選取',
                'consignee.required' => '收貨人姓名為必填',
                'address.required' => '收貨人地址為必填',
                'mobile.required' => '手機欄位為必填',
                'mobile.regex'=> '手機格式錯誤',
                'email.email' => '電子郵件格式錯誤',
                'payment.required'  => '付款方式為必選',
                'carruer_type.required' => '電子發票需選取'
            ];
            // 自然人憑證
            if(  $request->carruer_type == 2 ){

                $validationCond['ei_code'] = 'required|regex:/^[A-Z]{2}[0-9]{14}$/';

                $validationMsg['ei_code.required'] = '自然人憑證需填寫';

                $validationMsg['ei_code.regex'] = '自然人憑證格式錯誤';
            }

            // 手機載具
            if(  $request->carruer_type == 3 ){

                $validationCond['ei_code'] = 'required|regex:/^\/{1}[0-9A-Z\.\-\+]{7}$/';

                $validationMsg['ei_code.required'] = '手機載具需填寫';

                $validationMsg['ei_code.regex'] = '手機載具格式錯誤';                
            }            
            
            if( !empty($request->inv_payee) || !empty($request->inv_content) ){

                $validationCond['inv_payee']   = 'required';

                $validationCond['inv_content'] = 'required';

                $validationMsg['inv_payee.required']   = '如果需開立統編 , 統編為必填';

                $validationMsg['inv_content.required'] = '如果需開立統編 , 公司抬頭為必填';

            }
            $validator = Validator::make($request->all(), $validationCond , $validationMsg );       

            if ($validator->fails()) {
                
                //var_dump($validator->errors());
                
            }     
        } 

        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator);
            //return redirect()->back(); 
        } 

        if( $request->inv_payee != '' ||  $request->inv_content != ''  ) $request->carruer_type = '0' ;

        $inv_type = ( $request->inv_payee != '' ||  $request->inv_content != ''  ) ? '三聯式發票' : '一般發票開立' ;
        
        $consignee = [];
        // 初始最後要寫入的訂單資訊 
        $order = array(
        'shipping_id'     => intval( $request->shipping ),
        'pay_id'          => intval( $request->payment ),
        'pack_id'         => 0,//isset($_POST['pack']) ? intval($_POST['pack']) : 0,
        'card_id'         => 0,//isset($_POST['card']) ? intval($_POST['card']) : 0,
        'card_message'    => '',//trim($_POST['card_message']),
        'surplus'         => 0.00,//isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
        'integral'        => 0,//isset($_POST['integral']) ? intval($_POST['integral']) : 0,
        'bonus_id'        => 0,//isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
        'need_inv'        => 0,//empty($_POST['need_inv']) ? 0 : 1,
        'inv_type'        => $inv_type,
        'inv_payee'       => trim($request->inv_payee),
        'inv_content'     => trim($request->inv_content),
        'postscript'      => trim($request->postscript),
        'how_oos'         => '',//isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
        'need_insure'     => 0,//isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
        'user_id'         => 0,//$_SESSION['user_id'],
        'add_time'        => (time() - date('Z')),
        'order_status'    => 0,
        'shipping_status' => 0,
        'pay_status'      => 0,
        'agency_id'       => 0,//get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district'])),
        'carruer_type'    => trim($request->carruer_type),
        'ei_code'         => trim($request->ei_code),
        'from_ad_od_sn'   => '',
        'from_ip'         => $this->real_ip(),
        'country'         => $request->country,
        'province'        => $request->province,
        'city'            => $request->city 
        );

        $order['from_ad'] = '0';
        $order['referer'] = '本站';

        $order['bonus'] = 0;
        $order['goods_amount'] = $total['goods_price'];
        $order['discount'] = 0;
        $order['surplus']  = 0;
        $order['tax']      = 0;
        $order['rent_total'] = 0;
        $order['bonus_id'] = 0;

        $shipcode = DB::table('xyzs_shipping')
                 ->select('shipping_code','shipping_name')
                 ->where('shipping_id','=',$request->shipping)
                 ->where('enabled','=',1)
                 ->first();

        $order['shipping_code'] = $shipcode->shipping_code;

        $super_name      =  trim($request->super_name2);
        $super_addr      =  trim($request->super_addr2);
        $super_no        =  trim($request->super_no2);
        $super_consignee =  trim($request->super_consignee);
        $super_mobile    =  trim($request->super_mobile);
        $super_email     =  trim($request->super_email); 

        if( $order['shipping_code']  == 'super_get' ){

        }elseif( $order['shipping_code']  == 'super_get2' ){

        }elseif( $order['shipping_code']  == 'super_get3' ){

        }

        $showDoneString = False ; 

        if( $order['shipping_code']  == 'super_get' || $order['shipping_code']  == 'super_get2' || $order['shipping_code']  == 'super_get3' ){
            
            $showDoneString = True;
        }

        // 除了超商配送外  , 其他物流
        if( $order['shipping_code']  == 'ecan'  || $order['shipping_code']  == 'postoffice' || $order['shipping_code']  == 'flat'
         || $order['shipping_code']  == 'flat_lan' || $order['shipping_code']  == 'hct' || $order['shipping_code']  == 'hct_shun'
         || $order['shipping_code']  == 'kerry_tj' || $order['shipping_code']  == 'tjoin' || $order['shipping_code']  == 'acac'
         ){ 

            // 整理收貨人資料
            $consignee['address']   = isset($request->address) ? trim($request->address) : '' ;
            $consignee['consignee'] = isset($request->consignee) ? trim($request->consignee) : '' ;
            $consignee['email']     = isset($request->email) ? trim($request->email) : '' ;
            $consignee['zipcode']   = isset($request->zipcode) ? trim($request->zipcode) : '' ;
            $consignee['tel']       = isset($request->tel) ? trim($request->tel) : '' ; 
            $consignee['best_time'] = isset($request->best_time) ? trim($request->best_time) : '' ;
            $consignee['mobile']    = isset($request->mobile) ? trim($request->mobile) : '' ;
            $consignee['sign_building'] = isset($request->sign_building) ? trim($request->sign_building) : '' ;

        }   
        
        $order['shipping_name'] = $shipcode->shipping_name;

        if( $order['shipping_code']  == 'super_get' || $order['shipping_code']  == 'super_get2' || $order['shipping_code']  == 'super_get3' ){
            
            $order['shipping_type'] = addslashes( $request->super_type );
            $order['shipping_super_name'] = addslashes( $request->super_name2 );
            $order['shipping_super_no'] = addslashes( $request->super_no2);
            $order['shipping_super_addr'] = addslashes( $request->super_addr2 );
            $order['address'] = addslashes( $request->super_name2 ).'_'.addslashes( $request->super_addr2 );

        }     
        
        // 收貨人訊息轉換
        foreach ($consignee as $key => $value){
            
            $order[$key] = addslashes($value);
        }

        /*超商寫入*/
        if( $order['shipping_code']  == 'super_get' || $order['shipping_code']  == 'super_get2' || $order['shipping_code']  == 'super_get3' ){

            $order['mobile']    = addslashes( trim( $request->super_mobile ));
            $order['consignee'] = addslashes( trim( $request->super_consignee));
            $order['email']     = addslashes( trim( $request->super_email));
            $order['tel']   = '';

        }           

        $order['scode'] = 9453;
        
        // 運費
        $allFee = $this->available_shipping_list( [$request->country , $request->province ] );
        
        $allFee = json_decode( $allFee , true );
        
        // var_dump($allFee);
        foreach ($allFee as $allFeek => $allFeev) {
            
            if( $allFeev['shipping_id'] == $request->shipping ){

                $shipping_cfg = $this->unserialize_config($allFeev['configure']);

                $tmpfee = $this->shipping_fee( $request->session()->get('cart') ,$shipping_cfg );
                
                break;
            }
        }
        
        $order['shipping_fee'] = ($order['goods_amount'] >= $tmpfee['free'])?0:$tmpfee['fee'];

        $order['insure_fee'] = 0;

        if ($order['pay_id'] > 0){
            
            $payment = DB::table('payment')->where('pay_id','=',$order['pay_id'])->first();
            
            $order['pay_name'] = addslashes( $payment->pay_name );
        }    

        $order['order_amount'] = $order['goods_amount'] + $order['shipping_fee'] + $order['tax'];

        // 保留原始手機及家電
        $mobileForMail   = $order['mobile'];
        $telForMail      = $order['tel'];
    
        // 採用訂單編號執行加密後,再對該筆訂單進行更新動作
        $order['mobile'] = empty($order['mobile']) ? '' : $this->mobileEncode( '' , $order['mobile'] );
        $order['tel']    = empty($order['tel']) ?    '' : $this->telEncode   ( '' , $order['tel']);

        // 開始寫入訂單
        $error_no = 0;
        
        // $this->get_order_sn();
        unset($order['need_inv']);
        unset($order['need_insure']);
        unset($order['shipping_code']);




        $inSwitch = 1;                                   
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




    /*----------------------------------------------------------------
     | 開啟超商選擇
     |----------------------------------------------------------------
     |
     */
    public function storeMap( Request $request ){
        
        // 要執行的動作
        $status = 'get_store_map';
        
        // 如果是綠界回傳 , 則直接存進session即可
        if( isset($request->CVSStoreID) && isset($request->CVSStoreName) ){

            $status = 'store_call_back';
            //LogisticsSubType
            $CVSArr = [];
            
            $CVSArr['CVSStoreID']   = $request->CVSStoreID;
            $CVSArr['CVSStoreName'] = $request->CVSStoreName;
            $CVSArr['CVSAddress']   = $request->CVSAddress;

            $request->session()->put("{$request->LogisticsSubType}", $CVSArr );
        }        

        // 超商代碼
        $type   = $request->type;

        //裝置代碼
        $device = $request->device;

        
        // 如果不是要選取超商 , 則直接轉跳至收貨人訊息葉面即可
        if( $status == 'get_store_map' ){

            return view("storeMap")->with([
                                           'status' => $status,
                                           'type'   => $type,
                                           'device' => $device
                                         ]);

        }else{

            return redirect("/checkout");
        }

    }    




    /*----------------------------------------------------------------
     | 計算費用相關
     |----------------------------------------------------------------
     |
     */
    public function order_fee( $carts ){
        
        $total  = array('real_goods_count' => 0,
                        'gift_amount'      => 0,
                        'goods_price'      => 0,
                        'market_price'     => 0,
                        'discount'         => 0,
                        'pack_fee'         => 0,
                        'card_fee'         => 0,
                        'shipping_fee'     => 0,
                        'shipping_insure'  => 0,
                        'integral_money'   => 0,
                        'bonus'            => 0,
                        'surplus'          => 0,
                        'cod_fee'          => 0,
                        'pay_fee'          => 0,
                        'tax'              => 0
                  );  

        // 計算商品總價
        $tmpTotal = 0;
        
       // var_dump($carts);

        foreach ( $carts as $cartk => $cart ) {
            
            $tmpTotal += $cart['subTotal'] ;
        }
        
        $total['goods_price'] = $tmpTotal;

        return $total;
    }    
}
