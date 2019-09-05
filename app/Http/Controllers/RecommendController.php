<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Support\Facades\Validator;

class RecommendController extends Controller
{   
    /* recommmand_type: 1 -> 熱銷
                        2 -> 推薦
                        3 -> 新品
    */

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
        $HotSet = DB::table('xyzs_league_recommend')->where('recommmand_type',1)->where('user_id',$LeagueId )->first();

        if( $HotSet !== NUll ){

            $HotSet = (array) $HotSet ;
            
            $HotSet['custom_sets'] = unserialize( $HotSet['custom_set'] );

            $HotSet['avoid_cat']  = unserialize( $HotSet['avoid_cat'] ); 
            
            if( !$HotSet['custom_sets'] ){

                $HotSet['custom_sets'] = [];
            }

            if( !$HotSet['avoid_cat'] ){

                $HotSet['avoid_cat'] = [];
            }

        }else{

            $HotSet = [ 'custom_sets' => [],
                        'avoid_cat'   => [],
                      ];
        }


        return view('/league_module_recommend_hot',[ 'PageTitle' => $PageTitle ,
                                                     'Categorys' => $Categorys ,
                                                     'HotSet'    => $HotSet , 
                                                     'tree' => 'modul']);
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

            }else{
                $HotArrs[$HotArrk] = trim( $HotArr );
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

        if( isset( $request->cats) ){
            
            foreach( $request->cats as $catk => $catv) {
   
                $CategoryExist = DB::table('xyzs_category')->where('cat_id',$catv)->first();

                if( $CategoryExist === NULL ){

                    $ErrorSwitch = 1;

                    array_push( $CustomErrors['category'] , "分類:".$catv."不存在");

                }            

            }
        
        }else{
            
            $request->cats = [];
        }

        if( $ErrorSwitch ){

            return back()->withErrors( $CustomErrors );

        }
        


        DB::beginTransaction();

        try {        
            
            DB::table('xyzs_league_recommend')
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




    /*
    |--------------------------------------------------------------------------
    | 推薦商品
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_recommend( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');

        $PageTitle = "推薦商品功能管理";
        
        // 取出所有分類
        $Categorys = DB::table('xyzs_category')->get();
        
        $Categorys = Lib_common::GetCategorys();
        
        // 取出設定
        $HotSet = DB::table('xyzs_league_recommend')->where('recommmand_type',2)->where('user_id',$LeagueId )->first();

        if( $HotSet !== NUll ){

            $HotSet = (array) $HotSet ;

            $HotSet['custom_sets'] = unserialize( $HotSet['custom_set']);

            $HotSet['avoid_cat']  = unserialize($HotSet['avoid_cat']);

            if( !$HotSet['custom_sets'] ){

                $HotSet['custom_sets'] = [];
            }
            
            if( !$HotSet['avoid_cat'] ){

                $HotSet['avoid_cat'] = [];
            }            

        }else{

            $HotSet = [ 'custom_sets' => [] ,
                        'avoid_cat'   => [] ,
                      ];
        }


        
        // var_dump($Categorys);
        return view('/league_module_recommend_recommend',[ 'PageTitle' => $PageTitle ,
                                                           'Categorys' => $Categorys ,
                                                           'HotSet'    => $HotSet , 
                                                           'tree' => 'modul']);
    }
    



    /*
    |--------------------------------------------------------------------------
    | 推薦商品功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_recommend_act( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');

        $NowTime =  time() - date('Z');

        // 檢查自訂熱銷商品是否存在
        $HotArrs = explode("\n", $request->custom_hot);

        foreach ($HotArrs as $HotArrk => $HotArr) {
            
            if( empty( trim($HotArr) ) ){

                unset( $HotArrs[$HotArrk] );

            }else{
                $HotArrs[$HotArrk] = trim( $HotArr );
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

        if( isset( $request->cats) ){
            foreach( $request->cats as $catk => $catv) {
    
                $CategoryExist = DB::table('xyzs_category')->where('cat_id',$catv)->first();
    
                if( $CategoryExist === NULL ){
    
                    $ErrorSwitch = 1;
    
                    array_push( $CustomErrors['category'] , "分類:".$catv."不存在");
    
                }            
    
            }
        }else{

            $request->cats = [];
        }

        if( $ErrorSwitch ){

            return back()->withErrors( $CustomErrors );

        }


        DB::beginTransaction();

        try {        
            
            DB::table('xyzs_league_recommend')
            ->updateOrInsert(
                ['user_id' => $LeagueId , 'recommmand_type' => 2 ],
                ['custom_set' =>  serialize( $HotArrs ),
                 'avoid_cat'  =>  serialize( $request->cats ),
                 'update_date' => $NowTime
                ]
            );

            DB::commit();
        
            $league_message =   [ '1',
                                  "設定推薦商品成功",
                                  [ ['operate_text'=>'回推薦商品設定介面','operate_path'=>'/league_module_recommend_recommend'] ],
                                  3
                                ];
            $request->session()->put('league_message', $league_message);                                

        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "設定推薦商品失敗",
                                  [ ['operate_text'=>'回推薦商品設定介面','operate_path'=>'/league_module_recommend_recommend'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }

        return redirect('/league_message');
    }




    /*
    |--------------------------------------------------------------------------
    | 新品模組
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_new( Request $request ){

        $LeagueId = $request->session()->get('user_id');

        $PageTitle = "新品上市功能管理";
        
        // 取出所有分類
        $Categorys = DB::table('xyzs_category')->get();
        
        $Categorys = Lib_common::GetCategorys();
        
        // 取出設定
        $HotSet = DB::table('xyzs_league_recommend')->where('recommmand_type',3)->where('user_id',$LeagueId )->first();

        if( $HotSet !== NUll ){

            $HotSet = (array) $HotSet ;
            
            $HotSet['custom_sets'] = unserialize( $HotSet['custom_set']);

            $HotSet['avoid_cat']  = unserialize($HotSet['avoid_cat']);            

            if( !$HotSet['custom_sets'] ){

                $HotSet['custom_sets'] = [];
            }
            
            if( !$HotSet['avoid_cat'] ){

                $HotSet['avoid_cat'] = [];
            }

        }else{

            $HotSet = [ 'custom_sets' => [] ,
                        'avoid_cat'   => [] ,
                      ];
        }

        return view('/league_module_recommend_new',[ 'PageTitle' => $PageTitle ,
                                                     'Categorys' => $Categorys ,
                                                     'HotSet'    => $HotSet , 
                                                     'tree' => 'modul']);
    }




    /*
    |--------------------------------------------------------------------------
    | 新品模組功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_new_act( Request $request ){
        $LeagueId = $request->session()->get('user_id');

        $NowTime =  time() - date('Z');

        // 檢查自訂熱銷商品是否存在
        $HotArrs = explode("\n", $request->custom_hot);

        foreach ($HotArrs as $HotArrk => $HotArr) {
            
            if( empty( trim($HotArr) ) ){

                unset( $HotArrs[$HotArrk] );

            }else{
                $HotArrs[$HotArrk] = trim( $HotArr );
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

        if( isset( $request->cats) ){
            
            foreach( $request->cats as $catk => $catv) {
    
                $CategoryExist = DB::table('xyzs_category')->where('cat_id',$catv)->first();
    
                if( $CategoryExist === NULL ){
    
                    $ErrorSwitch = 1;
    
                    array_push( $CustomErrors['category'] , "分類:".$catv."不存在");
    
                }            
    
            }
        }else{
            $request->cats = [];
        }

        if( $ErrorSwitch ){

            return back()->withErrors( $CustomErrors );

        }


        

        DB::beginTransaction();

        try {        
            
            DB::table('xyzs_league_recommend')
            ->updateOrInsert(
                ['user_id' => $LeagueId , 'recommmand_type' => 3 ],
                ['custom_set' =>  serialize( $HotArrs ),
                 'avoid_cat'  =>  serialize( $request->cats ),
                 'update_date' => $NowTime
                ]
            );

            DB::commit();
        
            $league_message =   [ '1',
                                  "設定新品上市成功",
                                  [ ['operate_text'=>'回新品上市設定介面','operate_path'=>'/league_module_recommend_new'] ],
                                  3
                                ];
            $request->session()->put('league_message', $league_message);                                

        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "設定新品上市失敗",
                                  [ ['operate_text'=>'回新品上市設定介面','operate_path'=>'/league_module_recommend_new'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }

        return redirect('/league_message');
    }
}
