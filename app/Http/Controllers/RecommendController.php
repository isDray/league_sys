<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


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



    /*
    |--------------------------------------------------------------------------
    | 類別推薦介面
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_category( Request $request ){
      
        $LeagueId = $request->session()->get('user_id');     
        $PageTitle = "類別商品管理功";  

        //$Categorys = DB::table('xyzs_category')->get();
        
        //$Categorys = Lib_common::GetCategorys();
        
        $child_category = [];

        // 取出資料
        $is_exist = DB::table('xyzs_league_category_recommend')->where('user_id',$LeagueId)->first();
        
        if( $is_exist && count($request->old()) == 0 )
        {   
            // 取出全部類別
            /*$tmpdatas = DB::table('xyzs_category')->select('cat_id','cat_name')->get();
            $tmpdatas = json_decode($tmpdatas,true);*/
            
            $is_exist = (array)$is_exist;
            
            

            for ($i=1; $i <= 3; $i++) { 

                $tmp_p_cate = 'p_cate'.$i;
                    
                $tmp_c_cate = 'c_cate'.$i;

                // 如果不為0 則需要算出類別
                if( $is_exist['cate_name'.$i] != 0)
                {
                    $res = DB::table('xyzs_category')->select('cat_id','cat_name','parent_id')->where('cat_id',$is_exist['cate_name'.$i])->first();
                    

                    if( $res->parent_id != 0)
                    {
                        $$tmp_p_cate = $res->parent_id;

                        $$tmp_c_cate = $res->cat_id;
                    }
                    else
                    {
                        $$tmp_p_cate = $res->cat_id;

                        $$tmp_c_cate = 0;
                    }
                    
                    if( $$tmp_p_cate != 0){ 

                        $child_category[($i-1)] = Lib_common::GetSpecificCategorys( $$tmp_p_cate );
                    }
                    else
                    {
                        $child_category[($i-1)] = [];
                    }

                }
                else
                {
                    $$tmp_p_cate = 0;
                    $$tmp_c_cate = 0;

                    $child_category[($i-1)] = [];
                }
                
                $cate_goods[$i-1] = unserialize($is_exist['cate_goods'.$i]);


            }

        }
        elseif( count($request->old()) != 0 )
        {    
            $p_cate1 = $request->old()['p_cate1'];
            $p_cate2 = $request->old()['p_cate2'];
            $p_cate3 = $request->old()['p_cate3'];
            
            $c_cate1 = $request->old()['c_cate1'];
            $c_cate2 = $request->old()['c_cate2'];
            $c_cate3 = $request->old()['c_cate3'];
            
            $child_category[0] = Lib_common::GetSpecificCategorys( $p_cate1 );
            $child_category[1] = Lib_common::GetSpecificCategorys( $p_cate2 );
            $child_category[2] = Lib_common::GetSpecificCategorys( $p_cate3 );

            $cate_goods[0] = $request->old()['goods1'];
            $cate_goods[1] = $request->old()['goods2'];
            $cate_goods[2] = $request->old()['goods3'];
        }
        else
        {   

            $p_cate1 = 0;
            $p_cate2 = 0;
            $p_cate3 = 0;

            $c_cate1 = 0;
            $c_cate2 = 0;
            $c_cate3 = 0;
            
            $cate_goods = [];
            $child_category = [];
        }

        $Categorys = Lib_common::GetSpecificCategorys();

        return view('/league_module_category_recommend',[ 'PageTitle' => $PageTitle ,
                                                          'Categorys' => $Categorys,
                                                          'child_category' => $child_category,
                                                          'p_cate1' => $p_cate1,
                                                          'p_cate2' => $p_cate2,
                                                          'p_cate3' => $p_cate3,
                                                          'c_cate1' => $c_cate1,
                                                          'c_cate2' => $c_cate2,
                                                          'c_cate3' => $c_cate3,
                                                          'cate_goods'=> $cate_goods,
                                                        ]);
        
    }
    



    /*
    |--------------------------------------------------------------------------
    | 類別推薦模組實作
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_category_act( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');        
        
        $goods_msg1 = [];
        $goods_msg2 = [];
        $goods_msg3 = [];

        // 驗證動作
        $validator = Validator::make($request->all(), 
        [ 
            'p_cate1' => 'nullable|exists:xyzs_category,cat_id',
            'p_cate2' => 'nullable|exists:xyzs_category,cat_id',
            'p_cate3' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate1' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate2' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate3' => 'nullable|exists:xyzs_category,cat_id',
        ],
        [   'p_cate1.exists' => '第一組所選的商品母分類不存在',
            'p_cate2.exists' => '第二組所選的商品母分類不存在',
            'p_cate3.exists' => '第三組所選的商品母分類不存在',
            'c_cate1.exists' => '第一組所選的商品子分類不存在',
            'c_cate2.exists' => '第二組所選的商品子分類不存在',
            'c_cate3.exists' => '第三組所選的商品子分類不存在',

        ])->validate();
        
        // 類別統驗證完，就先將類別整理出來 
        $cat1 = empty( $request->c_cate1 )? empty( $request->p_cate1 )? 0 : $request->p_cate1 : $request->c_cate1;

        $cat2 = empty( $request->c_cate2 )? empty( $request->p_cate2 )? 0 : $request->p_cate2 : $request->c_cate2;

        $cat3 = empty( $request->c_cate3 )? empty( $request->p_cate3 )? 0 : $request->p_cate3 : $request->c_cate3;
        

        $goods_msg1 = [];

                        
        for( $i = 0 ; $i < 3 ;$i++ )
        {   $tmp_arr = "goods_msg1";
            $tmp_str = '';

            for ($j=0; $j <10 ; $j++) { 
                $tmp_str = "第".($i+1)."-".($j+1)."商品，不屬於該類別";
                $goods_msg1['goods'.($i+1).".".$j.".exists"] =  $tmp_str ;
            }    
        }

        $goods_msg = $goods_msg1;
        
        $validator = Validator::make($request->all(), 
        [ 'goods1.*'=> ['nullable',
                        Rule::exists('xyzs_goods','goods_sn')->where(function ($query) use ($cat1) {
                            $query->where('cat_id', $cat1);
                        }),
                       ],
          'goods2.*'=> ['nullable',
                        Rule::exists('xyzs_goods','goods_sn')->where(function ($query) use ($cat2) {
                            $query->where('cat_id', $cat2);
                        }),
                       ],                       

          'goods3.*'=> ['nullable',
                        Rule::exists('xyzs_goods','goods_sn')->where(function ($query) use ($cat3) {
                            $query->where('cat_id', $cat3);
                        }),
                       ],                        
        ]
        ,
        $goods_msg
        )->validate(); 
        
        // 整理要輸入的資料
        $tmpdatas = DB::table('xyzs_category')->select('cat_id','cat_name')->get();
        
        $tmpdatas = json_decode($tmpdatas,true);
        
        $Categorys = [];
        
        foreach ($tmpdatas as $tmpdatak => $tmpdata) {
            
            $Categorys[$tmpdata['cat_id']] = $tmpdata['cat_name'];
        }
        
        
        $goods_arr1 = [];
        $goods_arr2 = [];
        $goods_arr3 = [];

        // 迴圈將貨號打包成文字
        foreach ($request->goods1 as $key => $value) {
            
            if( !empty($value) )
            {
                array_push($goods_arr1, trim($value) );
            }
        
        }
          
        foreach ($request->goods2 as $key => $value) {
            
            if( !empty($value) )
            {
                array_push($goods_arr2, trim($value) );
            }
        
        }
    
        foreach ($request->goods3 as $key => $value) {
            
            if( !empty($value) )
            {
                array_push($goods_arr3, trim($value) );
            }
        
        }
        

        $is_exist = DB::table('xyzs_league_category_recommend')->where('user_id',$LeagueId)->first();
        
        
        for ($i=1; $i <= 3 ; $i++) { 
            
            $tmpname = "cat{$i}";
            
            $tmpgoods = "goods_arr{$i}";
            
            /*
            if( $$tmpname == 0 )
            {
                $$tmpname = '';
            }
            else
            {
                $$tmpname = $Categorys[$$tmpname];
            }
            */

            $$tmpgoods = serialize( $$tmpgoods );
        }

        // 新增
        if( !$is_exist)
        {   
            try {
                $res = DB::table('xyzs_league_category_recommend')->insert(
                      [
                     'user_id' => $LeagueId,
                     'cate_name1'  => $cat1,
                     'cate_name2'  => $cat2,
                     'cate_name3'  => $cat3,
                     'cate_goods1' => $goods_arr1,
                     'cate_goods2' => $goods_arr2,
                     'cate_goods3' => $goods_arr3,
                    ]
                );   

                $res = 1;

            } catch (Exception $e) {

                $res = 0;
            }

        }
        // 更新
        else 
        {   
            try {
                
                $res = DB::table('xyzs_league_category_recommend')
                ->where('user_id', $LeagueId)
                ->update([
                 'cate_name1'  => $cat1,
                 'cate_name2'  => $cat2,
                 'cate_name3'  => $cat3,
                 'cate_goods1' => $goods_arr1,
                 'cate_goods2' => $goods_arr2,    
                 'cate_goods3' => $goods_arr3,
                ]);  

                $res = 1;

            } catch (Exception $e) {
                
                $res = 0;
            }


        }

        if( $res )
        {
            $league_message =   [ '1',
                                  "編輯類別推薦成功",
                                  [ ['operate_text'=>'回類別推薦管理','operate_path'=>'/league_module_recommend_category'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);  
        }
        else
        {   

            $league_message =   [ '0',
                                  "編輯類別推薦失敗",
                                  [ ['operate_text'=>'回類別推薦管理','operate_path'=>'/league_module_recommend_category'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message); 
        }

        return redirect('/league_message');
    }
}
