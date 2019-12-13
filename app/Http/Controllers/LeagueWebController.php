<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cus_lib\Lib_bonus;
use DB;
use App\Cus_lib\hctTool;

class LeagueWebController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 網站首頁
    |--------------------------------------------------------------------------
    |
    */
    public function index( Request $request ){
        /*
        $indexUrl = explode('/', \Request::fullUrl());
        
        if( end( $indexUrl) != 'index' ){
            
            $urlarr = explode('/',url()->previous());
        
        
            if( trim(end( $urlarr )) == 'over18' ){
            
                setcookie('over18',true,time()+86400);
             
            }else{
        
            
                if( !isset( $_COOKIE['over18'] )){

                    return redirect('/over18');

                }            
            }

        }else{

            setcookie('over18',true,time()+86400);
        }
        */


        $LeagueId = $request->session()->get('league_id');
        
        // 中央區塊
        $CenterBlock = DB::table('xyzs_league_block_sort')->where('user_id', $LeagueId)->where('block_id',1)->first();
        
        $CenterBlock = (array)$CenterBlock;
        
        if( array_key_exists('sort', $CenterBlock) ){
            
            $CenterBlocks = unserialize( $CenterBlock['sort'] );

        }else{

            $CenterBlocks = $CenterBlock;
        }
        
        foreach ($CenterBlocks as $CenterBlockk => $CenterBlock) {
            
            $BlockName = DB::table('xyzs_league_block')->where('id',$CenterBlock)->first();

            if( $BlockName != NULL ){

                $CenterBlocks[$CenterBlockk] = $BlockName->name;

            }
        }
        
        $condList = [];

        if( in_array('video_recommend', $CenterBlocks ) ){
            
            $condList['owl'] = true;    
        }

        //$request->session()->forget('cart');
        
        return view('web_index', [ 'CenterBlocks' => $CenterBlocks ,
                                   'title'        => '情趣用品-飛機杯-跳蛋-按摩棒-情趣小物-線上購買',
                                   'keywords'     => '情趣用品,電動飛機杯,仿真飛機杯,無線跳蛋,有線跳蛋,旋轉按摩棒,震動按摩棒,多段變頻按摩棒,情趣小怪獸,情趣小章魚,舌舔跳蛋',
                                   'description'  => '樣式最多的情趣用品線上購買的平台 , 精選跳蛋、變頻按摩棒、情趣睡衣、自慰飛機杯、情趣娃娃、潤滑液等多款情趣商品,想要找提升情趣的用品',
                                   'page_header'  => "情趣用品首頁-推薦商品-熱銷商品-新品上市-各式情趣用品分類,",
                                 ]+$condList);
    }




    /*
    |--------------------------------------------------------------------------
    | 網站文章
    |--------------------------------------------------------------------------
    |
    */
    public function article( Request $request ){
        
        $article = DB::table('xyzs_article')->where('article_id',$request->article_id)->first();

        if( $article ){
            $article_title = $article->title;
            $article = $article->content;

        }else{

            return redirect("/");
        }

        return view( 'web_atricle' , ['article'=>$article , 'article_title'=>$article_title]);
    }
    
    


    /*
    |--------------------------------------------------------------------------
    | 網站過橋頁
    |--------------------------------------------------------------------------
    |
    */
    public function over18( Request $request ){
        
        $LeagueId = $request->session()->get('league_id');

        return view('over18',[  'title'        => '情趣用品-情趣睡衣-情趣商品-成人情趣道具-線上購買',
                                'keywords'     => '情趣用品,情趣睡衣,情趣睡衣,已滿18歲,18禁',
                                'description'  => '確認18歲開始探索各式情趣用品 , 精選跳蛋、變頻按摩棒、情趣睡衣、自慰飛機杯、情趣娃娃、潤滑液等多款情趣商品, 等您來挖掘不一樣的成人情趣',
                                'page_header'  => "確認您已滿18歲後,為您推薦各式情趣用品,讓您在眾多商品中找到最適合您的情趣小物",
                            ]);
    }
    



    /*
    |--------------------------------------------------------------------------
    | 檢測頁面
    |--------------------------------------------------------------------------
    |
    */
    public function test( Request $request ){
        
        /**
         * 開始使用優惠券
         **/
        /*
        $ori = 0;

        $rt  = 1.10;

        $switch = true;
        
        $totaly = 0;

        $targ   = 5000;

        while ( $switch ) {
            
            $ori += 20;
            
            $ori =  $rt * $ori;
            
            $totaly += 1;

            if( $ori >= $targ ){
            
                $switch = false;
            }
        }
        
        echo $totaly;
        */
        

    

        // 可折扣的金額
        $bonus_price = Lib_bonus::_useBonus( '1000018995' , 6 );
         
        
        
    }
    



    /*
    |--------------------------------------------------------------------------
    | 訂單查詢介面
    |--------------------------------------------------------------------------
    |
    */
    public function check_order( Request $request ){

        //$LeagueId = $request->session()->get('league_id');

        return view('league_check_order',[ 'title'        => '訂單查詢',
                                    'keywords'     => '訂單查詢,付費狀態查詢,物流狀態查詢,到貨查詢',
                                    'description'  => '查詢指定訂單編號之訂單狀態,追蹤情趣用品訂單是是否依照確認訂單,理貨,配送,通知取貨等流程執行',
                                    'page_header'  => "訂單查詢",
        ]);        

    }




    /*
    |--------------------------------------------------------------------------
    | 訂單查詢實作
    |--------------------------------------------------------------------------
    |
    */
    public function check_order_act( Request $request ){
        
        /**
         * 避免快速查詢
         **/
        if ( $request->session()->has('last_order_query') ) 
        {

            if( time() - $request->session()->get('last_order_query') <= 10)
            {
                
                $html = view('league_check_order_res', ['type'=>0 , 'msg'=>'查詢頻率過高 , 請稍後再查詢'])->render();

                return json_encode( $html );
            }
        }

        $request->session()->put('last_order_query', time() );
        
        /**
         * 驗證查詢的號碼
         *
         **/
        if( empty( $_POST['order_sn'] ) ){

            $html = view('league_check_order_res', ['type'=>0 , 'msg'=>'請填寫要查詢的訂單編號'])->render();

            return json_encode( $html );            
        }

        if( !preg_match("/^\d{13}$/", $_POST['order_sn'])) {

            $html = view('league_check_order_res', ['type'=>0 , 'msg'=>'訂單編號格式錯誤'])->render();

            return json_encode( $html );             
        }

        $osArr = [ 0 => '未確認',
                   1 => '已確認',
                   2 => '已取消',
                   3 => '無效',
                   4 => '退貨',
                   5 => '已出貨'
                 ];

        $ssArr = [ 0 => '未出貨',
                   1 => '已出貨',
                   2 => '已收貨',
                   3 => '備貨中',
                   4 => '已出貨',
                   5 => '出貨中'
                 ];

        
        /**
         * 取出訂單及出貨單資料
         **/
        $order = DB::table('xyzs_order_info as o')
               ->leftJoin('xyzs_delivery_order as d', 'o.order_id', '=', 'd.order_id')
               ->where('o.order_sn',$request->order_sn)
               ->select('o.order_id' , 'o.order_sn' ,  'o.order_status' , 'o.shipping_status', 'o.pay_status', 'o.postscript' , 'o.add_time' , 'o.shipping_time' , 'o.shipping_id' , 
                         'o.invoice_no' , 'o.user_id' , 'o.goods_amount' , 'o.shipping_fee' , 'o.order_amount' , 'd.out_date' , 'd.st_date' , 'd.tk_date' , 'd.back_date')
               ->first();
        
        $orderDatas = (array)$order;
        
        $orderDatas['order_status'] = $osArr[ $orderDatas['order_status'] ];
        
        $orderDatas['shipping_statusN2'] = $orderDatas['shipping_status'];

        $orderDatas['shipping_status'] = $ssArr[ $orderDatas['shipping_status'] ];

        $orderDatas['add_time'] = date( 'Y-m-d H:i:s' , $orderDatas['add_time'] - date('Z') );

        $orderDatas['shipping_time'] = date( 'Y-m-d H:i:s' , $orderDatas['shipping_time'] - date('Z') );
        
        $orderDatas['out_date'] = date( 'Y-m-d H:i:s' , $orderDatas['out_date'] - date('Z') );
        
        $orderDatas['st_date'] = date( 'Y-m-d H:i:s' , $orderDatas['st_date'] - date('Z') );
        
        $orderDatas['back_date'] = date( 'Y-m-d H:i:s' , $orderDatas['back_date'] - date('Z') );

        $orderDatas['tk_date'] = date( 'Y-m-d H:i:s' , $orderDatas['tk_date'] - date('Z') );
        
        /**
         * 如果是特定配送方式 , 就嘗試去撈取物流狀態
         ***********************************************************/
        if( in_array($orderDatas['shipping_id'], ['14','20','21','22','32']) && !empty($orderDatas['invoice_no']) ){
            

            $text=file_get_contents("https://www.t-cat.com.tw/Inquire/TraceDetail.aspx?BillID={$orderDatas['invoice_no']}&ReturnUrl=Trace.aspx");
        
            $xml = new \domDocument('1.0', 'utf-8'); 
 
            $xml->validateOnParse = true;
            
            libxml_use_internal_errors(true);

            $xml->loadHTML($text);

            $xpath = new \DOMXPath($xml);
        
            $table =$xpath->query("//*[@class='tablelist']")->item(0);

            $tcats = $table->getElementsByTagName("tr");

            $tcatFlow = [];

            foreach ($tcats as $tcatk => $tcat) {
            
                $cells = $tcat -> getElementsByTagName('td');
            
                if( $tcatk != 0 ){
                
                    $tmpFlow = [];
        
                if( $tcatk == 1){
                    
                    foreach ($cells as $cellk=>$cell) {
        
                        if( $cellk == 1 ){
        
                            $tmpFlow['status'] = trim($cell->nodeValue);
                        }
                        if( $cellk == 2 ){
                            
                            $tmpFlow['date']   = trim($cell->nodeValue);
                        }
                        if( $cellk == 3 ){
                            
                            $tmpFlow['station'] = trim($cell->nodeValue);
                        }                            
        
                    }
        
                    array_push($tcatFlow, $tmpFlow);
        
                }else{
        
                    foreach ($cells as $cellk=>$cell) {
        
                        if( $cellk == 0 ){
        
                            $tmpFlow['status'] = trim($cell->nodeValue);
                        }
                        if( $cellk == 1 ){
                            
                            $tmpFlow['date']   = trim($cell->nodeValue);
                        }
                        if( $cellk == 2 ){
                            
                            $tmpFlow['station'] = trim($cell->nodeValue);
                        }                            
        
                    }
        
                    array_push($tcatFlow, $tmpFlow);
                }
                }
            }

            if( count( $tcatFlow ) > 0 ){
            
                $orderDatas['tcatflow'] = $tcatFlow;
            }
        }         
        
        /**
         * 如果是新竹物流 ,
         **/
        if( ($orderDatas['shipping_id'] == '23' || $orderDatas['shipping_id'] == '24') &&  !empty($orderDatas['invoice_no']) ){
            
            $HCT = new hctTool(1);

            $datas = $HCT->orderQuery([ $orderDatas['invoice_no'] ]);
            
            if( $datas != false ){
            
                $orderDatas['hctflow'] = $datas[$orderDatas['invoice_no']]['@datas'];
            }
            
            //var_dump( $orderDatas );
            
        }


        /**
         * 取出訂單商品資料
         **/
        if( $order != NULL ){

            // 如果訂單有資料 , 接著將商品也抓出來
            $order_goods = DB::table('xyzs_order_goods as og')
                           ->leftJoin('xyzs_goods as g', 'g.goods_id', '=', 'og.goods_id')
                           ->where('og.order_id', $order->order_id )
                           ->select('og.goods_name','og.goods_number','og.goods_price','og.goods_sn','g.goods_thumb')
                           ->get();

            if( $order_goods != NULL ){
                 
                $orderGoodsDatas = json_decode($order_goods,true);
                
                foreach ($orderGoodsDatas as $orderGoodsDatak => $orderGoodsData) {

                    $orderGoodsDatas[ $orderGoodsDatak ]['goods_price'] = floor( $orderGoodsData['goods_price'] );

                }


                $html = view('league_check_order_res', ['type'=>1 ,
                                                        'orderDatas' => $orderDatas,
                                                        'orderGoodsDatas' =>$orderGoodsDatas,
                                                       ])->render();

                return json_encode( $html );
                //return  json_encode( [ "res"=>true, "msg"=>(array)$order_goods ] );

            }

            //return json_encode( [ "res"=>true, "msg"=>(array)$order ] );

        }

    }
}
