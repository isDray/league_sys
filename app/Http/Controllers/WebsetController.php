<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use File;
use Intervention\Image\ImageManagerStatic as Image;
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
        
        if( empty( $WebData['colorset'] ) ){

            $WebData['back_color'] = '1';
        }

        // 取出網站資料
        return view('league_webset',[ 'WebData' => $WebData ]);
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
            'webname' => 'required|',
            'webback' => 'required','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'logo'    => 'mimes:jpeg,jpg,png',
            'mlimg'   => 'mimes:jpeg,jpg,png',
            'mrimg'   => 'mimes:jpeg,jpg,png',

            
        ],
        [   'webname.required'        => '網站名稱為必填',
            'webback.required'        => '網站背景色為必填',
            'webback.regex'           => '網站背景色格式錯誤',
            'logo.mimes'              => '網站logo圖只接受 jpeg,jpg,png格式',
            'mlimg.mimes'             => '過橋頁離開圖只接受 jpeg,jpg,png格式',
            'mrimg.mimes'             => '過橋頁進入圖只接受 jpeg,jpg,png格式',

        ])->validate();       
        
        $colorsetArr = ['1','2'];

        if( !isset( $request->colorset ) || !in_array($request->colorset, $colorsetArr) ){

            $request->colorset = 1;

        }

        DB::beginTransaction();

        try {   
            
            if( isset( $request->logo ) ){
                
                if( !file_exists( public_path('league_logo') ) ){

                    File::makeDirectory( public_path('league_logo') , 755 );
                }

            
                Image::make( $request->file('logo'))->resize(180, 60)->save("league_logo/{$request->session()->get('user_id')}.{$request->logo->extension()}");
                
                DB::table('xyzs_league_web')
                ->updateOrInsert(
                    [ 'user_id' => $LeagueId ],
                    ['logo' => $request->session()->get('user_id').".".$request->logo->extension(),
                     'update_date'  => time() - date('Z') 
                    ]
                );
            }

            /**
             * 針對過橋頁圖片做處理
             **/   
            if( isset( $request->mlimg ) )
            {   
                // 如果過橋頁資料夾不存在 , 就產生一個資料夾
                if( !file_exists( public_path("over18_pic/$LeagueId") ) ){

                    File::makeDirectory( public_path("over18_pic/$LeagueId") , 755 );
                }

                Image::make( $request->file('mlimg'))->resize(384, 1080)->save("over18_pic/{$LeagueId}/ml.{$request->mlimg->extension()}");

                DB::table('xyzs_league_web')
                ->updateOrInsert(
                    [ 'user_id' => $LeagueId ],
                    ['ml' => 'ml'.".".$request->mlimg->extension(),
                     'update_date'  => time() - date('Z') 
                    ]
                );                
            }

            if( isset( $request->mrimg ) )
            {   
                // 如果過橋頁資料夾不存在 , 就產生一個資料夾
                if( !file_exists( public_path("over18_pic/$LeagueId") ) ){

                    File::makeDirectory( public_path("over18_pic/$LeagueId") , 755 );
                }

                Image::make( $request->file('mrimg'))->resize(384, 1080)->save("over18_pic/{$LeagueId}/mr.{$request->mrimg->extension()}");
                
                DB::table('xyzs_league_web')
                ->updateOrInsert(
                    [ 'user_id' => $LeagueId ],
                    ['mr' => 'mr'.".".$request->mrimg->extension(),
                     'update_date'  => time() - date('Z') 
                    ]
                );                
            }            


            DB::table('xyzs_league')
              ->where('user_id', $LeagueId )
              ->update(['store_name' => $request->webname]);           

            DB::table('xyzs_league_web')
              ->updateOrInsert(
               [ 'user_id' => $LeagueId ],
               [ 'back_color'   => $request->webback,
                 'colorset' =>  $request->colorset,
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
