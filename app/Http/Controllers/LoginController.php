<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 登入頁面
    |--------------------------------------------------------------------------
    |
    |
    */
    public function index(){

        $title = '登入頁面';

    	return view('login',['title'=>$title]);

    }




    /*
    |--------------------------------------------------------------------------
    | 登入驗證
    |--------------------------------------------------------------------------
    |
    */
    public function login_act( Request $request ){

    	$validator = Validator::make($request->all(), 
        [ 'account'  => 'required' ,
          'password' => 'required'
        ],
        [ 'account.required'  => '請填寫帳號',
          'password.required' => '請填寫密碼',
        ])->validate();
        
        // 開始進行登入
    }
}
