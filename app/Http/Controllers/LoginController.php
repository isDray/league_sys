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
                select('u.*', 'l.status')->
                first();

        if( $user !== NULL ){
            
            // 完全正確狀態 , 才開放登入
            if( $user->register_rank == 5 && $user->user_rank == 5 && $user->status == 1 ){
                
                $LoginResult = TRUE;
            }

            // 針對尚未開通的會員 , 給予不一樣的錯誤訊息
            if( $user->register_rank == 5 && $user->user_rank == 0 && $user->status == 0 ){

                $LoginErrMsg = "登入失敗 , 您的帳戶尚未完成審核 , 審核結果將以信件通知 , 感謝您的耐心等候 。";
            }
        }

        if( !$LoginResult ){

            return back()->withErrors([$LoginErrMsg]);

        }else{

            $request->session()->put('user_id', $user->user_id );

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

        return redirect('/login');
    }
}
