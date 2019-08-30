<?php
namespace App\Cus_lib;
use DB;

/*
|--------------------------------------------------------------------------
| 通用工具
|--------------------------------------------------------------------------
| 在各個功能都有機會被使用到的工具 , 統一集中至此
|
*/
class Lib_common{
    
    public static  function GetCategorys(){
        
        $RootCategorys = DB::table('xyzs_category')->orderBy('parent_id', 'asc')->orderBy('sort_order', 'asc')->get();
        
        $RootCategorys = json_decode( $RootCategorys , true );
        
        $ReturnArr     = [];

        foreach( $RootCategorys as $RootCategoryk => $RootCategory ) {
            
            if( $RootCategory['parent_id'] == 0 ){
            	//echo $RootCategory['cat_name'] ;
            	//echo '<br>';
            	array_push( $ReturnArr , ['rcat'      => $RootCategory['cat_id'],
                                          'rcat_name' => $RootCategory['cat_name'],
                                          'child'     => [],
            		                     ]);
            	unset( $RootCategorys[$RootCategoryk] );
            }

            
        }
        
        // 將子分類放入對應位置
        foreach( $RootCategorys as $RootCategoryk => $RootCategory ) {

            foreach ($ReturnArr as $ReturnArrk => $ReturnArrv) {
                
                if( $RootCategory['parent_id'] == $ReturnArrv['rcat'] ){

                	array_push( $ReturnArr[$ReturnArrk]['child'] , [ 'ccat' => $RootCategory['cat_id'] ,
                                                                     'ccat_name' => $RootCategory['cat_name'],
                		                                           ]  );
                }
            }
        }

        return $ReturnArr;

    }
}

?>