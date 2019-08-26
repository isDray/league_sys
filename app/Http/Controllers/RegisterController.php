<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;

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
       File::put('test.html',
        view('register')
        ->render()
       );

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
}    
