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
            'tel'      => 'nullable|regex:/^[0-9]*$/',
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

            $res = true;
            $msg = "加入會員成功 , 請點下方連結進行登入";
            $next_urls = [ '/member_login'=>'前往登入頁面'
                         ];            

        } catch (\Exception $e) {
            
            //echo '新增失敗';
            $res = false;
            $msg = "加入會員過程出錯 , 請稍後再試";
            $next_urls = [ '/join_member'=>'前往加入會員頁面'
                         ];            
        }



        return view('member.msg_page',['res' => $res,
                                       'msg' => $msg,
                                       'next_urls'=> $next_urls
                                      ]);        


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
             
            /**
             * 如果登入成功且尚無salt值 , 則替其產生一組
             **/
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
            
            /**
             * 記錄登入時間點
             **/
            try {
                    
                DB::table('xyzs_league_member')
                ->where('league_id', $LeagueId)
                ->where('account',$request->account)
                ->update( ['login_time' => Lib_common::_GetGMTTime() ] );     

            } catch (\Exception $e) {
                    
            }            
      
            
            // 登入成功給予兩個判斷用session
            $request->session()->put('member_login'  , true );
            
            $request->session()->put('member_id' , $res['id'] );

            $request->session()->put('member_name' , $res['name'] );
            
            if( $request->session()->get('WantTo') !== NULL ){

                $WantTo = $request->session()->get('WantTo');
                $request->session()->forget('WantTo');

                return redirect("/$WantTo");

            }else{

                return redirect('/member_index');

            }

            
        }else{

            return back()->withErrors( ['login'=>'登入的帳號或密碼不正確'] );

        }
  

    }
    



    /*
    |--------------------------------------------------------------------------
    | 登出功能
    |--------------------------------------------------------------------------
    |
    */
    public function member_logout_act( Request $request ){
        
        $logout_msg = '';

        // 執行登出動作
        try {

            $request->session()->forget('member_login');

            $request->session()->forget('member_id');

            $request->session()->forget('member_name');
            
            $logout_res = true;

        } catch (\Exception $e) {

            $logout_res = flase;
        }

        if( $logout_res === true ){
            
            $logout_msg = '已從系統順利登出。';

        }else{
            
            $logout_msg = '登出過程出錯 ，請稍後再執行一次 ， 謝謝。';
        }

        return view('member.msg_page',['res' => $logout_res,
                                       'msg' => $logout_msg
                                      ]);
    }
   


    
    /*
    |--------------------------------------------------------------------------
    | 二階會員首頁
    |--------------------------------------------------------------------------
    |
    */
    public function member_index( Request $request ){
        
        $page_title = '會員中心入口';

        /**
         * 取出會員資料 , 呈現歡迎頁面
         **/
        $LeagueId = $request->session()->get('league_id');
        $MemberId = $request->session()->get('member_id');

        $member = DB::table('xyzs_league_member')->
                  where('league_id',$LeagueId)->
                  where('id', $MemberId)->
                  first();
       
        if( $member != null ){
            
            $member = (array)$member;

            $member['login_time'] = Lib_common::_GMTToLocalTime( $member['login_time'] , 'Y-m-d h:i:s');

        }else{

            $member = [];
        }
        
        /**
         * 計算最近三十天訂單
         **/
        $before30 = Lib_common::_GetGMTTime() - (86400 * 30);
        
        $orders_num = DB::table('xyzs_order_info')
                ->select(DB::raw('COUNT(order_id) as order_sum'))
                ->where('member_id',$MemberId)
                ->where('add_time','>=',$before30)
                ->first();
        
        
        $order_sum = $orders_num->order_sum;


        return view('member_index',[ 'page_title'=>$page_title ,
                                     'member'=>$member,
                                     'now_function' => __FUNCTION__,
                                     'order_sum' => $order_sum
                                   ]);

    }
    



    /*
    |--------------------------------------------------------------------------
    | 會員編修資料介面
    |--------------------------------------------------------------------------
    |
    */
    public function member_edit( Request $request ){

        $page_title = '個人資料';
        $LeagueId = $request->session()->get('league_id');
        $MemberId = $request->session()->get('member_id');

        $member = DB::table('xyzs_league_member')->
                  where('league_id',$LeagueId)->
                  where('id', $MemberId)->
                  first();
       
        if( $member != null ){
            
            $member = (array)$member;

            $member['login_time'] = Lib_common::_GMTToLocalTime( $member['login_time'] , 'Y-m-d h:i:s');

        }else{

            $member = [];
        }

        /**
         * 資料轉換
         **/

        $member['phone'] = Lib_common::mobileDecode( '' , $member['phone'] );

        $member['tel']   = Lib_common::telDecode( '' , $member['tel'] );

        return view('member_edit',[ 'page_title'=>$page_title ,
                                     'member'=>$member,
                                     'now_function' => __FUNCTION__
                                   ]);


    }
    


    
    /*
    |--------------------------------------------------------------------------
    | 修改私有會員的基本資料
    |--------------------------------------------------------------------------
    |
    */
    public function member_edit_detail_act( Request $request ){
        
        /**
         * 表單驗證
         **/
        $LeagueId = $request->session()->get('league_id');
        $MemberId = $request->session()->get('member_id');

        $validator = Validator::make($request->all(), 
        [    
            'name'     => 'required|min:2|max:16',
            'email'    => 'required|email|unique:xyzs_league_member,email,'.$MemberId.',id,league_id,'.$LeagueId,
            'phone'    => 'required|regex:/^[09]{2}[0-9]{8}$/',
            'tel'      => 'required|regex:/^0[0-9]*$/',
        ],
        [  
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
         * 更新基本資料
         **/
        $update_arr = [];
        $update_arr['name']  = trim( $request->name );
        $update_arr['email'] = trim( $request->email );
        $update_arr['phone'] = Lib_common::mobileEncode( '' , trim( $request->phone ) );
        $update_arr['tel']   = Lib_common::telEncode( '' , trim( $request->tel ) );


        try {

            DB::table('xyzs_league_member')
            ->where('league_id', $LeagueId)
            ->where('id',$MemberId)
            ->update(['name'  => $update_arr['name'],
                      'email' => $update_arr['email'],
                      'phone' => $update_arr['phone'],
                      'tel'   => $update_arr['tel'] 
            ]);
            
            $res = true;
            
            $msg = '會員基本資料更新成功';

        } catch (Exception $e) {

            $res = false;

            $msg = '會員基本資料攻心失敗';
        }
        
        $next_urls = [ '/member_edit'=>'回個人資料'
                     ];

        return view('member.msg_page',['res' => $res,
                                       'msg' => $msg,
                                       'next_urls'=> $next_urls
                                      ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 私有會員修改密碼
    |--------------------------------------------------------------------------
    |
    */
    public function member_edit_password_act( Request $request ){
        
        /**
         * 表單驗證
         **/
        $LeagueId = $request->session()->get('league_id');
        $MemberId = $request->session()->get('member_id');
        
        $validator = Validator::make($request->all(), 
        [   'passwordo' => 'required',
            'password'  => 'required|min:6|',
            'password_confirm' => 'required|min:6|same:password',
        ],
        [   'passwordo.required' => '原密碼為必填',
            'password.required'  =>'新密碼為必填',
            'password.min'       =>'新密碼最少要6個字',
            'password_confirm.required' =>'新密碼確認為必填',
            'password_confirm.min'      =>'新密碼確認至少要6個字',
            'password_confirm.same'     =>'新密碼確認與新密碼不一致',

        ]);
        
        $res = DB::table('xyzs_league_member')->where('league_id',$LeagueId)->where('id',$MemberId)->first();
        
        if (!Lib_common::_MemberLogin( $request->passwordo , $res->password , $res->salt )) {         
            
            $validator->after(function ($validator) {

                $validator->errors()->add('passwordo', '原密碼錯誤');
            
            });         
        }

        if ($validator->fails()) {
            
            return back()->withErrors($validator)->withInput();

        } 

        /** 
         * 更新密碼
         * 
         **/
        $update_arr = [];
        $update_arr['password']  = md5( $request->password );
        $update_arr['salt']      = "";

        try {

            DB::table('xyzs_league_member')
            ->where('league_id', $LeagueId)
            ->where('id',$MemberId)
            ->update(['password'  => $update_arr['password'],
                      'salt'      => $update_arr['salt'],
            ]);
            
            $res = true;
            
            $msg = '密碼更新成功 , 為確保您帳戶安全 , 將自動登出 , 請點選下方按鍵再次登入即可';    

            $next_urls = [ '/member_login'=>'會員登入'
                         ];                    
            
            $request->session()->forget('member_login');

            $request->session()->forget('member_id');

            $request->session()->forget('member_name');

        } catch (Exception $e) {
            
            $res = false;
            
            $msg = '密碼更新失敗';    

            $next_urls = [ '/member_edit'=>'回個人資料'
                         ];                     
            
        }



        return view('member.msg_page',['res' => $res,
                                       'msg' => $msg,
                                       'next_urls'=> $next_urls
                                      ]);        

    }




    /*
    |--------------------------------------------------------------------------
    | 二階會員訂單查詢
    |--------------------------------------------------------------------------
    | 呈現會員所有訂單
    |
    */
    public function member_order( Request $request ){

        if( empty($request->page) ){

            $request->page = 1;
        }

        if( empty($request->perpage) ){

            $request->perpage = 5;
        }


        $start = 0;

        try {
            
            /**
             * 計算總共有多少資料
             **/
            $res_num = DB::table('xyzs_order_info')
                    ->select(DB::raw('COUNT(order_id) as allres'))
                    ->where('member_id',$request->session()->get('member_id') )
                    ->first();
            

            $orders = DB::table('xyzs_order_info')
                   ->select(DB::raw('*,(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount - bonus) AS total_fee') )
                   ->where('member_id',$request->session()->get('member_id') )
                   ->orderBy('order_id','DESC')
                   ->offset( ($request->page - 1) * $request->perpage )
                   ->limit( $request->perpage )                   
                   ->get();
           
            $orders = json_decode( $orders , true );

        } catch (Exception $e) {
            
            $orders = [];
        }
        
        $pages = Lib_common::create_page( '/member_order/' , $res_num->allres , $request->page , $request->perpage );
        /** 
         * 迴圈將狀態轉換為中文
         *
         **/
        
        foreach ($orders as $orderk => $order) {

            $orders[$orderk]['os'] = Lib_common::_StatusToStr('os',$order['order_status']);
            $orders[$orderk]['ss'] = Lib_common::_StatusToStr('ss',$order['shipping_status']);
            $orders[$orderk]['ps'] = Lib_common::_StatusToStr('ps',$order['pay_status']);

        }
        
        return view('member_order',[ 'orders' => $orders ,
                                     'now_function' => __FUNCTION__,
                                     'pages'=>$pages
                                   ]);

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
