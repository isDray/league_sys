<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use File;

class LeagueController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 加盟會員儀表頁面
    |--------------------------------------------------------------------------
    | 提供簡易資訊供加盟會員參考 
    |
    */
    public function index( Request $request ){

        //$request->session()->forget('user_id');

        return view('league_dashboard');
    }




    /*
    |--------------------------------------------------------------------------
    | 測試畫面
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_test( Request $request ){
        
        echo 'OMGGGG';
    }
    
    


    // 網站設定相關
    
    /*
    |--------------------------------------------------------------------------
    | 中央區塊排序
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_center( Request $request ){
        
        // 取出所有區塊
        $TmpModules = DB::table('xyzs_block')->get();
        
        // 轉換為array
        $TmpModules = json_decode( $TmpModules , true );

        $ToolModules = [];

        foreach ($TmpModules as $TmpModulek => $TmpModule ) {

            $ToolModules[ $TmpModule['id'] ] = $TmpModule['block_name'];
        }

        //var_dump($ToolModules);
        

        // 取出會員的裝央排序
        $TmpLeagueCenterSort = DB::table('xyzs_league_block_sort')->where('user_id',$request->session()->get('user_id'))->where('block_id',1)->first();
        
        if( $TmpLeagueCenterSort === NULL ){
            
            $TmpOnModules = [];

        }else{

            $TmpOnModules = unserialize( $TmpLeagueCenterSort->sort );
        }
        
        $OnModules = [];

        foreach( $TmpOnModules as $TmpOnModulek => $TmpOnModule ) {
            
            $OnModules[$TmpOnModule] = $ToolModules[$TmpOnModule] ;

            unset($ToolModules[$TmpOnModule]);
        }

        $OffModules = $ToolModules;
        
        $PageTitle = "中央區塊排序";

        return view('/league_sort_center',['OnModules' => $OnModules , 'OffModules' => $OffModules ,'PageTitle'=>$PageTitle ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 中央區塊排序實作
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_center_act( Request $request ){
        
        // 先確認資料庫是否已經有資料 , 再決定是要新增還是更新
        if( !isset($request->blocksort) || empty($request->blocksort) ){

        	$request->blocksort = [];
        }
        
        $HaveSort = DB::table('xyzs_league_block_sort')->where('user_id',$request->session()->get('user_id'))->where('block_id',1)->first();
        
        // 用新增的
        if( $HaveSort === NULL ){
            
            DB::table('xyzs_league_block_sort')->insert(
                ['user_id'  => $request->session()->get('user_id'),
                 'block_id' => 1 ,
                 'sort'     => serialize($request->blocksort)
                 ]
            );

        }else{
        // 更新
            DB::table('xyzs_league_block_sort')
            ->where('user_id', $request->session()->get('user_id'))
            ->where('block_id',1)
            ->update(['sort' => serialize($request->blocksort) ]);

        }

        return redirect('/league_sort_center');
    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理
    |--------------------------------------------------------------------------
    | 加盟會員banner管理介面
    |
    */
    public function league_module_banner( Request $request ){
        
        $PageTitle = 'banner功能管理'; 
        
        $LeagueId = $request->session()->get('user_id');

        // 依照排序從資料庫中取出加盟會員的banner圖
        $TmpBanners = DB::table('xyzs_league_banner')->where('user_id',$LeagueId.'')->where('status',1)->orderBy('sort', 'DESC')->orderBy('update_date', 'ASC')->get();

        if( count($TmpBanners) > 0 ){

            $TmpBanners = json_decode($TmpBanners,true);

        }else{

            $TmpBanners = [];
        }
        
        $banners = $TmpBanners;

        return view('league_module_banner',['PageTitle'=>$PageTitle , 'banners'=>$banners]);
    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 新增
    |--------------------------------------------------------------------------
    | 提供新增一組圖檔的介面
    |
    */
    public function league_module_banner_new( Request $request ){
        
        $PageTitle = '新增banner'; 
        
        return view('league_module_banner_new',['PageTitle'=>$PageTitle]);
    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 新增功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_banner_new_act( Request $request ){
        
        $validator = Validator::make($request->all(), 
        [
            'banner'     => 'required|mimes:jpeg,jpg,png',
            'sort'       => 'required|integer',


        ],
        [   'banner.required'=> 'banner圖片尚未選取',
            'banner.mimes'   => 'banner只接受 jpg 及 png 格式',
            'sort.integer'   => '排序只接受數字',
            'sort.required'  => '排序為必填',            


        ])->validate();
        
        $NowTime =  time() - date('Z');



        DB::beginTransaction();

        try {

            //File::makeDirectory("banner/{$request->session()->get('user_id')}");
            
            if( !file_exists( public_path('banner') ) ){

                File::makeDirectory( public_path('banner') , 755 );
            }

            if( !file_exists( public_path("banner/{$request->session()->get('user_id')}") ) ){

                File::makeDirectory( public_path("banner/{$request->session()->get('user_id')}") , 755 );

            }

            Image::make( $request->file('banner'))->resize(1280, 720)->save("banner/{$request->session()->get('user_id')}/$NowTime.{$request->banner->extension()}");

            
            DB::table('xyzs_league_banner')->insertGetId(
                [ 
                    'user_id' => $request->session()->get('user_id'),
                    'banner'  => $NowTime.".".$request->banner->extension(),
                    'sort'    => $request->sort ,
                    'status'  => 1 ,
                    'update_date'=>$NowTime
                ]
            );


            DB::commit();

            $league_message =   [ '1',
                                  "新增banner成功",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "新增banner失敗",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

            //return redirect('/register_result/0');
            // something went wrong
        }

        return redirect('/league_message');


    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 編輯
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_banner_edit( Request $request ){
        
        // 當下加盟會員的代碼
        $LeagueId = $request->session()->get('user_id');

        // 先確認banner存在 , 而且確實是當下加盟會員的banner
        $Banner = DB::table('xyzs_league_banner')->where('id',$request->id)->where('user_id',$LeagueId)->first();
        
        // 如果找不到對應的資料 , 表示不可以編輯
        if( $Banner === NULL){

            $league_message =   [ '0',
                                  "此banner不存在 , 或者不屬於您 , 請勿嘗試非法操作 。",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);

            return redirect('/league_message');
        }
        
        $Banner = (array)$Banner ;

        $PageTitle = '編輯banner'; 
        
        return view('league_module_banner_edit',['PageTitle'=>$PageTitle , 'Banner' => $Banner ]);
    }
    



    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 編輯功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_banner_edit_act( Request $request ){
        
        // 當下加盟會員的代碼
        $LeagueId = $request->session()->get('user_id');

        $validator = Validator::make($request->all(), 
        [
            'banner'     => 'nullable|mimes:jpeg,jpg,png',
            'sort'       => 'required|integer',
            'banner_id'  => 'required|exists:xyzs_league_banner,id',


        ],
        [   'banner.required'=> 'banner圖片尚未選取',
            'banner.mimes'   => 'banner只接受 jpg 及 png 格式',
            'sort.integer'   => '排序只接受數字',
            'sort.required'  => '排序為必填',
            'banner_id.required' => 'banner編號缺少',
            'banner_id.exists' => 'banner編號不存在',

        ]); 
        
        // 檢查banner 是否屬於加盟會員
        $BannerBelong = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->where('id',$request->banner_id)->first();

        if ( $validator->fails() || $BannerBelong === NULL ) {

            if( $BannerBelong === NULL ){

                $validator->errors()->add('banner_id','此banner不屬於您,請勿嘗試非法操作');
            }

            return back()->withErrors( $validator->errors() );
        }

        DB::beginTransaction();

        try {

            $NowTime =  time() - date('Z');

            $UpdateArr = [ 
                    'user_id' => $request->session()->get('user_id'),
                    'sort'    => $request->sort ,
                    'update_date'=>$NowTime
            ];

            // 先判斷是否有接收到新圖片
            if( isset( $request->banner ) ){
                
                Image::make( $request->file('banner'))->resize(1280, 720)->save("banner/{$request->session()->get('user_id')}/$NowTime.{$request->banner->extension()}");
                
                $UpdateArr['banner'] = $NowTime.".".$request->banner->extension();
            }
            
            DB::table('xyzs_league_banner')
                ->where('id', $request->banner_id)
                ->update($UpdateArr);    

            DB::commit();

            $league_message =   [ '1',
                                  "編輯banner成功",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);                   

        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "編輯banner失敗",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

            //return redirect('/register_result/0');
            // something went wrong
        }

        return redirect('/league_message');
    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 刪除
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_banner_del_act( Request $request ){

        // 當下加盟會員的代碼
        $LeagueId = $request->session()->get('user_id');

        // 檢查加盟商是否有此banner控制權限
        $BannerBelong = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->where('id',$request->banner_id)->first();
        
        if( $BannerBelong  === NULL){

            $league_message =   [ '0',
                                  "刪除banner失敗 , 此banner不屬於您 , 請勿嘗試非法操作。",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];    

            $request->session()->put('league_message', $league_message);                                         

        }else{
            
            DB::beginTransaction();

            try {



                if( file_exists( public_path("/banner/$LeagueId/$BannerBelong->banner") ) ){

                    unlink( public_path("/banner/$LeagueId/$BannerBelong->banner") );
                }

                DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->where('id',$request->banner_id)->delete();

                DB::commit();

                $league_message =   [ '1',
                                      "刪除banner成功",
                                      [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                      3
                                    ];

                $request->session()->put('league_message', $league_message);  

            }catch (\Exception $e) {
            
                DB::rollback();
            
                //var_dump($e->getMessage());
                $league_message =   [ '0',
                                      "刪除banner失敗 , 請稍後再試",
                                      [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                      3
                                    ];

                $request->session()->put('league_message', $league_message);            

            }

        }

        return redirect('/league_message');

    }




    /*
    |--------------------------------------------------------------------------
    | banner 管理 - 排序調整
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_banner_sort_act( Request $request ){
        
        // 目前時間
        $NowTime =  time() - date('Z');

        $LeagueId = $request->session()->get('user_id');

        // 確認資料庫總數量根要排序的數量相同
        $DBBanner = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->where('status',1)->get();

        if( count( $request->blocksort ) != count($DBBanner) ){
            
            $league_message =   [ '0',
                                  "banner 排序失敗 , 排序數量不符 , 請重新整理後再嘗試",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);             

            return redirect('/league_message');
        }

        // 整理排序資料


        DB::beginTransaction();

        try {
            
            foreach ( $request->blocksort  as $blocksortk => $blocksorv ) {
                
                $TmpSort = count($request->blocksort) - $blocksortk;

                DB::table('xyzs_league_banner')
                ->where('id', $blocksorv)
                ->where('user_id', $LeagueId)
                ->where('status',1)
                ->update(['sort'        =>  $TmpSort ,
                          'update_date' =>  $NowTime
                         ]);        
            }

            DB::commit();

            $league_message =   [ '1',
                                  "banner 排序成功",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "banner 排序失敗 , 請稍後再試",
                                  [ ['operate_text'=>'回banner功能管理','operate_path'=>'/league_module_banner'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

            //return redirect('/register_result/0');
            // something went wrong
        }            

        return redirect('/league_message');
    }
}
