<?php
namespace App\Cus_lib;
use DB;
use Session;

/*
|--------------------------------------------------------------------------
| 獎金類別
|--------------------------------------------------------------------------
|
*/

Class Lib_commission{
    



    /*
    |--------------------------------------------------------------------------
    | 獎金計算
    |--------------------------------------------------------------------------
    |
    | $_allPrice => 訂單總金額
    |
    */
    public static function CalculateCommission( $_allPrice = 0 ){
        
        

        $_commissions = DB::table('xyzs_league_commission_rate')->orderBy('price','DESC')->get();

        $_commissions = json_decode( $_commissions , TRUE );
        
        // 獎金計算方式
        $commission_type =  DB::table('xyzs_league_setting')->select('commission_type')->where('id',1)->first();

        if($commission_type){
            
            $_type = $commission_type->commission_type;
        }
        else
        {
            $_type = 0;
        }        
        
        $_commission_price = 0;
        
        // 取最佳獎金比
        if( !$_type )
        {    
            $rate = 0;

            foreach ($_commissions as $_commissionk => $_commission) {
                
                // 如果總價小於獎金級距金額，表示不是用直接跳過
                if(  $_allPrice < $_commission['price'] )
                {
                    continue;
                }
                else
                {  
                    $rate = $_commission['rate'];
                    break;
                }

            }

            $_commission_price = round( $_allPrice * ($rate/100) );

        }
        // 階段性獎金比
        else
        {   
        	$rate = 0;
        	
        	$_price_section = 0;

            foreach ($_commissions as $_commissionk => $_commission) {
                
                // 如果總價小於獎金級距金額，表示不是用直接跳過
                if(  $_allPrice <= $_commission['price'] )
                {
                    continue;
                }
                else
                {  
                    $rate = $_commission['rate'];

                    $_price_section = $_allPrice - $_commission['price'];

                    $_allPrice      = $_commission['price'];

                    $_commission_price +=  round( $_price_section * ($rate/100) );
                }            	
            }
        }

        return $_commission_price;
    }




    /*
    |--------------------------------------------------------------------------
    | 針對獎金報表中細項做計算
    |--------------------------------------------------------------------------
    | $_totalPrice => 指定月分內訂單累加金額( 如果為"以最高計算"則為訂單總和 ) 
    |
    | $_orderPrice => 目前正要計算的訂單
    */
    public static function CalculateCommissionList( $_totalPrice = 0 , $_orderPrice = 0 )
    {
        $_commissions = DB::table('xyzs_league_commission_rate')->orderBy('price','DESC')->get();

        $_commissions = json_decode( $_commissions , TRUE );
        

        $_2commissions = DB::table('xyzs_league_commission_rate')->orderBy('price','ASC')->get();

        $_2commissions = json_decode( $_2commissions , TRUE ); 

        // 獎金計算方式 , 0 為固定額 , 1為以級距計算
        $commission_type =  DB::table('xyzs_league_setting')->select('commission_type')->where('id',1)->first();

        if($commission_type){
            
            $_type = $commission_type->commission_type;
        }
        else
        {
            $_type = 0;
        }
        

        $_commission_price = 0;

        if( !$_type ){

            $rate = 0;
            
            foreach ($_commissions as $_commissionk => $_commission) {
                // 如果總價小於獎金級距金額，表示不是用直接跳過
                if(  $_totalPrice < $_commission['price'] )
                {
                    continue;
                }
                else
                {  
                    $rate = $_commission['rate'];
                    break;
                }
            }

            $_commission_price = $_orderPrice * ($rate/100) ;            
        } 
        else
        {  

            $newTotal = $_totalPrice + $_orderPrice;

            foreach ($_commissions as $_commissionk => $_commissions ) {

                if( $newTotal > $_commissions['price'] )
                {


                    $ok_price = $newTotal - $_commissions['price'];
                    
                    // 訂單全都到達獎金級距
                    if( $ok_price >= $_orderPrice )
                    {
                        $_commission_price += $_orderPrice * ($_commissions['rate']/100);
                        break;
                    }
                    else
                    {   

                        $_commission_price += $ok_price * ($_commissions['rate']/100);

                        $_orderPrice = $_orderPrice - $ok_price;
                    }

                }
                else
                {
                    continue;
                }
                


            }
        } 

        return $_commission_price;
    }
}
?>