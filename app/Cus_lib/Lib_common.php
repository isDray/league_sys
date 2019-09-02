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

    public static function mobileEncode( $_key , $_num ){
    
      $_key = '8610';
    
      $idNums  = preg_split('//', $_key, -1, PREG_SPLIT_NO_EMPTY);
    
      $idSum   = 0;
      
      foreach ($idNums as $idNumk => $idNum) {
       
        $idSum += $idNum;
    
    
      }
    
      $position = $idSum % mb_strlen( $_num , "utf-8");
    
      if( $position == 0 ){
    
        $mergeNum = $_num.$_key;
    
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
        //return base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),$mergeNum, MCRYPT_MODE_ECB, $iv)));
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),$mergeNum, MCRYPT_MODE_ECB, $iv));
        
      }else{
       
        $mergeNum[0] = substr($_num, 0, $position);
        $mergeNum[1] = $_key;
        $mergeNum[2] = substr($_num, $position);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
       
        //return base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),implode("",$mergeNum), MCRYPT_MODE_ECB, $iv)));
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),implode("",$mergeNum), MCRYPT_MODE_ECB, $iv));
    
      }
    }

    // 手機解密
    public  static function mobileDecode( $_key , $_ciphertext ){
    
      //$_ciphertext;
      $_key = '8610';  
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $encodeNum = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($_key), base64_decode($_ciphertext), MCRYPT_MODE_ECB, $iv));
    
      $idNums  = preg_split('//', $_key, -1, PREG_SPLIT_NO_EMPTY);
    
      $idSum   = 0;
    
      foreach ($idNums as $idNumk => $idNum) {
       
        $idSum += $idNum;
    
      }
      
      $position = $idSum % (mb_strlen( $encodeNum , "utf-8") - 4);
    
    
      if( $position == 0 ){
    
        return  substr($encodeNum, 0, -4);
    
      }else{
    
        $keylen = strlen($_key);
    
        $mergeNum[0] = substr($encodeNum, 0, $position);
        $mergeNum[1] = substr($encodeNum, ($position+$keylen) );
        
        return implode("",$mergeNum);
      }
    }

    // 家電加密
    public static function telEncode( $_key , $_num ){
    
      $_key = '8610';
      $idNums  = preg_split('//', $_key, -1, PREG_SPLIT_NO_EMPTY);
    
      $idSum   = 0;
      
      foreach ($idNums as $idNumk => $idNum) {
       
        $idSum += $idNum;
    
    
      }
      $position = $idSum % mb_strlen( $_num , "utf-8");
    
    
      if( $position == 0 ){
    
        $mergeNum = $_num.$_key;
    
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
        //return base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),$mergeNum, MCRYPT_MODE_ECB, $iv)));
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),$mergeNum, MCRYPT_MODE_ECB, $iv));
    
      }else{
    
        $mergeNum[0] = substr($_num, 0, $position);
        $mergeNum[1] = $_key;
        $mergeNum[2] = substr($_num, $position);
    
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
        //return base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),implode("",$mergeNum), MCRYPT_MODE_ECB, $iv)));
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($_key),implode("",$mergeNum), MCRYPT_MODE_ECB, $iv));
      }
    }
    
    // 家電解密
    public static function telDecode( $_key , $_ciphertext ){
    
      $_key = '8610';
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $encodeNum = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($_key), base64_decode($_ciphertext), MCRYPT_MODE_ECB, $iv));
    
      $idNums  = preg_split('//', $_key, -1, PREG_SPLIT_NO_EMPTY);
    
      $idSum   = 0;
      
      foreach ($idNums as $idNumk => $idNum) {
       
        $idSum += $idNum;
    
      }
    
      $position = $idSum % (mb_strlen( $encodeNum , "utf-8") - 4);
    
      if( $position == 0 ){
    
        return  substr($encodeNum, 0, -4);
    
      }else{
        $keylen = strlen($_key);
        $mergeNum[0] = substr($encodeNum, 0, $position);
        $mergeNum[1] = substr($encodeNum, ( $position+$keylen ) );
        return implode("",$mergeNum);
      }
    }    
}

?>