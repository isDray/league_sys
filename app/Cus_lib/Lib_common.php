<?php
namespace App\Cus_lib;
use DB;
use Session;
use Illuminate\Support\Facades\Cookie;
/*
|--------------------------------------------------------------------------
| 通用工具
|--------------------------------------------------------------------------
| 在各個功能都有機會被使用到的工具 , 統一集中至此
|
*/
class Lib_common{

    public static  function GetCategorys(){
        
        $RootCategorys = DB::table('xyzs_category')->whereNotIn('cat_id',[42,153])->orderBy('parent_id', 'asc')->orderBy('sort_order', 'asc')->get();
        
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

    // 取指定類別的子類別
    public static  function GetSpecificCategorys( $_cat = 0 ){
        

        $Categorys = DB::table('xyzs_category')->whereNotIn('cat_id',[42,153])->where('parent_id',$_cat)->orderBy('parent_id', 'asc')->orderBy('sort_order', 'asc')->get();
        
        if( $Categorys )
        {
            $Categorys = json_decode( $Categorys , true );
        }
        
        return $Categorys;

    }




    public static function mobileEncode( $_key , $_num ){
    
      $_key = '1992';
    
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
      $_key = '1992';  
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
    
      $_key = '1992';
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
    
      $_key = '1992';
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
    



    /*----------------------------------------------------------------
     | 綠界解密
     |----------------------------------------------------------------
     |
     */
    public static function ecEncryptDecrypt($_mid, $_code, $decrypt){ 
    
        if($decrypt){ 
    
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_mid), base64_decode($_code), MCRYPT_MODE_CBC, md5(md5($_mid))), "12");
            $idNums  = preg_split('//', $_mid, -1, PREG_SPLIT_NO_EMPTY);
    
            $idSum   = 0;
      
            foreach ($idNums as $idNumk => $idNum) {
       
                $idSum += $idNum;
    
            }
    
            $position = $idSum % 16;
    
            $keylen = strlen($_mid);
            $mergeNum[0] = substr($decrypted, 0, $position);
            $mergeNum[1] = substr($decrypted, ( $position+$keylen ) );
            
            $decrypted=implode("",$mergeNum);
            
            return $decrypted;
    
        }else{ 
            $idNums  = preg_split('//', $_mid, -1, PREG_SPLIT_NO_EMPTY);
    
            $idSum   = 0;
      
            foreach ($idNums as $idNumk => $idNum) {
        
                $idSum += $idNum;
    
            }
    
            $position = $idSum % 16;
    
    
            $mergeNum[0] = substr($_code, 0, $position);
            $mergeNum[1] = $_mid;
            $mergeNum[2] = substr($_code, $position);
            
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($_mid), implode("", $mergeNum), MCRYPT_MODE_CBC, md5(md5($_mid)))); 
            return $encrypted; 
        } 
    }




    /*
    |--------------------------------------------------------------------------
    | 製作分頁
    |--------------------------------------------------------------------------
    | 1. total = 總資料量
    | 
    | 2. now_page = 當前頁面
    |
    | 3. per_page = 每頁要多少筆資料
    |
    | 4. control_num = 呈現可跳頁的數量
    |
    |
    |
    */
    public static function create_page( $target , $total , $now_page = 1, $per_page = 20 , $control_num = 3 ){
        
        $AllPage = ceil( $total / $per_page );
        //$Pages .= "<li class='paginate_button active'><a href='#''>«</a></li>";
        $Pages  = "<ul class='pagination pagination-sm no-margin pull-right'>";
        

        for ($i = $control_num ; $i > 0; $i--) { 
            
            if( $now_page - $i > 0 ){

                $Pages .= "<li class='paginate_button'><a href='".url("$target".($now_page - $i)."/$per_page")."'>".($now_page - $i)."</a></li>";
            }
        }
        
        $Pages .= "<li class='paginate_button colorbtn active '><a href='".url("$target"."$now_page/$per_page")."'>".$now_page."</a></li>";
        
        for ($j = 1; $j <= $control_num ; $j++) { 
            
            if( $now_page + $j <= $AllPage ){

                $Pages .= "<li class='paginate_button'><a href='".url("$target".($now_page + $j)."/$per_page")."'>".($now_page + $j)."</a></li>";
            }
        }

        $Pages .= "</ul>";

        return $Pages;
    }
    



    /*
    |--------------------------------------------------------------------------
    | 計算訂單總價
    |--------------------------------------------------------------------------
    |
    |
    */
    public static function _GetTotalFee( $alias = '' ){
        
        return "   {$alias}goods_amount + {$alias}tax + {$alias}shipping_fee" .
               " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee" .
               " + {$alias}card_fee ";
    }




    /*
    |--------------------------------------------------------------------------
    | 計算訂單總價
    |--------------------------------------------------------------------------
    |
    |
    */
    public static function _GetTotalFeeLDB( $alias = '' ){
        
        return "{$alias}goods_amount + {$alias}tax + {$alias}shipping_fee" .
               " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee" .
               " + {$alias}card_fee ";
    }    




    /*
    |--------------------------------------------------------------------------
    | 登入功能
    |--------------------------------------------------------------------------
    | 專門給加盟會員的會員使用的登入功能
    |
    | 1. $_password   = 使用者輸入之密碼
    | 2. $_passwordDB = 資料庫密碼
    | 3. $_salt       = salt值
    | 
    */
    public static function _MemberLogin( $_password , $_passwordDB , $_salt ){
        
        // 如果沒有salt值 , 只要直接比對md5加蜜後即可
        if( empty($_salt) ){
            
            if( md5($_password) == $_passwordDB ){

                return true;
            
            }else{

                return false;
            }
        
        // 如果有salt值, 就要添加salt後 , 兩層md5
        }else{
            
            if( md5(md5($_password.$_salt)) == $_passwordDB ){
                
                return true;

            }else{
                
                return false;
            }
        }

    }




    /*
    |--------------------------------------------------------------------------
    | 訂單狀態轉換
    |--------------------------------------------------------------------------
    |
    */
    public static function _StatusToStr( $_type , $_status ){
        
        if( $_type == 'os' ){
            
            switch ( $_status ) {
                case 0:
                    return '未確認';
                break;

                case 1:
                    return '已確認';
                break;
                case 2:
                    return '已取消';
                break;
                case 3:
                    return '無效';
                break;
                case 4:
                    return '退貨';
                break;     
                case 5:
                    return '已出貨';
                break;                                                                          
              default:
                    return '';
                break;
            }
        }

        if( $_type == 'ss' ){

            switch ( $_status ) {
                case 0:
                    return '未出貨';
                break;

                case 1:
                    return '已出貨';
                break;
                case 2:
                    return '已收貨';
                break;
                case 3:
                    return '備貨中';
                break;
                case 4:
                    return '已發貨';
                break;     
                case 5:
                    return '出貨中';
                break;                                                                          
              default:
                    return '';
                break;
            }
        }

        if( $_type == 'ps'){
            switch ( $_status ) {
                case 0:
                    return '未付款';
                break;

                case 1:
                    return '付款中';
                break;
                case 2:
                    return '已付款';
                break;                                              
                default:
                    return '';
                break;
            }
        }
    }



    /*
    |--------------------------------------------------------------------------
    | 標準時間
    |--------------------------------------------------------------------------
    |
    */
    public static function _GetGMTTime( ){
        
        return ( time() - date('Z') );
    }




    /*
    |--------------------------------------------------------------------------
    | 標準時間轉本地時間
    |--------------------------------------------------------------------------
    |
    */
    public static function _GMTToLocalTime( $_gmt , $_formate = NULL){
        
        if( empty( $_formate ) ){
            
            return ( $_gmt + date('Z') );

        }else{

            return date( $_formate, $_gmt + date('Z') );
        }
    }
    



    /*
    |--------------------------------------------------------------------------
    | 計算購物車總價( 單純購物車內商品總價 )
    |-------------------------------------------------------------------------- 
    |
    */
    public static function _getCartAmount(){
        
        $cartGoods = Session::get('cart');
        
        if( $cartGoods === null ){

            return 0;

        }else{
            
            $total = 0;

            foreach ($cartGoods as $cartGoodk => $cartGood) {
                
                $total += $cartGood['subTotal'];
            }

            return $total;
        }
    }




    /*
    |--------------------------------------------------------------------------
    | 網頁路徑連結產生器
    |--------------------------------------------------------------------------
    |
    */
    public static function _getBreadcrumb(){
        
        $breadcrumb = "<span class='breadcrumb_box'> <a href='".url('/')."' title='前往首頁' ><span class='breadcrumb_item'>首頁</span></a>";

        $currentURl = parse_url( \Request::url() );
        
        $crumb2 = '';

        if( array_key_exists('path', $currentURl) ){
            
            $crumb2 = explode('/', $currentURl['path'])[1];
        
        }
        
        // 依照url路徑的第一個值,去決定,導覽第二層要如何呈現
        if( $crumb2 == 'category' ){

            $categoryId = explode('/', $currentURl['path'])[2];

            $categoryDatas = DB::table('xyzs_category')->where('cat_id',$categoryId)->first();
            
            if( $categoryDatas != NULL ){
                // 如果有母類別就將母類別先寫進導覽中
                if( $categoryDatas->parent_id != 0){

                    $categoryMotherDatas = DB::table('xyzs_category')->where('cat_id',$categoryDatas->parent_id)->first();
                
                    if( $categoryMotherDatas != NULL){

                        $categoryMotherName = $categoryMotherDatas->cat_name;

                        $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
                        
                        $breadcrumb .= "<a href='".url("/category/{$categoryDatas->parent_id}")."' title='前往商品分類:{$categoryMotherName}'><span class='breadcrumb_item'>{$categoryMotherName}</span></a>"; 

                    }

                }

                $categoryName = $categoryDatas->cat_name;
    
                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/category/{$categoryId}")."' title='前往商品分類:{$categoryName}'><span class='breadcrumb_item'>{$categoryName}</span></a>";                

            }

        }

        if( $crumb2 == 'show_goods' ){

            $goodsId = explode('/', $currentURl['path'])[2];

            // 找出商品資料
            $goodsDatas = DB::table('xyzs_goods')->where('goods_id',$goodsId)->first();
            
            // 如果有該商品的資料才呈現導覽
            if( $goodsDatas != NULL ){
                
                $categoryDatas = DB::table('xyzs_category')->where('cat_id',$goodsDatas->cat_id)->first();
            
                if( $categoryDatas != NULL ){
                    
                    if( $categoryDatas->parent_id != 0){

                        $categoryMotherDatas = DB::table('xyzs_category')->where('cat_id',$categoryDatas->parent_id)->first();
                
                        if( $categoryMotherDatas != NULL){

                            $categoryMotherName = $categoryMotherDatas->cat_name;

                            $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
                        
                            $breadcrumb .= "<a href='".url("/category/{$categoryDatas->parent_id}")."' title='前往商品分類:{$categoryMotherName}'><span class='breadcrumb_item'>{$categoryMotherName}</span></a>"; 

                        }
          
                    }                    
                    
                    $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                    $breadcrumb .= "<a href='".url("/category/{$categoryDatas->cat_id}")."' title='前往商品分類:{$categoryDatas->cat_name}'><span class='breadcrumb_item'>{$categoryDatas->cat_name}</span></a>";
                }

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/show_goods/{$goodsId}")."' title='前往查看商品:{$goodsDatas->goods_name}'><span class='breadcrumb_item'>{$goodsDatas->goods_name}</span></a>";   
            }
        }

        if( $crumb2 == 'cart'){

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/cart")."' title='前往察看購物車'><span class='breadcrumb_item'>購物車</span></a>";             
        }

        if( $crumb2 == 'checkout'){

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/checkout")."' title='前往結帳'><span class='breadcrumb_item'>結帳頁面</span></a>";             
        }
        
        if( $crumb2 == 'join_member'){

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/join_member")."' title='加入會員'><span class='breadcrumb_item'>加入會員</span></a>";             
        }        

        if( $crumb2 == 'member_login'){

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/member_login")."' title='會員登入'><span class='breadcrumb_item'>會員登入</span></a>";             
        }      
        
        if( $crumb2 == 'new_arrival'){

                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/new_arrival")."' title='查看最新情趣商品'><span class='breadcrumb_item'>最新商品</span></a>";           

        }

        if( $crumb2 == 'search'){
                
                if( array_key_exists('keyword', \Request::all()) ){
                    
                    $searchWord = \Request::all()['keyword'];

                }else{

                    $searchWord = urldecode( explode('/', $currentURl['path'])[2] );
                }
                //$searchWord = explode('/', $currentURl['path']);
                //var_dump($searchWord);
                
                $breadcrumb .= "<span class='breadcrumb_arrow'></span>";
    
                $breadcrumb .= "<a href='".url("/search/{$searchWord}")."' title='商品搜尋-{$searchWord}'><span class='breadcrumb_item'>商品搜尋-{$searchWord}</span></a>";             
        } 
             
        

        $breadcrumb .= "</span>";

        return $breadcrumb;
    }




    /*
    |--------------------------------------------------------------------------
    | 
    |--------------------------------------------------------------------------
    |
    |
    */
    public static function _categoryRoot( $_cat_id = '' ){
        
        if( !empty($_cat_id) )
        {   
            $sw = true;
            
            $tmp_cat = $_cat_id;

            while ( $sw ) {
                
                $parent_id = DB::table('xyzs_category')->select('parent_id','cat_name')->where('cat_id', $tmp_cat )->first();
                
                if( $parent_id !== NULL )
                {   
                    
                    if( $parent_id->parent_id === 0)
                    {   
                        $sw = false;
                    }
                    else
                    {
                        $tmp_cat = $parent_id->parent_id;
                    }
                }
            }
            
            return $tmp_cat; 
        }
    }




    /*
    |--------------------------------------------------------------------------
    | 湊免運推薦
    |--------------------------------------------------------------------------
    |
    |
    */
    public static function _shipfree_recommend( $_diff_price ){
        
        $LeagueId = Session::get( 'league_id' );
        
        $sub_member = Session::get('member_id');
         
        /***
         * 優先整理出購物車內所有商品 , 必免推薦構物車內商品
         **/
        $session_car_goods = Session::get('cart');
        
        $car_goods = [];
        
        // 迴圈整理
        foreach ($session_car_goods as $session_car_goodk => $session_car_good) {

            array_push( $car_goods , $session_car_good['goodsSn'] );
        }
        
        /***
         * 整理瀏覽過且可湊滿免運的商品
         **/
        $final_viewed_goods = [];
        
        if( !empty($sub_member) )
        {   

            $viewd_goods = DB::table('xyzs_league_viewd_goods')->where('member_id', $sub_member)->first();
            
            // 如果有取到瀏覽紀錄 , 就取出還原成array
            if( $viewd_goods )
            {
                $tmp_viewed_goods = unserialize( $viewd_goods->viewed_goods );
            
                foreach ($tmp_viewed_goods as $tmp_viewed_goodk => $tmp_viewed_good) {
                    
                    $tmp_goods_datas = DB::table('xyzs_goods')->where('goods_id', $tmp_viewed_good)->select('goods_sn','goods_name','goods_thumb','goods_id',DB::raw( "ROUND(shop_price) as shop_price"))->whereNotIn('goods_sn', $car_goods )->first();

                    if( $tmp_goods_datas )
                    {
                        array_push( $final_viewed_goods , ['goods_sn'=>$tmp_goods_datas->goods_sn , 'goods_name'=>$tmp_goods_datas->goods_name , 'goods_thumb'=>$tmp_goods_datas->goods_thumb , 'goods_id'=>$tmp_goods_datas->goods_id ,'shop_price'=>$tmp_goods_datas->shop_price ] );
                    }
                }
            }
        }
        else
        {
            if( Cookie::get('viewed_goods') !== null )
            {
                $tmp_viewed_goods = Cookie::get('viewed_goods');
            
                
                foreach ($tmp_viewed_goods as $tmp_viewed_goodk => $tmp_viewed_good) {
                    
                    $tmp_goods_datas = DB::table('xyzs_goods')->where('goods_id', $tmp_viewed_good)->select('goods_sn','goods_name','goods_thumb','goods_id',DB::raw( "ROUND(shop_price) as shop_price"))->whereNotIn('goods_sn', $car_goods )->first();

                    if( $tmp_goods_datas )
                    {
                        array_push( $final_viewed_goods , ['goods_sn'=>$tmp_goods_datas->goods_sn , 'goods_name'=>$tmp_goods_datas->goods_name , 'goods_thumb'=>$tmp_goods_datas->goods_thumb , 'goods_id'=>$tmp_goods_datas->goods_id ,'shop_price'=>$tmp_goods_datas->shop_price ] );
                    }
                }

            }                         
        } 
        
        // 推薦陣列
        $recommend_arr = [];
        
        // 相差級距 , 最少為 1 ( 即100 ~ 200 )
        $need_range = ceil($_diff_price/100);
        
        $need_range = $need_range > 10 ? 10: $need_range;

        // 如果有瀏覽紀錄就先判斷瀏覽紀錄可不可以推薦
        if( count( $final_viewed_goods ) > 0)
        {
            foreach ($final_viewed_goods as $final_viewed_goodk => $final_viewed_good) {
                
                // 滿足 差額 ~ 差額+100 之間的商品
                if( $final_viewed_good['shop_price'] >= $_diff_price && $final_viewed_good['shop_price'] <= ($_diff_price+100) )
                {
                    array_push( $recommend_arr , $final_viewed_good);
                }
            }
            
            // 如果瀏覽商品能夠湊免運的 , 不足四個 , 就嘗試以加盟商設置的補足四個
            if( count($recommend_arr) < 4 )
            {   
                // 取出加盟商的湊免運推薦
                $tmp_shippings = DB::table('xyzs_league_shipping_recommend')->where('league_id',$LeagueId)->first();

                if( $tmp_shippings && $need_range > 0)
                {   
                    $need_name = "need_{$need_range}";

                    $all_needs  = unserialize( $tmp_shippings->$need_name );

                    if( count( $all_needs ) > 0)
                    {
                        $tmp_shipping_goods = DB::table('xyzs_goods')
                                        ->select( 'goods_sn','goods_name','goods_thumb','goods_id' , DB::raw( "ROUND(shop_price) as shop_price") )
                                        ->whereIn('goods_sn', $all_needs )
                                        ->whereNotIn('goods_sn', $car_goods )
                                        ->get();

                        if( $tmp_shipping_goods )
                        {
                            $tmp_shipping_goods = json_decode($tmp_shipping_goods , true);
                            
                            foreach ($tmp_shipping_goods as $tmp_shipping_goodk => $tmp_shipping_good) {
                                
                                if( count($recommend_arr) < 4)
                                {
                                    array_push( $recommend_arr , $tmp_shipping_good);
                                }
                                else
                                {
                                    break;
                                }

                            }

                        }
                    }
                }

            }
        }
        else
        {
            // 取出加盟商的湊免運推薦
            $tmp_shippings = DB::table('xyzs_league_shipping_recommend')->where('league_id',$LeagueId)->first();

            if( $tmp_shippings && $need_range > 0 )
            {   
                $need_name = "need_{$need_range}";

                $all_needs  = unserialize( $tmp_shippings->$need_name );

                if( count( $all_needs ) > 0)
                {
                    $tmp_shipping_goods = DB::table('xyzs_goods')
                                        ->select( 'goods_sn','goods_name','goods_thumb','goods_id' , DB::raw( "ROUND(shop_price) as shop_price") )
                                        ->whereIn('goods_sn', $all_needs )
                                        ->whereNotIn('goods_sn', $car_goods )
                                        ->get();

                    if( $tmp_shipping_goods )
                    {
                        $tmp_shipping_goods = json_decode($tmp_shipping_goods , true);
                            
                        foreach ($tmp_shipping_goods as $tmp_shipping_goodk => $tmp_shipping_good) {
                                
                            if( count($recommend_arr) < 4)
                            {
                                array_push( $recommend_arr , $tmp_shipping_good);
                            }
                            else
                            {
                                break;
                            }

                        }

                    }
                }
            }          
        }

        return $recommend_arr;

    }




    /*
    |--------------------------------------------------------------------------
    | sitemap 產生方法
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    /*
   <url>

      <loc>http://www.example.com/</loc>

      <lastmod>2005-01-01</lastmod>

      <changefreq>monthly</changefreq>

      <priority>0.8</priority>

   </url>
    */
    public static function makeSitemaps( $domain )
    {
        if( empty($domain) ) return;

        $str  = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $str .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
        
        //首頁
        $str .= "<url>\n";
        $str .= "<loc>https://{$domain}</loc>\n";
        $str .= "<changefreq>daily</changefreq>\n";
        $str .= "<priority>1.0</priority>\n";
        $str .= "<lastmod>".date('c', time())."</lastmod>\n";
        $str .= "</url>\n";
        
        /***
         * 最新商品
         **/
        $str .= "<url>\n";
        $str .= "<loc>https://{$domain}/new_arrival</loc>\n";
        $str .= "<changefreq>daily</changefreq>\n";
        $str .= "<priority>1.0</priority>\n";
        $str .= "<lastmod>".date('c', time())."</lastmod>\n";
        $str .= "</url>\n";

        /***
         * 加盟
         **/
        $str .= "<url>\n";
        $str .= "<loc>https://{$domain}/register</loc>\n";
        $str .= "<changefreq>daily</changefreq>\n";
        $str .= "<priority>1.0</priority>\n";
        $str .= "<lastmod>".date('c', time())."</lastmod>\n";
        $str .= "</url>\n";        
        
        /***
         * 分類頁面
         **/
        $categorys = 
        DB::table('xyzs_category as c')
        ->leftJoin('xyzs_goods as g', 'c.cat_id', '=', 'g.cat_id')
        ->where('g.goods_number','>',0)
        ->where('g.is_on_sale','=',1)
        ->whereNotNull('g.goods_id')
        ->select('c.cat_id','g.goods_id')
        //->groupBy('c.cat_id')
        ->orderBy('c.cat_id','ASC')
        ->get();
        
        $categorys = json_decode( $categorys , true );
         
        //echo count($categorys);
        $usedCategory = [];
           
        foreach ($categorys as $category) {
            
            if( !in_array($category, $usedCategory) )
            {
                $str .= "<url>\n";
                $str .= "<loc>https://{$domain}/category/{$category['cat_id']}</loc>\n";
                $str .= "<changefreq>daily</changefreq>\n";
                $str .= "<priority>1.0</priority>\n";
                $str .= "<lastmod>".date('c', time())."</lastmod>\n";
                $str .= "</url>\n";

                array_push($usedCategory, $category);
            }

        }
        
        /***
         * 商品頁面
         **/
        foreach ($categorys as $category) {
            $str .= "<url>\n";
            $str .= "<loc>https://{$domain}/show_goods/{$category['goods_id']}</loc>\n";
            $str .= "<changefreq>weekly</changefreq>\n";
            $str .= "<priority>0.8</priority>\n";
            $str .= "<lastmod>".date('c', time())."</lastmod>\n";
            $str .= "</url>\n";            
        }

        $str .= "</urlset>\n";
        
        return $str;
    }


}
?>
