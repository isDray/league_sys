<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DB;

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
        
        return view('league_module_banner',['PageTitle'=>$PageTitle]);
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
    public function league_module_banner_new_do( Request $request ){
        

        $league_message = ['0',"BALA BALA BALA BALA BALA",[ ['operate_text'=>'回banner功能管理',
                                                             'operate_path'=>'/league_module_banner']
                                                          ] ,5];

        $request->session()->put('league_message', $league_message);

        return redirect('/league_message');
    }
}
