<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
class WebsetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 網站設定
    |--------------------------------------------------------------------------
    |
    */
    public function league_webset( Request $request ){
        
        $LeagueId  = $request->session()->get('user_id');

        $PageTitle = '網站設定';
        
        $WebData   = DB::table('xyzs_league as l')
                  -> leftJoin('xyzs_league_web as w', 'l.user_id', '=', 'w.user_id')
                  -> where('l.user_id',$LeagueId )
                  -> first();
        
        $WebData = (array)$WebData;
        
        if( empty( $WebData['back_color'] ) ){

            $WebData['back_color'] = "#ffffff";
        }
        
        // 取出配色
        $colors = DB::table('xyzs_league_color')->get();
        // 取出網站資料
        return view('league_webset',[ 'WebData' => $WebData , 'colors'=>$colors ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 網站設定實作
    |--------------------------------------------------------------------------
    |
    */
    public function league_webset_act( Request $request ){
        
        $LeagueId  = $request->session()->get('user_id');

        $validator = Validator::make($request->all(), 
        [ 
            'webname'         => 'required|',
            'webback'         => ['required','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],

            
        ],
        [   'webname.required'        => '網站名稱為必填',
            'webback.required'        => '網站背景色為必填',
            'webback.regex'           => '網站背景色格式錯誤'

        ])->validate();       

        DB::beginTransaction();

        try {   
           
            DB::table('xyzs_league')
              ->where('user_id', $LeagueId )
              ->update(['store_name' => $request->webname]);           

            DB::table('xyzs_league_web')
              ->updateOrInsert(
               [ 'user_id' => $LeagueId ],
               [ 'back_color'   => $request->webback,
                 'update_date'  => time() - date('Z') ]
            );

            DB::commit();

            $league_message =   [ '1',
                                  "網站設定成功",
                                  [ ['operate_text'=>'回網站設定介面','operate_path'=>"/league_webset"] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);                

        } catch (\Exception $e) {

            DB::rollback();
            
            $league_message =   [ '0',
                                  "網站設定失敗",
                                  [ ['operate_text'=>'回網站設定介面','operate_path'=>"/league_webset"] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }
        
        return redirect('/league_message');        
    }
}
