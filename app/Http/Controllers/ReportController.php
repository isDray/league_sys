<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use File;
use App\Cus_lib\Lib_common;
use App\Cus_lib\Lib_commission;

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
                                        AND (order_status = 5 AND shipping_status = 2 AND pay_status = 2 )
                                        GROUP BY DAY(FROM_UNIXTIME(add_time + 28800))', ['league' => $LeagueId , 'MonthStart'=>$request->start , 'MonthEnd'=>$request->end ]);
        
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
                               AND (order_status = 5 AND shipping_status = 2 AND pay_status = 2 )'
                               , ['league' => $LeagueId , 'MonthStart'=>$request->start  , 'MonthEnd'=>$request->end] );        
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
        
        // 提示訊息
        $NoteMsgs = [];
        
        // 加盟會員ID
        $LeagueId = $request->session()->get('user_id');
        
        // 頁面標題
        $PageTitle = '獎金報表'; 

        /*
        |--------------------------------------------------------------------------
        | 如果沒有選擇日期 , 則需要將查詢日期選到最接近的結算月
        | 上個月:1~30(31)的訂單
        | 本月超過15號才可以查詢
        | 本月超過20號才可以進行匯款動作
        |
        */         
        
        // 當下月份
        $NowMonth = date( 'm' , time() );
        
        // 當下日期
        $NowDate  = date('d' , time() );
        
        // 本月第一天
        $NowMonth01 = strtotime( date('Y-m-01 00:00:00') );

        // 本月最後一天
        $NowMonthLasted = strtotime( date('Y-m-t 23:59:59') );
        
        // 如果沒有接收到月份相關的參數 , 就以最近期可以查詢的日期作為查詢條件
        if( empty( $request->start ) ){
            
            // 由於每月超過15號才能夠查詢上個月的獎金 , 所以當下日期如果沒有超過15號就只能查上上個月
            if( $NowDate >= 15 ){

                $StartDate = strtotime("first day of last month", $NowMonth01 )- date('Z');
                
                $EndDate   = strtotime( "last day of last month", $NowMonthLasted )- date('Z');

            }else{

                $StartDate = strtotime("first day of -2 month", $NowMonth01 )- date('Z');

                $EndDate   = strtotime("last day of -2 month", $NowMonthLasted )- date('Z');

            }

        }else{
            
            // 先判斷是否為可以查詢的月份

            // 最後一天可查詢日期
            $LatestDray = '';

            if( $NowDate >= 15 ){
                
                $LatestDray   = strtotime( "last day of -1 month", $NowMonthLasted )- date('Z');

            }else{

                $LatestDray   = strtotime( "last day of -2 month", $NowMonthLasted )- date('Z');
            }
            
            $EndDate = date( 'Y-m-t 23:59:59'   , strtotime( $request->start )  );

            $EndDate = strtotime($EndDate) - date('Z');
            
            // 如果使用者所選取的查訊日 , 小於最後可以查詢日期 , 表示此日期可以查詢 , 否則則需要強制替其變換日期
            if( $LatestDray > $EndDate ) {
                
                $StartDate = strtotime(date("Y-m-01 00:00:00", $EndDate )) - date('Z');

            }else{
           
                if( $NowDate >= 15 ){
    
                    $StartDate = strtotime("first day of last month", $NowMonth01 )- date('Z');
                    
                    $EndDate   = strtotime( "last day of last month", $NowMonthLasted )- date('Z');
    
                }else{
    
                    $StartDate = strtotime("first day of -2 month", $NowMonth01 )- date('Z');
    
                    $EndDate   = strtotime("last day of -2 month", $NowMonthLasted )- date('Z');
    
                }
            }

        }


        /*
        |--------------------------------------------------------------------------
        | 針對狀態惡意操作避免 , 永遠只接受三個狀態 , 如果不符合就給預設值
        |
        |
        */
        /*$AgreeSatuts = [0,1,2];

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
        */

        $CommissionStatus = [ 0 , 1 ];

        /*
        |--------------------------------------------------------------------------
        | 查詢訂單
        |
        |
        */
        /*$table('goods')->select('color','size');

        $table('goods')->leftJoin('attr');

        $goods['red']['mid'] = 10;*/


        $Orders = DB::table('xyzs_order_info')
                  ->where('league',$LeagueId)
                  ->where('add_time','>=',$StartDate)
                  ->where('add_time','<=',$EndDate)
                  ->whereIn('league_pay',$CommissionStatus)
                  ->where( function( $query ){
                        $query->where('order_status','5')
                              ->where('pay_status','2')
                              ->where('shipping_status','2');

                  })                  
                  ->orderBy('order_id','ASC')
                  ->select(DB::raw("(".Lib_common::_GetTotalFee().") as total_fee"),"add_time","order_sn",'league_pay')
                  ->get();

        $Orders = json_decode( $Orders , true) ;
        

        $commission_type =  DB::table('xyzs_league_setting')->select('commission_type')->where('id',1)->first();

        if($commission_type){
            
            $_type = $commission_type->commission_type;
        }
        else
        {
            $_type = 0;
        }

        
        // 臨時訂單加總，用來計算訂單獎金
        $tmp_total = 0;

        if( !$_type ){

            foreach ($Orders as $Orderk => $Order) {
                
                $tmp_total += $Order['total_fee'];
            }
            
            foreach ($Orders as $Orderk => $Order) {

                $tmp_commission = Lib_commission::CalculateCommissionList( $tmp_total , $Order['total_fee'] );

                $Orders[$Orderk]['commission'] = $tmp_commission;
            }
        } 
        else
        {
            $tmp_total = 0;

            foreach ($Orders as $Orderk => $Order) {

               $tmp_commission = Lib_commission::CalculateCommissionList( $tmp_total , $Order['total_fee']);

               $tmp_total += $Order['total_fee'];

               $Orders[$Orderk]['commission'] = $tmp_commission;

            }

        }  

        
        /*
        |--------------------------------------------------------------------------
        | 獎金成長曲線圖
        |
        |
        */
        $RangeDateArrs = [];
        
        // 產生一組key值為本月每天日期的陣列
        for ( $i = $StartDate ; $i <= $EndDate ; $i += 86400) { 
            
            $RangeDateArrs[ date('Ymd' , $i+date('Z') ) ] = 0;

        }
        
        // 取出當下月份中每一天的"已完成訂單"的 數量 & 金額
        $DayCommissions = DB::table('xyzs_order_info')
        ->select( DB::raw( "DATE_FORMAT(FROM_UNIXTIME(add_time+28800),'%Y%m%d') as order_date") ,
                  DB::raw( "COUNT(order_id) as day_order" ) ,
                  DB::raw( "SUM((".Lib_common::_GetTotalFee().")) as day_price"),
                  'order_id' )
        ->where('league',$LeagueId)
        ->where('add_time','>=',$StartDate)
        ->where('add_time','<=',$EndDate)
        ->whereIn('league_pay',$CommissionStatus)
        ->where(function( $query ){
            $query->where('order_status','5')
                  ->where('pay_status','2')
                  ->where('shipping_status','2');
        })
        ->groupBy( DB::raw( "DAY(FROM_UNIXTIME(add_time + 28800) )"))
        ->get();
        
        
        $DayCommissions = json_decode( $DayCommissions , true );
        
        // 根據系統設定 , 決定是要採最高 or 階段算獎金
        $commission_type =  DB::table('xyzs_league_setting')->select('commission_type')->where('id',1)->first();

        if($commission_type){
            
            $_type = $commission_type->commission_type;
        }
        else
        {
            $_type = 0;
        }

        $commission_type = $_type;
        
        // 最高算法
        if( !$commission_type )
        {   
            // 訂單金額加總
            $tmp_total = 0;

            foreach ($DayCommissions as $DayCommissionk => $DayCommission ) {
                
                $tmp_total += $DayCommission['day_price'];
            }
            
            foreach ($DayCommissions as $DayCommissionk => $DayCommission) {
            
                if( array_key_exists( $DayCommission['order_date'] , $RangeDateArrs) ){


                    $RangeDateArrs[ $DayCommission['order_date'] ] = Lib_commission::CalculateCommissionList( $tmp_total , $DayCommission['day_price']);

                }
            }
        }
        // 階段
        else
        {   
            $tmp_total = 0;

            foreach ($DayCommissions as $DayCommissionk => $DayCommission) {
            
                if( array_key_exists( $DayCommission['order_date'] , $RangeDateArrs) ){


                    $RangeDateArrs[ $DayCommission['order_date'] ] = Lib_commission::CalculateCommissionList( $tmp_total , $DayCommission['day_price']);
                    
                    $tmp_total += $DayCommission['day_price'];
                }
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
        
        $ThisMonthCommission = round( end( $DayCommissions ) );
        $DayCommissions = json_encode( $DayCommissions );

        // 取出指定月份前尚未撥款獎金總和
        /*
        $Unpay = DB::table('xyzs_order_info')
        //->select( DB::raw( "SUM(ROUND( (".Lib_common::_GetTotalFee().") * 0.2)) as commission" ) )
        ->select( DB::raw( "SUM(".Lib_common::_GetTotalFee().") as commission" ) )
        ->where('league',$LeagueId)
        ->where('add_time','<',$StartDate)
        ->whereIn('league_pay',[0])
        ->where(function( $query ){
            $query->where('order_status','5')
                  ->where('pay_status','2')
                  ->where('shipping_status','2');
        })
        ->groupBy( DB::raw( "DAY(FROM_UNIXTIME(add_time + 28800) )"))
        ->get();*/
        
        // 取出未領取獎金之訂單，並且依照每個月區分
        $Unpays = DB::table('xyzs_order_info')
        ->select( DB::raw( "DATE_FORMAT(FROM_UNIXTIME(add_time+28800),'%Y%m') as order_date") ,
                  DB::raw( "COUNT(order_id) as month_order" ) ,
                  DB::raw( "SUM((".Lib_common::_GetTotalFee().")) as month_price"),
                  'order_id' )  
        ->where('league',$LeagueId)
        ->where('add_time','<',$StartDate)
        ->whereIn('league_pay',[0])
        ->where(function( $query ){
            $query->where('order_status','5')
                  ->where('pay_status','2')
                  ->where('shipping_status','2');
        })            
        ->groupBy( DB::raw( "MONTH(FROM_UNIXTIME(add_time + 28800) )"))            
        ->get();
        
        $Unpays = json_decode( $Unpays , true );
        // 每個月個別計算獎金
        $Unpay_price = 0;
        foreach ($Unpays as $Unpayk => $Unpay) {
            //$Unpay['month_price'];

            $Unpay_price += Lib_commission::CalculateCommission($Unpay['month_price']);
        }

        $Unpay_price = round( $Unpay_price );
        /*
        Lib_commission::CalculateCommission($Unpay[0]->commission);
        
        $Unpay = $Unpay[0]->commission;
        
        if( empty($Unpay) ){

            $Unpay = 0;

        }
        */
        $Unpay = 0;// 除錯用
        //echo date("Ymd H:i:s");
        return view('league_report_commission',[ 'PageTitle'=>$PageTitle,
                                                 'start' => date('Y-m' , $StartDate +date('Z')),
                                                 'end'   => date('Y-m' , $EndDate + date('Z')),
                                                 'commission_status' => $request->commission_status,
                                                 'NoteMsgs' => $NoteMsgs ,
                                                 'Orders' => $Orders,
                                                 'DateX' => $DateX,
                                                 'DayCommissions'=>$DayCommissions,
                                                 'PerDayCommissions'=>$PerDayCommissions,
                                                 'tree'=>'report',
                                                 'Unpay' => $Unpay_price,
                                                 'ThisMonthCommission' =>$ThisMonthCommission
                                               ]);





    }
}
