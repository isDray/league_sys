<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Cus_lib\Lib_common;

class MemberController extends Controller
{
    

    public function index()
    {    
        
        
    }




    /*
    |--------------------------------------------------------------------------
    | 加盟商會員表單
    |--------------------------------------------------------------------------
    |
    */
    public function create()
    {   
        return view("member.form");
    }
    



    /*
    |--------------------------------------------------------------------------
    | 註冊會員實作
    |--------------------------------------------------------------------------
    |
    |
    */
    public function store(Request $request)
    {   

        /**
         * 表單驗證
         **/
        $LeagueId = $request->session()->get('league_id');

        $validator = Validator::make($request->all(), 
        [    
            'account'  => 'required|alpha_dash|min:6|max:12|unique:xyzs_league_member,account,NULL,id,league_id,'.$LeagueId,
            'password' => 'required|min:6|',
            'password_confirm' => 'required|min:6|same:password',
            'name'     => 'required|min:2|max:16',
            'email'    => 'required|email|unique:xyzs_league_member,email,NULL,id,league_id,'.$LeagueId,
            'phone'    => 'required|regex:/^[09]{2}[0-9]{8}$/',
            'tel'      => 'required|regex:/^[0-9]*$/',
        ],
        [   'account.required'   =>'帳號為必填',
            'account.alpha_dash' =>'帳號只能包含字母、數字、底線',
            'account.min'        =>'帳號最少要6個字',
            'account.max'        =>'帳號最多12個字',
            'account.unique'     =>'帳號已存在',
            'password.required'  =>'密碼為必填',
            'password.min'       =>'密碼最少要6個字',
            'password_confirm.required' =>'密碼確認為必填',
            'password_confirm.min'      =>'密碼確認至少要6個字',
            'password_confirm.same'     =>'密碼確認與密碼不一致',
            'name.required' =>'姓名為必填',
            'name.min'      =>'姓名最少要2個字',
            'name.max'      =>'姓名最多16個字',
            'email.required' =>'信箱為必填',
            'email.email'    =>'信箱格式錯誤',
            'email.unique'   =>'信箱已使用過',
            'phone.required' =>'手機為必填',
            'phone.regex'    =>'手機格式錯誤',
            'tel.required' =>'電話為必填',
            'tel.regex' =>'電話格式錯誤'
 


        ]);

        if ($validator->fails()) {
            
            return back()->withErrors($validator)->withInput();

        }
        
        /**
         * 確認資料無誤後開始寫入資料庫
         **/
        

        try {

            $res = DB::table('xyzs_league_member')->insert(
                [  'league_id' => $LeagueId, 
                   'account'   => $request->account,
                   'password'  => md5( $request->password ),
                   'name'   => $request->name,
                   'email'  => $request->email,
                   'salt'   => '',
                   'phone'  => Lib_common::mobileEncode('',$request->phone),
                   'tel'    => Lib_common::telEncode('',$request->tel)
                ]
            );            
            
        } catch (\Exception $e) {
            
            echo '新增失敗';
        }

        


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    



    /*
    |--------------------------------------------------------------------------
    | 加盟會員之會員登入介面
    |--------------------------------------------------------------------------
    | 
    |
    |
    */
    public function login( Request $request ){
        $request->session()->forget('member_login');
        $request->session()->forget('member_id');
        return view('member.login');
    }
    




    /*
    |--------------------------------------------------------------------------
    | 登入驗證
    |--------------------------------------------------------------------------
    |
    */
    public function member_login_act( Request $request ){
        
        /**
         * 檢查是否有確實填寫帳號密碼
         **/
        $validator = Validator::make($request->all(), 
        [    
            'account'  => 'required|alpha_dash',
            'password' => 'required|',
        ],
        [   'account.required'   =>'帳號為必填',
            'account.alpha_dash' =>'帳號只能包含字母、數字、底線',
            'password.required'  =>'密碼為必填',

        ]);

        if ($validator->fails()) {
            
            return back()->withErrors($validator)->withInput();

        }        

        // 先取出目前的經銷商會員id , 做為後需判斷用
        $LeagueId = $request->session()->get('league_id');

        // 在資料庫中找尋
        $res = DB::table('xyzs_league_member')->
                   where('league_id',$LeagueId)->
                   where('account',$request->account)->
                   first();

        if( $res == NULL ){

            return back()->withErrors( ['login'=>'登入的帳號或密碼不正確'] );

        }
        
        // 轉換成為陣列
        $res = (Array)$res;

        if( Lib_common::_MemberLogin( $request->password , $res['password'] , $res['salt'] ) ){

            // 如果登入成功且尚無salt值 , 則替其產生一組
            if( empty($res['salt']) ){
                
                $new_salt     = sprintf("%04d", rand(1,9999) );

                $new_password = md5(md5( $request->password.$new_salt ) );

                try {
                    
                    DB::table('xyzs_league_member')
                    ->where('league_id', $LeagueId)
                    ->where('account',$request->account)
                    ->update( ['password' => $new_password , 'salt' => $new_salt ] );

                } catch (\Exception $e) {
                    
                }
                
                    
            }
            
            // 登入成功給予兩個判斷用session
            $request->session()->put('member_login'  , true );
            
            $request->session()->put('member_id' , $res['id'] );
            
            if( $request->session()->get('WantTo') !== NULL ){

                $WantTo = $request->session()->get('WantTo');
                $request->session()->forget('WantTo');

                return redirect("/$WantTo");

            }else{

                return redirect('/league_dashboard');

            }

            
        }else{

            return back()->withErrors( ['login'=>'登入的帳號或密碼不正確'] );

        }
  

    }




    /*
    |--------------------------------------------------------------------------
    | 二階會員訂單查詢
    |--------------------------------------------------------------------------
    |
    |
    */
    public function member_order( Request $request ){

        echo 'ENTER';

    }





    /*
    |--------------------------------------------------------------------------
    | 檢測是帳號是否已存在
    |--------------------------------------------------------------------------
    |
    */
    public function member_account_exit( Request $request ){
        
        // 取出目前的加盟會員id
        $LeagueId = $request->session()->get('league_id');
        
        // 要確認的帳號
        $chk_account = $request->account;

        // 確認帳號是否存在
        $res = DB::table('xyzs_league_member')->where('league_id',$LeagueId)->where('account',$chk_account)->exists();
        
        echo json_encode( !$res );


    }
}
