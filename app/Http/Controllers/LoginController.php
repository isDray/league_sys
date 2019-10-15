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
    public function index( Request $request ){
        
        // $request->session()->get('user_id');
        // $request->session()->forget('user_id');
        
        //var_dump( $request->session()->get('user_id') );

        // 如果已經有登入狀態了 , 就直接將其轉跳進入後台
        if( !empty($request->session()->get('user_id')) ){

            return redirect('/league_dashboard');
        }

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
        
        // 預設訊息
        $LoginResult = FALSE;
        $LoginErrMsg = "登入失敗 , 請確定帳號密碼輸入無誤 , 如果您尚未申請加盟會員帳號 , 請點擊下方連結申請 。";

        // 開始進行登入
        $user = DB::table('xyzs_users AS u')->
                leftJoin('xyzs_league AS l', 'u.user_id', '=', 'l.user_id')->
                where('user_name', $request->account )->
                where('password', md5( $request->password ) )->
                where('user_rank' , 5 )->
                select('u.*', 'l.status')->
                first();

        if( $user !== NULL ){
            

            // 如果已經為停用 , 就不需要再
            if( $user->able == 0){
                
                $LoginErrMsg = "登入失敗 , 此帳號已被暫停使用 , 如有任何疑問請洽客服 。";

            }elseif( $user->able == 1 ){

                // 沒有停用才判斷是否能夠登入     
                $LoginResult = TRUE;

            }


        }

        if( !$LoginResult ){

            return back()->withErrors([$LoginErrMsg]);

        }else{

            $request->session()->put('user_id', $user->user_id );

            $request->session()->put('login'  , true );

            if( $request->session()->get('WantTo') !== NULL ){

                $WantTo = $request->session()->get('WantTo');
                $request->session()->forget('WantTo');

                return redirect("/$WantTo");

            }else{

                return redirect('/league_dashboard');

            }

        }
    }




    /*
    |--------------------------------------------------------------------------
    | 登出功能
    |--------------------------------------------------------------------------
    |
    |
    */
    public function logout_act( Request $request ){
        
        // 清除登入session後 , 轉跳到登入頁面
        $request->session()->forget('user_id');
        $request->session()->forget('login');
        
        return redirect('/login');
    }
}
