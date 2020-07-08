<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use Storage;
/*
|--------------------------------------------------------------------------
| 排程專用 controller
|--------------------------------------------------------------------------
| 
|
*/
class CronController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 產生當週銷售最好的三樣商品
    |--------------------------------------------------------------------------
    |
    */
    public function getTop3( Request $request ){
        /*
        if( $argv[1] != 'top3' || empty( $argv[1] ) ){
            
            return;

        } 
        */
        $today   = strtotime( date('Y-m-d 00:00:00') ) - date('Z') ;

        $weekAgo = $today - (86400*7);

        $goods = DB::table('xyzs_order_info as oi')
               ->leftJoin('xyzs_order_goods as og', 'oi.order_id', '=', 'og.order_id')
               ->leftJoin('xyzs_goods as g', 'og.goods_id', '=', 'g.goods_id')
               ->where('oi.add_time','>=',$weekAgo)
               ->where('oi.add_time','<=',$today)
               ->where(function( $query ){
                    return $query->where('order_status', '=', 1)
                    ->orWhere('order_status', '=', 5);
               })
               ->where(function( $query2 ){
                    return $query2->where('shipping_status', '=', 1)
                    ->orWhere('shipping_status', '=', 2);
               })
               ->where('g.is_on_sale',1)
               ->where('g.shop_price','>=',200)
               ->where('g.goods_number','>',0)
               ->select('g.*', DB::raw('SUM(og.goods_number) AS totalsale') )
               ->groupBy('g.goods_id')
               ->orderBy('totalsale','desc')
               ->offset(0)
               ->limit(3)
               ->get();



        Storage::disk('ezpub')->put('top3.json', json_encode($goods)  );

    }


}
