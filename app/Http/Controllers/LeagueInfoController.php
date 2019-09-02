<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Cus_lib\Lib_common;
use DB;

class LeagueInfoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 加盟會員基本資料
    |--------------------------------------------------------------------------
    |
    */
    public function league_profile_basic( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');

        $PageTitle = '基本資料設定'; 
        
        // 取出加盟會員資料
        $LeagueData = DB::table('xyzs_users as u')
                   -> leftJoin('xyzs_league as l', 'u.user_id', '=', 'l.user_id')
                   -> where('u.user_id',$LeagueId)
                   -> first();

        $LeagueData = (array)$LeagueData;

        $LeagueData['home_phone']   = Lib_common::telDecode('8610' ,  $LeagueData['home_phone'] );

        $LeagueData['mobile_phone'] = Lib_common::mobileDecode('8610' , $LeagueData['mobile_phone'] );
        
        return view('league_profile_basic',[ 'PageTitle'  => $PageTitle ,
                                             'LeagueData' => $LeagueData ,
        	                               ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 加盟會員基本資料功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_profile_basic_act( Request $request ){

        $LeagueId = $request->session()->get('user_id');

        // 驗證
        $BankStr = '004,005,006,007,008,009,011,012,013,016,017,018,021,022,025,039,040,050,052,053,054,072,075,081,085,101,102,103,104,108,114,115,118,119,120,124,127,130,132,146,147,158,161,162,163,165,178,188,204,215,216,222,223,224,503,504,505,506,507,511,512,515,517,518,520,521,523,524,525,600,603,605,606,607,608,610,611,612,613,614,616,617,618,619,620,621,622,623,624,625,627,635,650,700,803,805,806,807,808,809,810,812,814,815,816,822,901,903,904,910,912,916,919,922,928,951,954';

        $validator = Validator::make($request->all(), 
        [ 
            'name'  => 'required|',
            'phone' => 'required|regex:/^09[0-9]{8}$/',
            'tel'   => 'required|regex:/^0[0-9]{1,3}[0-9]{7,8}$/',
            'email' => 'required|email',
            'bank'  => "required|in:$BankStr",
            'banksub'     => 'required',
            'bankaccount' => 'required',            


        ],
        [   'name.required'  => '姓名為必填',
            'phone.required' => '手機為必填',
            'phone.regex'    => '手機格式錯誤',
            'tel.required'   => '電話為必填',
            'tel.regex'      => '電話格式錯誤',
            'email.required' => '信箱為必填',
            'email.email'    => '信箱格式錯誤',
            'bank.required'  => '銀行尚未選取',
            'bank.in'        => '請選擇銀行清單中的選項',
            'banksub.required'     => '分行名稱為必填', 
            'bankaccount.required' => '匯款帳號為必填', 
        ])->validate();
        
        // 寫入資料庫
        DB::beginTransaction();

        try {   
            
            DB::table('xyzs_users')
                ->where('user_id', $LeagueId)
                ->update(['name' => trim( $request->name ) ,
                          'home_phone' => Lib_common::telEncode( '8610' , $request->tel ),
                          'mobile_phone' => Lib_common::mobileEncode( '8610' , $request->phone ),
                          'email' => trim( $request->email),
                	    ]);
            
            DB::table('xyzs_league')
                ->where('user_id', $LeagueId)
                ->update(['bank_sn' => trim( $request->bank ) ,
                          'sub_name' => $request->banksub ,
                          'bank_account' => $request->bankaccount ,
                          'update_time'  =>  time() - date('Z')
                	    ]);

            DB::commit();

            $league_message =   [ '1',
                                  "設定基本資料成功",
                                  [ ['operate_text'=>'回基本資料設定介面','operate_path'=>"/league_profile_basic/{$LeagueId}"] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);   

        } catch (\Exception $e) {
            
            DB::rollback();
            
            $league_message =   [ '0',
                                  "設定基本資料失敗",
                                  [ ['operate_text'=>'回基本資料設定介面','operate_path'=>"/league_profile_basic/{$LeagueId}"] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }
        
        return redirect('/league_message');
    }
}
