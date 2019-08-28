<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |----------------------------------------------------------------
    | 註冊頁面
    |----------------------------------------------------------------
    | 提供註冊頁面 , 讓有意願參加之加盟商填寫申請表單
    |
    */

    public function index()
    {   
        return view('register');
    }
    
    


    /*
    |----------------------------------------------------------------
    | 註冊執行
    |----------------------------------------------------------------
    | 將申請者所填寫的資料再做一次檢測之後 , 開始寫入資料庫 
    |
    */
    public function register_act( Request $request ){
        
        // 所有接受的銀行
        $BankStr = '004,005,006,007,008,009,011,012,013,016,017,018,021,022,025,039,040,050,052,053,054,072,075,081,085,101,102,103,104,108,114,115,118,119,120,124,127,130,132,146,147,158,161,162,163,165,178,188,204,215,216,222,223,224,503,504,505,506,507,511,512,515,517,518,520,521,523,524,525,600,603,605,606,607,608,610,611,612,613,614,616,617,618,619,620,621,622,623,624,625,627,635,650,700,803,805,806,807,808,809,810,812,814,815,816,822,901,903,904,910,912,916,919,922,928,951,954';

        //申請表單驗證
        $validator = Validator::make($request->all(), 
        [
            'account'     => 'required|min:6|unique:xyzs_users,user_name',
            'password1'   => 'required|min:6',
            'password2'   => 'same:password1',
            'name'        => 'required',
            'phone'       => 'required|regex:/^09[0-9]{8}$/',
            'tel'         => 'required|regex:/^0[0-9]{1,3}[0-9]{7,8}$/',
            'email'       => 'required|email',
            'storename'   => 'required',
            'bank'        => "required|in:$BankStr",
            'banksub'     => 'required',
            'bankaccount' => 'required',
            'captcha' => 'required|captcha'

        ],
        [   'account.required'     => '帳號為必填',
            'account.min'          => '帳號至少需要6個字元',
            'account.unique'       => '此帳號已被使用 , 請使用其他帳號',
         
            'password1.required'   => '密碼為必填',
            'password1.min'        => '密碼至少需要6個字元',
             
            'password2.same'       => '密碼確認需與密碼一致',

            'name.required'        => '姓名為必填',

            'phone.required'       => '手機為必填',
            'phone.regex'          => '手機格式錯誤',

            'tel.required'         => '電話為必填',
            'tel.regex'            => '電話格式錯誤',

            'email.required'       => '信箱為必填',
            'email.email'          => '信箱格式錯誤',

            'storename.required'   => '加盟商店名稱為必填',

            'bank.required'        => '銀行尚未選取',
            'bank.in'              => '請選擇銀行清單中的選項',

            'banksub.required'     => '分行名稱為必填',

            'bankaccount.required' => '匯款帳號為必填',

            'captcha.required'     => '驗證碼為必填',
            'captcha.captcha'      => '驗證碼錯誤',

        ])->validate();

        
        // 開始寫入加盟商資料
        DB::beginTransaction();

        try {
            
            $newid = DB::table('xyzs_users')->insertGetId(
                ['email'        => trim( $request->email ),
                 'user_name'    => trim( $request->account ),
                 'password'     => md5( trim( $request->password1 ) ),
                 'reg_time'     => time() - date('Z'),
                 'birthday'     => '1958-01-01',
                 'last_time'    => date("Y-m-d H:i:s" , (time() - date('Z') ) ),
                 'alias'        => '',
                 'msn'          => '',
                 'qq'           => '',
                 'office_phone' => '',
                 'credit_line'  => 0,
                 'home_phone'   => $this->telEncode('', trim( $request->tel ) ),
                 'mobile_phone' => $this->mobileEncode('', trim( $request->phone ) ),
                 'name'         => trim( $request->name ),
                 'register_rank'=> 5,
                ]
            );

            DB::table('xyzs_league')->insert(
                [ 'user_id'      => $newid,
                  'store_name'   => trim( $request->storename ),
                  'bank_sn'      => trim( $request->bank ),
                  'sub_name'     => trim( $request->banksub ),
                  'bank_account' => trim( $request->bankaccount ),
                  'status'       => 0,
                  'update_time'  => time() - date('Z'),
                ]
            );


            DB::commit();

            return redirect('/register_result/1');

        } catch (\Exception $e) {
            
            DB::rollback();

            return redirect('/register_result/0');
            // something went wrong
        }
        
        
    }




    /*
    |----------------------------------------------------------------
    | ajax 帳號檢查
    |----------------------------------------------------------------
    | 提供ajax 檢查會員帳號是否已經存在於資料庫中 , 存在回應True,
    | 不存在回應false
    |
    */
    public function league_account_exist( Request $request ){
        
        $account =  $request->account ;

        $users = DB::table('xyzs_users')->where('user_name',$account)->first();

        if( $users === null ){
            
            echo json_encode( true );

        }else{

            echo json_encode( false );
        }
    }



    
    /*
    |----------------------------------------------------------------
    | 註冊結果頁面
    |----------------------------------------------------------------
    | 用來呈現註冊的結果
    |
    */
    public function register_result( Request $request ){
        
        if( $request->status ){
            
            $title = '加盟會員註冊成功';

        }else{
            
            $title = '加盟會員註冊失敗';
        }

        return view( 'register_result' , ['result' => $request->status,
                                          'title'  => $title,
                                         ] );

    }




    /*
    |----------------------------------------------------------------
    | 加解密
    |----------------------------------------------------------------
    | 包含手機加解密及電話加解密共四個方法
    |
    */
    public function mobileEncode( $_key , $_num ){
    
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
    public function mobileDecode( $_key , $_ciphertext ){
    
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
    public function telEncode( $_key , $_num ){
    
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
    public function telDecode( $_key , $_ciphertext ){
    
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
