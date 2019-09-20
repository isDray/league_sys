<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use File;
use App\Cus_lib\Lib_common;

class ReportController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | 訂單報表查詢
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_report_order( Request $request ){
        
        $NoteMsgs = [];
        
        $Before7Day = time() -  date('Z') - ( 7 * 24 * 60 * 60 );
        
        $PageTitle = '訂單報表'; 

        /*
        |--------------------------------------------------------------------------
        | 如果沒有選取值 , 就以當月開始跟結尾當成預設值
        |
        */
        if( empty( $request->start ) ){
            
            $request->start = strtotime( date('Y-m-01 00:00:00') ) - date('Z');

        }else{
            
            $request->start = strtotime( $request->start." 00:00:00" ) - date('Z');
        }
        
        if( empty( $request->end ) ){
            
            $request->end = strtotime( date('Y-m-t 23:59:59') ) - date('Z');
            
        }else{
            
            $request->end = strtotime( $request->end." 23:59:59") - date('Z');
        }
        
        /*
        |--------------------------------------------------------------------------
        | 最多查詢三個月
        | 
        | ** 此查詢為了避免使用者疑惑 , 有刻意多補一天
        */

        if( ($request->start + 86401) < strtotime("-3 months",$request->end ) ){

            $request->start = strtotime("-3 months",$request->end );

            array_push( $NoteMsgs , '開始日期到結束日期不能超過三個月 , 目前已將您的日期調整至三個月內 ');

        }

        /*
        |--------------------------------------------------------------------------
        | 撈取訂單資料
        |
        */
        $RangeDateArrs = [];
        
        for ( $i = $request->start ; $i <= $request->end ; $i += 86400) { 
            
            $RangeDateArrs[ date('Ymd' , $i+date('Z') ) ] = 0;

        }

        $LeagueId = $request->session()->get('user_id');

        $PerDayOrders = DB::select('SELECT DATE_FORMAT(FROM_UNIXTIME(add_time+28800),"%Y%m%d") as order_date, COUNT(order_id) as day_order ,order_id 
                                    FROM xyzs_order_info WHERE league = :league 
                                    AND add_time >= :MonthStart
                                    AND add_time <= :MonthEnd
                                    GROUP BY DAY(FROM_UNIXTIME(add_time + 28800))', ['league' => $LeagueId , 'MonthStart'=>$request->start , 'MonthEnd'=>$request->end]);
        
        $TmpPerDayOrders = [];

        foreach ($PerDayOrders as $PerDayOrderk => $PerDayOrder) {

            $TmpPerDayOrders[ $PerDayOrder->order_date ] = $PerDayOrder->day_order;

        }

        $DateArrs = $RangeDateArrs;

        foreach ($DateArrs as $DateArrk => $DateArr) {
            
            if( array_key_exists( $DateArrk , $TmpPerDayOrders ) ){

            	$DateArrs[ $DateArrk ] = $TmpPerDayOrders[ $DateArrk ];
            }
 
        }

        $PerDayLabels = json_encode( array_keys($DateArrs) );

        $PerDayOrderNums = json_encode( array_values($DateArrs));
        
        /*
        |--------------------------------------------------------------------------
        | 撈取已完成訂單
        |
        |
        */        
        $PerDayDoneOrders = DB::select('SELECT DATE_FORMAT(FROM_UNIXTIME(add_time+28800),"%Y%m%d") as order_date, COUNT(order_id) as day_order ,order_id 
                                        FROM xyzs_order_info WHERE league = :league 
                                        AND add_time >= :MonthStart
                                        AND add_time <= :MonthEnd
                                        AND ( (order_status = 5 AND shipping_status = 1 AND shipping_time <= :Before7Day ) OR
                                              (order_status = 5 AND shipping_status = 2 )
                                        )                                        
                                        GROUP BY DAY(FROM_UNIXTIME(add_time + 28800))', ['league' => $LeagueId , 'MonthStart'=>$request->start , 'MonthEnd'=>$request->end , 'Before7Day'=>$Before7Day]);
        
        $TmpPerDayDoneOrders = [];
        

        foreach ($PerDayDoneOrders as $PerDayDoneOrderk => $PerDayDoneOrder) {
            
            $TmpPerDayDoneOrders[ $PerDayDoneOrder->order_date ] = $PerDayDoneOrder->day_order;
        }
        
        $TmpDateArrs = $RangeDateArrs;

        foreach ($TmpDateArrs as $TmpDateArrk => $TmpDateArr ) {
            
            if( array_key_exists( $TmpDateArrk , $TmpPerDayDoneOrders ) ){

                $TmpDateArrs[ $TmpDateArrk ] = $TmpPerDayDoneOrders[ $TmpDateArrk ];
            }            
        }

        $PerDayDoneOrderNums = json_encode( array_values($TmpDateArrs));
        
        /*
        |--------------------------------------------------------------------------
        | 繪製訂單成長曲線
        |
        |
        */

        $GrowDatas     = json_decode( $PerDayOrderNums );
        $GrowDoneDatas = json_decode( $PerDayDoneOrderNums );

        foreach ( $GrowDatas as $GrowDatak => $GrowData) {
            
            if( array_key_exists( $GrowDatak-1 , $GrowDatas ) ){
                
                $GrowDatas[ $GrowDatak ] = ($GrowData + $GrowDatas[$GrowDatak-1]) ;
            }else{
                $GrowDatas[ $GrowDatak ] = $GrowData;
            }

        }

        foreach ($GrowDoneDatas as $GrowDoneDatak => $GrowDoneData) {

            if( array_key_exists( $GrowDoneDatak-1 , $GrowDoneDatas ) ){

                $GrowDoneDatas[$GrowDoneDatak] = $GrowDoneDatas[$GrowDoneDatak-1] + $GrowDoneData;

            }else{
                
                $GrowDoneDatas[$GrowDoneDatak] = $GrowDoneData;
            }
        }
        
        /*
        |--------------------------------------------------------------------------
        | 計算完成比例
        |
        |
        */
        $PercnetStatus = [ (end($GrowDatas)-end($GrowDoneDatas)) , end($GrowDoneDatas) ];
        
        $PercnetStatus = json_encode($PercnetStatus);

        $OrderGrow = json_encode($GrowDatas);
        
        $OrderDoneGrow = json_encode($GrowDoneDatas);
        
        /*
        |--------------------------------------------------------------------------
        | 重點銷售分類
        |
        |
        */
        $allDones =DB::select('SELECT oi.order_id
                               FROM xyzs_order_info as oi 
                               WHERE oi.league = :league 
                               AND oi.add_time >= :MonthStart
                               AND oi.add_time <= :MonthEnd
                               AND ( (order_status = 5 AND shipping_status = 1 AND shipping_time <= :Before7Day ) OR
                                     (order_status = 5 AND shipping_status = 2 )
                               )'
                               , ['league' => $LeagueId , 'MonthStart'=>$request->start  , 'MonthEnd'=>$request->end,'Before7Day'=>$Before7Day] );        
        $allOrderIds = [];
        

        foreach ($allDones as $allDonek => $allDone) {

            array_push( $allOrderIds , $allDone->order_id );
        }

        $allOrderGoods =  DB::table('xyzs_order_goods as og')
                       -> select('og.goods_id','g.cat_id',DB::raw('SUM(og.goods_number) as cat_num'),'c.cat_name')
                       -> leftJoin('xyzs_goods as g', 'og.goods_id', '=', 'g.goods_id')
                       -> leftJoin('xyzs_category as c', 'g.cat_id', '=', 'c.cat_id')
                       -> whereIn('order_id',$allOrderIds)
                       -> groupBy('g.cat_id')
                       -> get();
        
        // 轉換成為陣列
        $allOrderGoods = json_decode( $allOrderGoods , true );
        
        // 整理成繪圖陣列
        $RadarCatNames = [];
        $RadarCatNums  = [];

        foreach ( $allOrderGoods as $allOrderGoodk => $allOrderGood ) {

            array_push( $RadarCatNames , $allOrderGood['cat_name'] );

            array_push( $RadarCatNums  , $allOrderGood['cat_num'] );
        }
        
        // 轉換成json
        $RadarCatNames = json_encode( array_values($RadarCatNames) , true );

        $RadarCatNums  = json_encode( array_values($RadarCatNums) , true);  

        return view('league_report_order',[ 'PageTitle' => $PageTitle ,
                                            'start' => date('Y-m-d' , $request->start +date('Z')),
                                            'end'   => date('Y-m-d' , $request->end +date('Z')),
                                            'NoteMsgs' => $NoteMsgs,
        	                                'PerDayLabels' => $PerDayLabels ,
                                            'PerDayOrderNums' => $PerDayOrderNums,
                                            'PerDayDoneOrderNums'=>$PerDayDoneOrderNums,
                                            'OrderGrow' => $OrderGrow,
                                            'OrderDoneGrow' => $OrderDoneGrow,
                                            'PercnetStatus' => $PercnetStatus,
                                            'RadarCatNames' => $RadarCatNames,
                                            'RadarCatNums'  => $RadarCatNums,
                                            'tree'=>'report'

        	                              ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 獎金報表查詢
    |--------------------------------------------------------------------------
    | 
    |
    */
    public function league_report_commission( Request $request ){

        $NoteMsgs = [];

        $LeagueId = $request->session()->get('user_id');

        $Before7Day = time() -  date('Z') - ( 7 * 24 * 60 * 60 );
        
        $PageTitle = '獎金報表'; 

        /*
        |--------------------------------------------------------------------------
        | 如果沒有選擇日期 , 則需要將查詢日期選到最接近的結算月
        | 上個月:1~30(31)的訂單
        | 本月超過15號才可以查詢
        | 本月超過20號才可以進行匯款動作
        |
        */
        
        // 先查看今天月份及日期
        $NowMonth = date( 'm' , time() );
        
        $NowDate  = date('d' , time() );
        
        $NowMonth01 = strtotime( date('Y-m-01 00:00:00') );

        if( empty( $request->start ) ){
            
            if( $NowDate >= 15 ){

                /*echo date('Ymd.H*s*i',time());*/
                echo '<br>';

                $StartDate = date("Y-m-d H:s:i", strtotime("-1 month", $NowMonth01 - date('Z') ));
                
            }else{
          
                $StartDate = date("Y-m-d H:s:i", strtotime("-2 month", $NowMonth01 - date('Z') ));
            }

        }else{

            echo $request->start ;

            /**/
            $p = 'T';

            $T = 'supr';

            $supreme = ['col','siz'];
            
            $col= ['r','g'];

            $siz =['m','x'];

            $mix = [
                       ['supr','r'] , ['supr','x']
                    ];

            $this->attributes = ['carler','seiz'];

            $goods = [ $this->attributes ];


            /**/

            
        }
        
        /*
        |--------------------------------------------------------------------------
        | 針對狀態惡意操作避免 , 永遠只接受三個狀態 , 如果不符合就給預設值
        |
        |
        */
        $AgreeSatuts = [0,1,2];

        if( !isset( $request->commission_status ) || !in_array( $request->commission_status , $AgreeSatuts) ){

            $request->commission_status = 0;
        }
      
        if( $request->commission_status == 0 ){

            $CommissionStatus = [ 0 , 1 ];

        }elseif( $request->commission_status == 1 ){

            $CommissionStatus = [ 0 ];
        
        }else{
            
            $CommissionStatus = [ 1 ];
        }
        
        /*
        |--------------------------------------------------------------------------
        | 查詢訂單
        |
        |
        */
        $Orders = DB::table('xyzs_order_info')
                  ->where('league',$LeagueId)
                  ->where('add_time','>=',$request->start)
                  ->where('add_time','<=',$request->end)
                  ->whereIn('league_pay',$CommissionStatus)
                  ->where(function( $query ) use ($Before7Day){
                    $query->where(function($quey2) use ($Before7Day){
                        $quey2->where('order_status','5')
                              ->where('shipping_status','1')
                              ->where('shipping_time' ,'<' , $Before7Day);
                    })
                    ->orWhere(function($query3){
                        $query3->where('order_status','5')
                        ->where('shipping_status','2');
                    });
                  })                  
                  ->select(DB::raw("(".Lib_common::_GetTotalFee().") as total_fee"),"add_time","order_sn" , DB::raw("(ROUND( (".Lib_common::_GetTotalFee().") * 0.2 ) ) as commission"),'league_pay')
                  ->get();

        $Orders = json_decode( $Orders , true) ;
        
        /*
        |--------------------------------------------------------------------------
        | 獎金成長曲線圖
        |
        |
        */
        $RangeDateArrs = [];
        
        for ( $i = $request->start ; $i <= $request->end ; $i += 86400) { 
            
            $RangeDateArrs[ date('Ymd' , $i+date('Z') ) ] = 0;

        }

        $DayCommissions = DB::table('xyzs_order_info')
        ->select( DB::raw( "DATE_FORMAT(FROM_UNIXTIME(add_time+28800),'%Y%m%d') as order_date") ,
                  DB::raw( "COUNT(order_id) as day_order" ) ,
                  DB::raw( "SUM(ROUND( (".Lib_common::_GetTotalFee().") * 0.2)) as commission"),
                  'order_id' )
        ->where('league',$LeagueId)
        ->where('add_time','>=',$request->start)
        ->where('add_time','<=',$request->end)
        ->whereIn('league_pay',$CommissionStatus)
        ->where(function( $query ) use ($Before7Day){
            $query->where(function($quey2) use ($Before7Day){
                $quey2->where('order_status','5')
                ->where('shipping_status','1')
                ->where('shipping_time' ,'<' , $Before7Day);
            })
            ->orWhere(function($query3){
                $query3->where('order_status','5')
                ->where('shipping_status','2');
            });
        })
        ->groupBy( DB::raw( "DAY(FROM_UNIXTIME(add_time + 28800) )"))
        ->get();
        
        $DayCommissions = json_decode( $DayCommissions , true );
        
        foreach ($DayCommissions as $DayCommissionk => $DayCommission) {
            
            if( array_key_exists( $DayCommission['order_date'] , $RangeDateArrs) ){
                
                $RangeDateArrs[ $DayCommission['order_date'] ] = $DayCommission['commission'];
            }
        }
        
        $DateX = json_encode( array_keys($RangeDateArrs) );

        $DayCommissions = array_values($RangeDateArrs);
        
        $PerDayCommissions = json_encode( $DayCommissions );

        foreach ( $DayCommissions as $DayCommissionk => $DayCommission ) {
            
            if( array_key_exists( ($DayCommissionk-1) , $DayCommissions) ){
                
                $DayCommissions[$DayCommissionk] = $DayCommission +  $DayCommissions[$DayCommissionk-1];

            }else{

                $DayCommissions[$DayCommissionk] = $DayCommission;
            }
        }

        $DayCommissions = json_encode( $DayCommissions );
        
        return view('league_report_commission',[ 'PageTitle'=>$PageTitle,
                                                 'start' => date('Y-m-d' , $request->start +date('Z')),
                                                 'end'   => date('Y-m-d' , $request->end +date('Z')),
                                                 'commission_status' => $request->commission_status,
                                                 'NoteMsgs' => $NoteMsgs ,
                                                 'Orders' => $Orders,
                                                 'DateX' => $DateX,
                                                 'DayCommissions'=>$DayCommissions,
                                                 'PerDayCommissions'=>$PerDayCommissions,
                                                 'tree'=>'report'
                                               ]);





    }
}
