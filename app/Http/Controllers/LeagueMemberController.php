<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Validator;
use Session;

class LeagueMemberController extends Controller
{   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {   

        $LeagueId = $request->session()->get('user_id');
        
        /**
         * 判斷頁數參數
         **/ 
        if( empty($request->perpage) ){
            
            $request->perpage = 15;

        }

        $max = DB::table('xyzs_league_member')->where('league_id',$LeagueId)->count();
        
        $maxPage = ceil( $max / $request->perpage);

        if( empty($request->page) ){
            
            $request->page = 1;

        }else{
            
            if( $request->page > $maxPage ){

                $request->page = $maxPage;
            }
        }

        /**
         * 取出所有會員
         **/
        $LeagueMembers = DB::table('xyzs_league_member')
                         ->where('league_id',$LeagueId)
                         ->offset( (($request->page -1 ) * $request->perpage ) )
                         ->limit( $request->perpage )
                         ->get();
        
        // 產生分頁
        $pages = Lib_common::create_page('/league_member_list/', $max , $request->page , $request->perpage , 3 );

        if( count($LeagueMembers) > 0 ){
            
            $LeagueMembers = json_decode($LeagueMembers,true);

        }else{

            $LeagueMembers = [];
        }
        
        /**
         * 資料轉換
         **/
        foreach ($LeagueMembers as $LeagueMemberk => $LeagueMember) {
            $LeagueMembers[$LeagueMemberk]['phone'] = Lib_common::mobileDecode( '' , $LeagueMember['phone'] );
            $LeagueMembers[$LeagueMemberk]['tel']   = Lib_common::telDecode( '' , $LeagueMember['tel']);
            $LeagueMembers[$LeagueMemberk]['login_time'] = Lib_common::_GMTToLocalTime( $LeagueMember['login_time'] , 'Y-m-d h:i:s');
        }

        return view("league_member_list",['LeagueMembers'=>$LeagueMembers,
                                          'pages'=>$pages
                                         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request )
    {   


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Request $request )
    {
        /**
         * 檢測私有會員是不是屬於該加盟會員
         **/

        $LeagueId = $request->session()->get('user_id');
        
        $memberData = DB::table('xyzs_league_member')->where('id',$request->member_id)->first();
        
        $isSelfMember = false;

        if( $memberData != null && $LeagueId == $memberData->league_id){
            
            $isSelfMember = true;
        }
        
        if( !$isSelfMember ){

            return redirect('/league_member_list')->withErrors(['請勿嘗試非法操作']);
        }

        $memberData = (array)$memberData;

        $memberData['phone'] = Lib_common::mobileDecode ( '' , $memberData['phone'] );

        $memberData['tel']   = Lib_common::telDecode ( '' , $memberData['tel'] );

        return view( 'league_member_edit',['member'=>$memberData] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /**
         * 先確定私有會員是否為加盟會員所有
         **/

        $LeagueId = $request->session()->get('user_id');
        
        $memberData = DB::table('xyzs_league_member')->where('id',$request->member_id)->first();
        
        $isSelfMember = false;

        if( $memberData != null && $LeagueId == $memberData->league_id){
            
            $isSelfMember = true;
        }
        
        if( !$isSelfMember ){

            return redirect('/league_member_list')->withErrors(['請勿嘗試非法操作']);
        }

        $validator = Validator::make($request->all(), 
        [    
            'name'     => 'required|min:2|max:16',
            'email'    => 'required|email|unique:xyzs_league_member,email,'.$request->member_id.',id,league_id,'.$LeagueId,
            'phone'    => 'required|regex:/^[09]{2}[0-9]{8}$/',
            'tel'      => 'required|regex:/^0[0-9]*$/',
        ],
        [  
            'name.required'  =>'姓名為必填',
            'name.min'       =>'姓名最少要2個字',
            'name.max'       =>'姓名最多16個字',
            'email.required' =>'信箱為必填',
            'email.email'    =>'信箱格式錯誤',
            'email.unique'   =>'信箱已使用過',
            'phone.required' =>'手機為必填',
            'phone.regex'    =>'手機格式錯誤',
            'tel.required'   =>'電話為必填',
            'tel.regex'      =>'電話格式錯誤'
 
        ]);

        if ($validator->fails()) {
            
            return back()->withErrors($validator)->withInput();

        }         
        
        /**
         * 開始進行更新動作
         **/

        $update_arr = [];
        $update_arr['name']  = trim( $request->name );
        $update_arr['email'] = trim( $request->email );
        $update_arr['phone'] = Lib_common::mobileEncode( '' , trim( $request->phone ) );
        $update_arr['tel']   = Lib_common::telEncode( '' , trim( $request->tel ) );

        try {

            DB::table('xyzs_league_member')
                ->where('id', $request->member_id )
                ->update([
                          'name'  => $update_arr['name'] ,
                          'email' => $update_arr['email'],
                          'phone' => $update_arr['phone'],
                          'tel'   => $update_arr['tel']  ,
                        ]);

            $res = true;

        } catch (Exception $e) {
            
            $res = false;
        }

        if( $res ){
            
            Session::flash('success', "會員資料更新成功");
            return back();

        }else{

            return back()->withErrors( ['operation' => '更新過程失敗 , 請稍後再試' ])->withInput();
        }
        
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
}
