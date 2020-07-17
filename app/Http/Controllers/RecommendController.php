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
    | 類別推薦模組清單
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_category_list( Request $request ){

        $LeagueId  = $request->session()->get('user_id');
        
        $PageTitle = '類別推薦列表';

        // 取出當下加盟會員的分類推薦清單
        $cate_recommends = DB::table('xyzs_league_category_recommend')->where('league_id',$LeagueId)->get();
        
        $cate_recommends = json_decode( $cate_recommends , true);
        
        // 重新換算時間


        return view('league_module_recommend_category_list', [ 
                                                                 'PageTitle' => $PageTitle,
                                                                 'datas'     => $cate_recommends
                                                             ]);
        
    }




    /*
    |--------------------------------------------------------------------------
    | 類別推薦介面
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_category( Request $request ){
        
        // 當下加盟會員id
        $LeagueId = $request->session()->get('user_id');     
        
        $PageTitle = "類別商品管理功能";  
        
        $child_category = [];
        
        // 取出資料
        $is_exist = DB::table('xyzs_league_category_recommend')->where('league_id',$LeagueId)->where('id',$request->recommend_id)->first();
        
        $cate_recommend_id = '';

        $cate_recommend_name = '';

        $cate_recommend_des = [0=>'',1=>'',2=>''];

        if( $is_exist && count($request->old()) == 0 )
        {   
            // 取出全部類別
            /*$tmpdatas = DB::table('xyzs_category')->select('cat_id','cat_name')->get();
            $tmpdatas = json_decode($tmpdatas,true);*/
            
            $is_exist = (array)$is_exist;
            
            $cate_recommend_id = $is_exist['id'];
            $cate_recommend_name = $is_exist['title'];

            $cate_recommend_des[0] = $is_exist['cat_des1'];
            $cate_recommend_des[1] = $is_exist['cat_des2'];
            $cate_recommend_des[2] = $is_exist['cat_des3'];

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

            $cate_recommend_des[0] = $request->old()['cat_des1'];
            $cate_recommend_des[1] = $request->old()['cat_des2'];
            $cate_recommend_des[2] = $request->old()['cat_des3'];
            
            $cate_recommend_name = $request->old()['cate_recommend_name'];

            $child_category[0] = Lib_common::GetSpecificCategorys( $p_cate1 );
            $child_category[1] = Lib_common::GetSpecificCategorys( $p_cate2 );
            $child_category[2] = Lib_common::GetSpecificCategorys( $p_cate3 );

            $cate_goods[0] = $request->old()['goods1'];
            $cate_goods[1] = $request->old()['goods2'];
            $cate_goods[2] = $request->old()['goods3'];
           
            $cate_recommend_id =  $request->old()['cate_recommend_id'];


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
                                                          'cate_recommend_id'=>$cate_recommend_id,
                                                          'cate_recommend_name'=>$cate_recommend_name,
                                                          'cate_recommend_des'=>$cate_recommend_des,
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
        
        // 取得當下加盟會員ID
        $LeagueId = $request->session()->get('user_id');        
        
        $goods_msg1 = [];
        $goods_msg2 = [];
        $goods_msg3 = [];

        // 驗證動作
        $validator = Validator::make($request->all(), 
        [   'cate_recommend_name' => 'required',
            'p_cate1' => 'nullable|exists:xyzs_category,cat_id',
            'p_cate2' => 'nullable|exists:xyzs_category,cat_id',
            'p_cate3' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate1' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate2' => 'nullable|exists:xyzs_category,cat_id',
            'c_cate3' => 'nullable|exists:xyzs_category,cat_id',
        ],
        [   'cate_recommend_name.required'=>'類別推薦名稱為必填',
            'p_cate1.exists' => '第一組所選的商品母分類不存在',
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
            $tmp_str  = '';
            $tmp_str2 = '';

            for ($j=0; $j <4 ; $j++) { 
                $tmp_str  = "第".($i+1)."-".($j+1)."商品，不屬於該類別";
                $tmp_str2 = "第".($i+1)."-".($j+1)."商品，不存在";
                $goods_msg1['goods'.($i+1).".".$j.".good_class"] =  $tmp_str ;
                $goods_msg1['goods'.($i+1).".".$j.".exists"]     =  $tmp_str2 ;
            }    
        }

        $goods_msg = $goods_msg1; 
        
        $validator = Validator::make($request->all(), 
        [ 
          'goods1.*'=> "nullable|exists:xyzs_goods,goods_sn|good_class:{$cat1}",
          
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
        
        $is_exist = DB::table('xyzs_league_category_recommend')->where('league_id',$LeagueId)->where('id',$request->cate_recommend_id)->first();

        
        for ($i=1; $i <= 3 ; $i++) { 
            
            $tmpname = "cat{$i}";
            
            $tmpgoods = "goods_arr{$i}";
            
            $$tmpgoods = serialize( $$tmpgoods );
        }


        // 新增
        if( !$is_exist)
        {   
            try {
                $res = DB::table('xyzs_league_category_recommend')->insertGetId(
                      [
                     'title'=>$request->cate_recommend_name,
                     'league_id' => $LeagueId,
                     'cate_name1'  => $cat1,
                     'cate_name2'  => $cat2,
                     'cate_name3'  => $cat3,
                     'cat_des1'    => trim($request->cat_des1),
                     'cat_des2'    => trim($request->cat_des2),
                     'cat_des3'    => trim($request->cat_des3),
                     'cate_goods1' => $goods_arr1,
                     'cate_goods2' => $goods_arr2,
                     'cate_goods3' => $goods_arr3,
                     'edit_time'   => time()-date('Z'),
                    ]
                );   
                
                $returnid = $res;
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
                ->where('league_id', $LeagueId)
                ->where('id' , $request->cate_recommend_id )
                ->update([
                 'title'=>$request->cate_recommend_name,
                 'cate_name1'  => $cat1,
                 'cate_name2'  => $cat2,
                 'cate_name3'  => $cat3,
                 'cat_des1'    => trim($request->cat_des1),
                 'cat_des2'    => trim($request->cat_des2),
                 'cat_des3'    => trim($request->cat_des3),                 
                 'cate_goods1' => $goods_arr1,
                 'cate_goods2' => $goods_arr2,    
                 'cate_goods3' => $goods_arr3,
                 'edit_time'   => time()-date('Z'),
                ]); 

                $returnid = $request->cate_recommend_id;

                $res = 1;

            } catch (Exception $e) {
                
                $res = 0;
            }


        }

        if( $res )
        {
            $league_message =   [ '1',
                                  "編輯類別推薦成功",
                                  [ ['operate_text'=>'回類別推薦管理','operate_path'=>'/league_module_recommend_category/'.$returnid] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);  
        }
        else
        {   

            $league_message =   [ '0',
                                  "編輯類別推薦失敗",
                                  [ ['operate_text'=>'回類別推薦管理','operate_path'=>'/league_module_recommend_category_list'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message); 
        }

        return redirect('/league_message');
    }



    
    /*
    |--------------------------------------------------------------------------
    | 類別推薦刪除
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_category_del( Request $request ){
        
        // 當下加盟會員id
        $LeagueId = $request->session()->get('user_id');
        

        // 判斷類別推薦是否屬於當下加盟會員
        $validator = Validator::make($request->all(), 
        [ 'category_del_id'=> ['required',
                        Rule::exists('xyzs_league_category_recommend','id')->where(function ($query) use ($LeagueId) {
                            $query->where('league_id', $LeagueId);
                        }),
                       ],                       
        ]
        ,
        ['category_del_id.required'=>'移除過程有誤，請重新整理後再嘗試',
         'category_del_id.exists'  =>'此類別推薦不存在，或者無權限刪除',
        ]
        );

        // 驗證成功,可執行刪除
        if ($validator->passes())
        {
            if( DB::table('xyzs_league_category_recommend')->where('id', '=', $request->category_del_id)->where('league_id','=',$LeagueId)->delete() )
            {
                return response()->json(['success'=>'刪除成功']);
            }
            else
            {
                return response()->json(['error'=>['移除過程有誤，請重新整理後再嘗試']]);
            }
        }
        // 驗證失敗，回傳錯誤訊息
        else
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        
    }




    /*
    |--------------------------------------------------------------------------
    | 堆疊商品輪播清單
    |--------------------------------------------------------------------------
    |
    |
    */
    
    public function league_module_recommend_stack_list( Request $request ){
        
        // 加盟會員id
        $LeagueId = $request->session()->get('user_id');         

        $stacks   = DB::table('xyzs_league_stack')->select('*')->where('league_id',$LeagueId)->get();

        $stacks   = json_decode( $stacks , true );

        $PageTitle = '堆疊商品輪播管理';
              
        return view('/league_module_recommend_stack_list',[ 
                                                              'PageTitle' => $PageTitle ,
                                                              'stacks'    => $stacks
                                                          ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 堆疊商品輪播編輯(介面)
    |--------------------------------------------------------------------------
    |
    | 
    */
    public function league_module_recommend_stack_list_edit( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');  

        $PageTitle = '堆疊商品輪播編輯';

        $stack_id   = '';

        $stack_datas = [];

        if( !empty($request->stack_id) )
        {
           
            $stack_datas = DB::table('xyzs_league_stack')->where('id',$request->stack_id)->where('league_id',$LeagueId)->first();
            
            if( $stack_datas === NULL )
            {
                return redirect('/league_module_recommend_stack');
                exit;
            }
            
            $stack_id = trim( $request->stack_id );
            
            // 取出對應堆疊資料
            $stack_datas = (array)$stack_datas;

            $stack_datas['goods'] = unserialize($stack_datas['goods']);

            if( count($stack_datas['goods']) < 5 ){

                $j = 5 - count($stack_datas['goods']);
                
                for ($j ; $j > 0 ; $j--) { 

                   $stack_datas['goods'][5-$j] = '';
                }
            }

            $act = 'edit';
        }
        else
        {
            $act = 'new';
        }

        return view('/league_module_recommend_stack_edit',[   
                                                              'PageTitle'   => $PageTitle ,
                                                              'act'         => $act,
                                                              'stack_id'    => $stack_id,
                                                              'stack_datas' => $stack_datas,
                                                          ]);        

    }




    /*
    |--------------------------------------------------------------------------
    | 堆疊商品輪播編輯(功能)
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_stack_edit_act( Request $request ){
        
        // 加盟會員id
        $LeagueId = $request->session()->get('user_id');  
        
        // 製作驗證訊息array
        for( $i = 0 ; $i < 5 ;$i++ )
        {   
            $tmp_str = "第".($i+1)."筆商品,不存在或已停售";
            
            $goods_msg['goods_sn.'.($i)."."."exists"] =  $tmp_str ;
             
        }
    
        // 驗證動作
        $validator = Validator::make( $request->all() ,
        [ 
            'title' => 'required|string|max:16',
            'goods_sn.*' => ['nullable',
                            Rule::exists('xyzs_goods','goods_sn')->where(function ($query){
                                $query->where('is_on_sale', 1);
                            }),
                          ],
            'stack_id' => ['nullable',
                            Rule::exists('xyzs_league_stack','id')->where(function ($query) use ( $LeagueId ){
                                $query->where('league_id', $LeagueId);
                            }),
                          ],
        ],
        [   'title.required' => '堆疊標題為必填',
            'title.string'   => '堆疊標題必須為字串',
            'title.max'      => '堆疊標題最多16個字',
            'stack_id.exists'=> '此堆疊商品不存在',
        ]+$goods_msg)->validate();   
        
        // 整理能寫入的sn
        $editGoodsSn = [];

        foreach ($request->goods_sn as $key => $value) {
            
            if( !empty($value) )
            {
                array_push($editGoodsSn, trim($value) );
            }
        
        }        
        
        $editGoodsSn = serialize( $editGoodsSn );

        // 寫入資料庫 , 如果有指定id 表示要執行更新
        if( !empty( $request->stack_id ) )
        {
            try {
                
                $res = DB::table('xyzs_league_stack')
                ->where('id', $request->stack_id)
                ->update([
                    'title'     => $request->title,
                    'note'      => '',
                    'goods'     => $editGoodsSn,
                    'edit_time' => time() - date('Z'),
                ]);  

                $res = 1;

            } catch (Exception $e) {
                
                $res = 0;
            }
        }
        // 無指定編號 , 表示為新增
        else
        {
            try {
                $res = DB::table('xyzs_league_stack')->insert(
                      [
                     'league_id' => $LeagueId,
                     'title'     => $request->title,
                     'note'      => '',
                     'goods'     => $editGoodsSn,
                     'edit_time' => time() - date('Z'),
                    ]
                );   

                $res = 1;

            } catch (Exception $e) {

                $res = 0;
            }
        }

        if( $res )
        {
            $league_message =   [ '1',
                                  "編輯堆疊商品推薦成功",
                                  [ ['operate_text'=>'回堆疊商品管理','operate_path'=>'/league_module_recommend_stack'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);  
        }
        else
        {   

            $league_message =   [ '0',
                                  "編輯堆疊商品推薦成功",
                                  [ ['operate_text'=>'回堆疊商品管理','operate_path'=>'/league_module_recommend_stack'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message); 
        }        

        return redirect('/league_message');
        //var_dump( $request->all() );
    }




    /*
    |--------------------------------------------------------------------------
    | 堆疊推薦刪除功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_stack_del( Request $request ){

        // 當下加盟會員id
        $LeagueId = $request->session()->get('user_id');
        

        // 判斷類別推薦是否屬於當下加盟會員
        $validator = Validator::make($request->all(), 
        [ 'stack_del_id'=> ['required',
                        Rule::exists('xyzs_league_stack','id')->where(function ($query) use ($LeagueId) {
                            $query->where('league_id', $LeagueId);
                        }),
                       ],                       
        ]
        ,
        ['stack_del_id.required'=>'移除過程有誤，請重新整理後再嘗試',
         'stack_del_id.exists'  =>'此堆疊推薦不存在，或者無權限刪除',
        ]
        );

        // 驗證成功,可執行刪除
        if ($validator->passes())
        {
            if( DB::table('xyzs_league_stack')->where('id', '=', $request->stack_del_id)->where('league_id','=',$LeagueId)->delete() )
            {
                return response()->json(['success'=>'刪除成功']);
            }
            else
            {
                return response()->json(['error'=>['移除過程有誤，請重新整理後再嘗試']]);
            }
        }
        // 驗證失敗，回傳錯誤訊息
        else
        {   
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }
    



    /*
    |--------------------------------------------------------------------------
    | 客製化廣告商品列表
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_custom_ad_list( Request $request ){
        
        // 加盟會員id
        $LeagueId = $request->session()->get('user_id');         

        $stacks   = DB::table('xyzs_league_custom_ad')->select('*')->where('league_id',$LeagueId)->get();

        $stacks   = json_decode( $stacks , true );

        $PageTitle = '客製推薦商品管理';
              
        return view('/league_module_recommend_custom_ad_list',[ 
                                                              'PageTitle' => $PageTitle ,
                                                              'stacks'    => $stacks
                                                          ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 客製化廣告商品編輯
    |--------------------------------------------------------------------------
    |
    |
    */
    public function league_module_recommend_custom_ad_edit( Request $request ){

        $LeagueId = $request->session()->get('user_id');  

        $PageTitle = '客製推薦商品編輯';

        $id   = '';

        $datas = [];
        
        // 如果沒有給id 
        if( !empty($request->id) )
        {   
           
            $datas = DB::table('xyzs_league_custom_ad')->where('id',$request->id)->where('league_id',$LeagueId)->first();
            

            if( $datas === NULL )
            {
                return redirect('/league_module_recommend_custom_ad');
                exit;
            }
            
            $id = trim( $request->id );
            
            // 取出對應堆疊資料
            $datas = (array)$datas;

            $act = 'edit';
        }
        else
        {
            $act = 'new';
        }

        return view('/league_module_recommend_custon_ad_edit',[   
                                                              'PageTitle'   => $PageTitle ,
                                                              'act'         => $act,
                                                              'id'          => $id,
                                                              'datas'       => $datas,
                                                          ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 客製化廣告商品編輯功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_custom_ad_edit_act( Request $request ){
        
        // 加盟會員id
        $LeagueId = $request->session()->get('user_id');  

        
        // 製作驗證訊息array
        /*
        for( $i = 0 ; $i < 5 ;$i++ )
        {   
            $tmp_str = "第".($i+1)."筆商品,不存在或已停售";
            
            $goods_msg['goods_sn.'.($i)."."."exists"] =  $tmp_str ;
             
        }
        */

        // 驗證動作
        $validator = Validator::make( $request->all() ,
        [ 
            'title' => 'required|string|max:16',
            'goods_sn' => ['required',
                            Rule::exists('xyzs_goods','goods_sn')->where(function ($query){
                                $query->where('is_on_sale', 1);
                            }),
                          ],
            'descript' =>'required|string|max:128',
            'link'     =>'required|url',
            'bgcolor' =>'required|in:1,2,3,4,5,6',
            'animate' =>'required|in:1,2',
            'rorl'    =>'required|in:1,2',
            'id' => ['nullable',
                            Rule::exists('xyzs_league_custom_ad','id')->where(function ($query) use ( $LeagueId ){
                                $query->where('league_id', $LeagueId);
                            }),
                          ],
        ],
        [   'title.required'    => '標題為必填',
            'title.string'      => '標題必須為字串',
            'title.max'         => '標題最多16個字',
            'descript.required' => '簡述為必填',
            'descript.max'      => '簡述最多為128個字',
            'descript.string'   => '簡述必須為字串',
            'link.required'     => '推薦連結為必填',
            'link.url'          => '推薦連結必須為完整網址',
            'goods_sn.required' => '商品編號為必填',
            'goods_sn.exists'   => '商品停售或者不存在',
            'bgcolor.required'  => '背景色必須選擇',
            'bgcolor.in'        => '背景色不存在',
            'animate.required'  => '動畫類型必須選擇',
            'animate.in'        => '動畫類型不存在',
            'rorl.required'     => '圖像位置為必選',
            'rorl.in'           => '圖像位置值錯誤',
            'id.exists'         => '客製推薦不存在',

            //'stack_id.exists'=> '此堆疊商品不存在',
        ])->validate();   
        
        // 整理能寫入的sn
        /*$editGoodsSn = [];

        foreach ($request->goods_sn as $key => $value) {
            
            if( !empty($value) )
            {
                array_push($editGoodsSn, trim($value) );
            }
        
        }        
        
        $editGoodsSn = serialize( $editGoodsSn );*/
        
        $editGoodsSn = trim( $request->goods_sn );


        // 寫入資料庫 , 如果有指定id 表示要執行更新
        if( !empty( $request->id ) )
        {
            try {
                
                $res = DB::table('xyzs_league_custom_ad')
                ->where('id', $request->id)
                ->update([
                     'title'     => $request->title,
                     'goods_sn'  => $editGoodsSn,
                     'background-color'=>$request->bgcolor,
                     'animate'         =>$request->animate,
                     'descript'        =>$request->descript,
                     'link'            =>$request->link,    
                     'rorl'            =>$request->rorl,
                     'edit_time' => time() - date('Z'),
                ]);  
                
                $res = 1;

            } catch (Exception $e) {
                
                $res = 0;
            }
        }
        // 無指定編號 , 表示為新增
        else
        {
            try {
                
                $res = DB::table('xyzs_league_custom_ad')->insert(
                      [
                     'league_id' => $LeagueId,
                     'title'     => $request->title,
                     'goods_sn'  => $editGoodsSn,
                     'background-color'=>$request->bgcolor,
                     'animate'         =>$request->animate,
                     'descript'        =>$request->descript,
                     'link'            =>$request->link,
                     'rorl'            =>$request->rorl,
                     'edit_time' => time() - date('Z'),
                    ]
                );   
                
                $res = 1;

            } catch (Exception $e) {

                $res = 0;
            }
        }

        if( $res )
        {
            $league_message =   [ '1',
                                  "編輯堆疊商品推薦成功",
                                  [ ['operate_text'=>'回堆疊商品管理','operate_path'=>'/league_module_recommend_custom_ad'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);  
        }
        else
        {   

            $league_message =   [ '0',
                                  "編輯堆疊商品推薦成功",
                                  [ ['operate_text'=>'回堆疊商品管理','operate_path'=>'/league_module_recommend_custom_ad'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message); 
        }        

        return redirect('/league_message');
        //var_dump( $request->all() );
    }




    /*
    |--------------------------------------------------------------------------
    | 客製化廣告商品刪除
    |--------------------------------------------------------------------------
    |
    */
    public function league_module_recommend_custom_gad_del( Request $request ){
        // 當下加盟會員id
        $LeagueId = $request->session()->get('user_id');
        

        // 判斷類別推薦是否屬於當下加盟會員
        $validator = Validator::make($request->all(), 
        [ 'custom_ad_id'=> ['required',
                        Rule::exists('xyzs_league_custom_ad','id')->where(function ($query) use ($LeagueId) {
                            $query->where('league_id', $LeagueId);
                        }),
                       ],                       
        ]
        ,
        ['custom_ad_id.required'=>'移除過程有誤，請重新整理後再嘗試',
         'custom_ad_id.exists'  =>'此客製化商品推薦不存在，或者無權限刪除',
        ]
        );
     
        // 驗證成功,可執行刪除
        if ($validator->passes())
        {
            if( DB::table('xyzs_league_custom_ad')->where('id', '=', $request->custom_ad_id)->where('league_id','=',$LeagueId)->delete() )
            {
                return response()->json(['success'=>'刪除成功']);
            }
            else
            {
                return response()->json(['error'=>['移除過程有誤，請重新整理後再嘗試']]);
            }
        }
        // 驗證失敗，回傳錯誤訊息
        else
        {   
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }
}
