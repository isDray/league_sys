<?php
namespace App\Cus_lib;
/*----------------------------------------------------------------------------------------------------
 | 新竹物流api集合 
 |----------------------------------------------------------------------------------------------------
 | 
 |
 */

class hctTool{
    
    // 由於系統總共使用兩組新竹物流帳戶 , 所以有兩組不同參數要切換使用
    public $account = [ 
                        0 =>[ 'key'=> 14 , 'iv' => 'JMXPSORU' , 'v' => '5892F0BB7CBD06A4DF545D9F54D15699' ],
                        1 =>[ 'key'=> 50 , 'iv' => 'DGCHVXFG' , 'v' => '1C629DE6B7050DC566BE64C8805FDB9A' ] 
                      ];

    
    // 驗證所需的變數
    public $key = '';

    public $iv  = '';

    public $v   = '';


    // 建構子 : 初始化所需參數
    function __construct( $_account ){

        $this->key = strtotime("+{$this->account[$_account]['key']} day", time() );

        $this->key = date("Ymd",$this->key );

        $this->iv  = $this->account[$_account]['iv'];

        $this->v   = $this->account[$_account]['v'];
        

    }


    /*----------------------------------------------------------------
     | 出貨單查詢
     |----------------------------------------------------------------
     |
     |
     */

    public function orderQuery( $_orders ){

        $searchOrders = [];
        
        // 根据接收到的出貨單型態決定如何存入 $searchOrders
        if( is_array( $_orders ) ){
            
            foreach ( $_orders as $_order ) {
                
                array_push( $searchOrders , $_order );
            }

        }else{
            
            array_push( $searchOrders , $_orders );
        }
        
        $returnDatas = [];

        // 開始進行迴圈查詢
        foreach ( $searchOrders as $searchOrder ) {
            
            // 整理出xml格式
$xml  = <<<XML
<?xml version='1.0' encoding='UTF-8' ?>
<qrylist>
<order orderid='{$searchOrder}'></order>
</qrylist>
XML;

            // 加密
            $no = $this->encrypt( $xml , $this->key ,  $this->iv );
     
            
            // 執行curl
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.hct.com.tw/phone/searchGoods_Main_Xml.ashx?no={$no}&v={$this->v}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "Postman-Token: bf52bb14-0f55-4af7-b360-aad26d4c1661",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);
            
            curl_close($curl);

            if ($err) {
                
                //echo "cURL Error #:" . $err;
                // 查詢出錯
                $returnDatas["$searchOrder"] = [ '@status' => FALSE ];


            } else {
                
                // 將curl取得的配送狀態存入tmpStatus
                $tmpStatuses = $this->decrypt( $response , $this->key ,  $this->iv ) ;
                
                // 解析xml
                $tmpStatuses = simplexml_load_string( $tmpStatuses );
                
                // 將 orders 轉換成array
                $tmpStatuses = (array)$tmpStatuses->orders;
                
                if( !array_key_exists('order', $tmpStatuses) ){
                    
                    return false;
                }
                
                $tmpStatuses = ($tmpStatuses['order']);
                
                $tmpArr = [];
                
                foreach ($tmpStatuses as $tmpStatus) {
                    
                    $tmpStatus =  (array)$tmpStatus ;

                    /*
                    echo strtotime($tmpStatus['@attributes']['wrktime']);
                    echo $tmpStatus['@attributes']['status'];
                    */

                    array_push($tmpArr, [ 'time'  => strtotime($tmpStatus['@attributes']['wrktime']),
                                          'time2' => $tmpStatus['@attributes']['wrktime'],
                                          'flow'  => $tmpStatus['@attributes']['status']
                                        ]);

                }
                

                //echo '<br><br>------------------------<br><br>';
                
                $returnDatas["$searchOrder"] = [ '@status' => TRUE  ,
                                                 '@datas'  => $tmpArr
                                               ];

                //array_push($returnDatas["$searchOrder"], $tmpArr);
                //var_dump($tmpStatus->orders);
                
            } 


        }// foreach END
        

        return $returnDatas;
        
    }




    /*----------------------------------------------------------------
     | 加解密相關方法
     |----------------------------------------------------------------
     |
     */

    public function encrypt($input, $ky, $iv) {
        $key = $ky;
        $iv = $iv;  //$iv为加解密向量
        $size = 8; //填充块的大小,单位为bite    初始向量iv的位数要和进行pading的分组块大小相等!!!
        $input = $this->pkcs5_pad($input, $size);  //对明文进行字符填充
        $td = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');    //MCRYPT_DES代表用DES算法加解密;'cbc'代表使用cbc模式进行加解密.
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);    //对$input进行加密
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);   //对加密后的密文进行base64编码
        return $data;
    }
     
    /*
     * 在采用DES加密算法,cbc模式,pkcs5Padding字符填充方式,对密文进行解密函数
     */
     
    public function decrypt($crypt, $ky, $iv) {
        $crypt = base64_decode($crypt);   //对加密后的密文进行解base64编码
        $key = $ky;
        $iv = $iv;  //$iv为加解密向量
        $td = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');    //MCRYPT_DES代表用DES算法加解密;'cbc'代表使用cbc模式进行加解密.
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $crypt);    //对$input进行解密
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $decrypted_data = $this->pkcs5_unpad($decrypted_data); //对解密后的明文进行去掉字符填充
        $decrypted_data = rtrim($decrypted_data);   //去空格
        return $decrypted_data;
    }
     
    /*
     * 对明文进行给定块大小的字符填充
     */
     
    public function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
     
    /*
     * 对解密后的已字符填充的明文进行去掉填充字符
     */
     
    public function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        return substr($text, 0, -1 * $pad);
    }

}



?>