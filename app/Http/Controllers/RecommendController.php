<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Support\Facades\Validator;

class RecommendController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 測試首頁
    |--------------------------------------------------------------------------
    |
    */
    public function index( Request $request ){

    	echo 'ENTER';

    }




    /*
    |--------------------------------------------------------------------------
    | 熱銷商品
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_hot( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');

        $PageTitle = "熱銷商品功能管理";
        
        // 取出所有分類
        $Categorys = DB::table('xyzs_category')->get();
        
        $Categorys = Lib_common::GetCategorys();
        
        // 取出設定
        $HotSet = DB::table('league_recommend')->where('recommmand_type',1)->where('user_id',$LeagueId )->first();

        if( $HotSet !== NUll ){

            $HotSet = (array) $HotSet ;
        }

        $HotSet['custom_set'] = unserialize( $HotSet['custom_set']);

        $HotSet['avoid_cat']  = unserialize($HotSet['avoid_cat']);

        // var_dump($Categorys);
        return view('/league_module_recommend_hot',[ 'PageTitle' => $PageTitle ,
                                                     'Categorys' => $Categorys ,
                                                     'HotSet'    => $HotSet]);
    }




    /*
    |--------------------------------------------------------------------------
    | 熱銷商品功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_hot_act( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');

        $NowTime =  time() - date('Z');

        // 檢查自訂熱銷商品是否存在
        $HotArrs = explode("\n", $request->custom_hot);

        foreach ($HotArrs as $HotArrk => $HotArr) {
            
            if( empty( trim($HotArr) ) ){

                unset( $HotArrs[$HotArrk] );

            }

        } 

        
        // 檢查是否全部都存在資料庫中
        $CustomErrors = [ 'custom_hot' => [] ,
                          'category'   => [] ,
                        ];
        $ErrorSwitch = 0 ;

        foreach ($HotArrs as $HotArrk => $HotArr) {
            
            $GoodsExist = DB::table('xyzs_goods')->where('goods_sn',"NO.".$HotArr)->first();

            if( $GoodsExist === NULL ){

                $ErrorSwitch = 1;

                array_push( $CustomErrors['custom_hot'] , "貨號:".$HotArr."不存在");

            }
            
        }

        if( $ErrorSwitch ){

            return back()->withErrors( $CustomErrors );

        }

        foreach( $request->cats as $catk => $catv) {

            $CategoryExist = DB::table('xyzs_category')->where('cat_id',$catv)->first();

            if( $CategoryExist === NULL ){

                $ErrorSwitch = 1;

                array_push( $CustomErrors['category'] , "分類:".$catv."不存在");

            }            

        }
        

        DB::beginTransaction();

        try {        
            
            DB::table('league_recommend')
            ->updateOrInsert(
                ['user_id' => $LeagueId , 'recommmand_type' => 1 ],
                ['custom_set' =>  serialize( $HotArrs ),
                 'avoid_cat'  =>  serialize( $request->cats ),
                 'update_date' => $NowTime
                ]
            );

            DB::commit();
        
            $league_message =   [ '1',
                                  "設定熱銷商品成功",
                                  [ ['operate_text'=>'回熱銷商品設定介面','operate_path'=>'/league_module_recommend_hot'] ],
                                  3
                                ];
            $request->session()->put('league_message', $league_message);                                

        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "設定熱銷商品失敗",
                                  [ ['operate_text'=>'回熱銷商品設定介面','operate_path'=>'/league_module_recommend_hot'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

            //return redirect('/register_result/0');
            // something went wrong
        }

        return redirect('/league_message');

        
    }

}
