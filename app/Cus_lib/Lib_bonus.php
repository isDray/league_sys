<?php
namespace App\Cus_lib;
use DB;
use Session;
/*
|--------------------------------------------------------------------------
| 優惠券工具
|--------------------------------------------------------------------------
|
*/
class Lib_bonus{
    



    /*
    |--------------------------------------------------------------------------
    | 取得優惠券相關資料
    |--------------------------------------------------------------------------
    |
    */
	public static function _getBonusData( $_bonus_sn ){
        
        $datas = DB::table('xyzs_user_bonus as u')
                 ->leftJoin('xyzs_bonus_type as b', 'u.bonus_type_id', '=', 'b.type_id')
                 ->where( 'u.bonus_sn' , $_bonus_sn )
                 ->get();


        return $datas;

	}
    



    /*
    |--------------------------------------------------------------------------
    | 使用優惠券
    |--------------------------------------------------------------------------
    | return值為可折抵的價格
    | 
    */
	public static function _useBonus( $_bonus_sn = '' , $_orderPrice = 0){
        
        // 不管是優惠編號或者訂單金額任何一個為空值直接返回0元       
        if( empty($_bonus_sn) || empty($_orderPrice) ){
            
            return 0;
        }

        // 不論是加盟會員或者私有會員任何一個的session 為空值都是沒有權限使用優惠券的 , 直接返回0元
        if( empty( Session::get('league_id') ) || empty(Session::get('member_id')) ){
            
            return 0;

        }

        // 由資料庫取出優惠券資料
        $bonus = DB::table('xyzs_user_bonus as u')
                 ->leftJoin('xyzs_bonus_type as b', 'u.bonus_type_id', '=', 'b.type_id')
                 ->where( 'u.bonus_sn' , $_bonus_sn )
                 ->first();
        
        // 如果資料庫沒有對應資料 , 則視為不可使用
        if( $bonus === null ){
            
            return 0;

        }else{

        	$bonus = (array)$bonus;
        }
        
        // 取出當下時間
        $nowTime = Lib_common::_GetGMTTime();
        
        // 檢查如果不符合條件就直接視為不可使用 
        if( $nowTime < $bonus['use_start_date'] || $nowTime >$bonus['use_end_date'] ){

            return 0;

        }
        
        // use_type 表示針對加盟會員的計數類型優惠券
        if( $bonus['use_type'] == 1 ){
            
            if (empty($bonus) || $bonus['user_id'] != 0 || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > Lib_common::_getCartAmount()){
                
                return 0;
            }
            
            // 如果使用次數超過上限就直接認定不可使用
            if( $bonus['count_use'] >= $bonus['max_use'] ){
            	
            	return 0;
            }

        }else{ // 如果非計數類型優惠券則為一般優惠券(即一組編號一人使用)

            if (empty($bonus) || $bonus['user_id'] != 0 || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > Lib_common::_getCartAmount()){
                
                return 0;

            }

        }
        
        // 如果是計數類型優惠券 , 需要將使用次數加1 , 並且將當下的時間點寫入使用關聯表
        if( $bonus['use_type'] == 1 ){
            
            // 紀錄執行動作
            DB::table('xyzs_bonus_type')->where('type_id',$bonus['type_id'])->update([
                'count_use'=> DB::raw('count_use+1'), 
            ]);

            DB::table('xyzs_league_bonus_use')->insert(

                ['bonus_id'  => $bonus['bonus_id'] , 
                 'league_id' => Session::get('league_id') ,
                 'member_id' => Session::get('member_id') ,
                 'usetime'   => $nowTime
                ]
            );
            
            return ( $bonus['type_money'] > $_orderPrice )?$_orderPrice:$bonus['type_money'];

        }else{
            
            // 
            DB::table()->where('xyzs_user_bonus',$bonus['bonus_id'])->update([
                'user_id'  => Session::get('league_id'), 
                'used_time'=> $nowTime
            ]);

            return ( $bonus['type_money'] > $_orderPrice )?$_orderPrice:$bonus['type_money'];
        }
        

	}
}

?>