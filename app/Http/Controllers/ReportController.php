<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
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
        $DateArrs = [];
        
        for ( $i = $request->start ; $i <= $request->end ; $i += 86400) { 
            
            $DateArrs[ date('Ymd' , $i+date('Z') ) ] = 0;

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

        foreach ($DateArrs as $DateArrk => $DateArr) {
            
            if( array_key_exists( $DateArrk , $TmpPerDayOrders ) ){

            	$DateArrs[ $DateArrk ] = $TmpPerDayOrders[ $DateArrk ];
            }
 
        }

        $PerDayLabels = json_encode( array_keys($DateArrs) );

        $PerDayOrderNums = json_encode( array_values($DateArrs));


        return view('league_report_order',[ 'NoteMsgs' => $NoteMsgs,
        	                                'PerDayLabels' => $PerDayLabels ,
                                            'PerDayOrderNums' => $PerDayOrderNums
        	                              ]);
    }
}
