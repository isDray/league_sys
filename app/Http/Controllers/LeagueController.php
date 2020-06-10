<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use File;
use App\Cus_lib\Lib_common;


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
        
        $LeagueId = $request->session()->get('user_id');
     
        $MonthStart = strtotime( date('Y-m-01') ) -  date('Z');
        
        $Before7Day = time() -  date('Z') - ( 7 * 24 * 60 * 60 );

        $MonthEnd   =  strtotime( date('Y-m-t 23:59:59', strtotime('now')) ) - date('Z');
        
        // 取出訂單數
        $Orders = DB::table('xyzs_order_info')
        ->where('league',$LeagueId)
        ->where('add_time','>=',$MonthStart)
        ->where('add_time','<=',$MonthEnd)
        ->get();
        
        $OrderNum = count( $Orders );
        
        
        // 取出完成訂單數
        $DoneOrders = DB::table('xyzs_order_info')
        ->where('league',$LeagueId)
        ->where('add_time','>=',$MonthStart)
        ->where('add_time','<=',$MonthEnd)
        /*->where(function( $query ) use ($Before7Day){
            
            $query->where(function($quey2) use ($Before7Day){
                $quey2->where('order_status','5')
                ->where('shipping_status','1')
                ->where('pay_status','2')
                ->where('shipping_time' ,'<' , $Before7Day);
            })
            ->orWhere(function($query3){
                $query3->where('order_status','5')
                ->where('shipping_status','2')
                ->where('pay_status','2');
            });
        })*/
        ->where('order_status','5')
        ->where('shipping_status','2')
        ->where('pay_status','2')
        ->get();
        
        $DoneOrders = count( $DoneOrders );
        
        // 未領取獎金計算
        $Divides = DB::table('xyzs_order_info')
        ->select(DB::raw("shipping_fee , (".Lib_common::_GetTotalFee().") as total_fee"))
        ->where('league',$LeagueId)
        ->where('league_pay',0)
        // ->where(function( $query ) use ($Before7Day){
            
        //     $query->where(function($quey2) use ($Before7Day){
        //         $quey2->where('order_status','5')
        //         ->where('shipping_status','1')
        //         ->where('pay_status','2')
        //         ->where('shipping_time' ,'<' , $Before7Day);
        //     })
        //     ->orWhere(function($query3){
        //         $query3->where('order_status','5')
        //         ->where('shipping_status','2')
        //         ->where('pay_status','2');
        //     });
        // })
        ->where('order_status','5')
        ->where('shipping_status','2')
        ->where('pay_status','2')        
        ->get(); 
        
        $Divides = json_decode( $Divides , true );
        
        $Acumulation = 0;
        
        foreach ($Divides as $Dividek => $Divide) {

            $Acumulation += round( ( $Divide['total_fee'] ) * 0.2 );

        }
        $Acumulation =  $Acumulation;
        
        /* 
        |--------------------------------------------------------------------------
        | 繪製本月每日訂單圖示
        |
        |
        */
        $ThisMonthDays = date("t");
        
        $MonthDays = [];

        for( $i=1 ; $i <= $ThisMonthDays ; $i++ ){
            
            //array_push($MonthDays, $i);
            $MonthDays["$i"] = 0;

        } 

        $MonthDay2s = $MonthDays;

        $MonthDayCommissions = $MonthDays;

        // 每日訂單數
        $PerDayOrders = DB::select('SELECT DAY(FROM_UNIXTIME(add_time+28800)) as order_day, COUNT(order_id) as day_order ,order_id 
                               FROM xyzs_order_info WHERE league = :league 
                               AND add_time >= :MonthStart
                               AND add_time <= :MonthEnd
                               GROUP BY DAY(FROM_UNIXTIME(add_time + 28800))', ['league' => $LeagueId , 'MonthStart'=>$MonthStart , 'MonthEnd'=>$MonthEnd]);
        
        
        foreach ($PerDayOrders as $PerDayOrderk => $PerDayOrder) {

            if( array_key_exists($PerDayOrder->order_day , $MonthDays) ){

                $MonthDays[$PerDayOrder->order_day] = $PerDayOrder->day_order;

            }
        }
        

        // 每日完成訂單數
        $PerDayDoneOrders = DB::select('SELECT DAY(FROM_UNIXTIME(add_time+28800)) as order_day, COUNT(order_id) as day_order , SUM( ROUND( ('.Lib_common::_GetTotalFee().')*0.2 )) as commission
                               FROM xyzs_order_info WHERE league = :league 
                               AND add_time >= :MonthStart
                               AND add_time <= :MonthEnd
                               AND (order_status = 5 AND shipping_status = 2 AND pay_status = 2)
                               GROUP BY DAY(FROM_UNIXTIME(add_time + 28800))', ['league' => $LeagueId , 'MonthStart'=>$MonthStart , 'MonthEnd'=>$MonthEnd ]);
       
        foreach ($PerDayDoneOrders as $PerDayDoneOrderk => $PerDayDoneOrder) {

            if( array_key_exists($PerDayDoneOrder->order_day , $MonthDay2s) ){

                $MonthDay2s[$PerDayDoneOrder->order_day] = $PerDayDoneOrder->day_order;

            }

            if( array_key_exists($PerDayDoneOrder->order_day, $MonthDayCommissions ) ){

                $MonthDayCommissions[$PerDayDoneOrder->order_day] = $PerDayDoneOrder->commission;
            }
        }        
        


        $MonthDayOrders = json_encode( array_values($MonthDays) ); 

        $MonthDayOrders2 = json_encode( array_values($MonthDay2s) ); 

        $MonthDays = json_encode( array_keys( $MonthDays ));
        

        /*
        |--------------------------------------------------------------------------
        | 繪製本月訂單完成比例圖
        |
        |
        */
        $PercentUndone = 0;
        $PercentDone   = 0;
        

        foreach ( json_decode( $MonthDayOrders , true) as $MonthDayOrder) {
            
            $PercentUndone += $MonthDayOrder;

        }

        foreach ( json_decode( $MonthDayOrders2 , true) as $MonthDayOrder2) {
            
            $PercentDone += $MonthDayOrder2;
            
        }        
        
        $PercnetStatus = json_encode( [( $PercentUndone - $PercentDone ), $PercentDone ] );
        

        /*
        |--------------------------------------------------------------------------
        | 繪製獎金成長圖
        |
        |
        */
        foreach ($MonthDayCommissions as $MonthDayCommissionk => $MonthDayCommission ) {
            
            if( array_key_exists($MonthDayCommissionk-1, $MonthDayCommissions) ){
                
                // if( date('d') >= $MonthDayCommissionk ){
                    $MonthDayCommissions[$MonthDayCommissionk] = $MonthDayCommissions[$MonthDayCommissionk-1] + $MonthDayCommission;
                // }

            }

        }
         
        $MonthDayCommissions = json_encode( array_values($MonthDayCommissions) );
        
        
        /*
        |--------------------------------------------------------------------------
        | 繪製重點銷售類別
        |
        |
        */
        $allDones =DB::select('SELECT oi.order_id
                               FROM xyzs_order_info as oi 
                               WHERE oi.league = :league 
                               AND oi.add_time >= :MonthStart
                               AND oi.add_time <= :MonthEnd
                               AND (order_status = 5 AND shipping_status = 2 AND pay_status = 2)'
                               , ['league' => $LeagueId , 'MonthStart'=>$MonthStart , 'MonthEnd'=>$MonthEnd] );
        
        $allOrderIds = [];
       
        foreach ($allDones as $allDonek => $allDone) {

            array_push( $allOrderIds , $allDone->order_id );
        }

        $allOrderGoods =  DB::table('xyzs_order_goods as og')
                       -> select('og.goods_id','g.cat_id',DB::raw('SUM(og.goods_number) as cat_num'),'c.cat_name')
                       -> leftJoin('xyzs_goods as g', 'og.goods_id', '=', 'g.goods_id')
                       -> leftJoin('xyzs_category as c', 'g.cat_id', '=', 'c.cat_id')
                       -> whereIn('order_id',$allOrderIds)
                       -> groupBy('g.cat_id')
                       -> get();
        
        // 轉換成為陣列
        $allOrderGoods = json_decode( $allOrderGoods , true );
        
        // 整理成繪圖陣列
        $RadarCatNames = [];
        $RadarCatNums  = [];

        foreach ( $allOrderGoods as $allOrderGoodk => $allOrderGood ) {
            array_push( $RadarCatNames , $allOrderGood['cat_name'] );

            array_push( $RadarCatNums  , $allOrderGood['cat_num'] );
        }
        
        // 轉換成json
        $RadarCatNames = json_encode( array_values($RadarCatNames) , true );


        $RadarCatNums  = json_encode( array_values($RadarCatNums) , true);

        return view('league_dashboard' , ['OrderNum'    => $OrderNum,
                                          'DoneOrders'  => $DoneOrders,
                                          'Acumulation' => $Acumulation,
                                          // 每日圖表
                                          'MonthDayOrders' => $MonthDayOrders,
                                          'MonthDays'   => $MonthDays,
                                          'MonthDayOrders2'=>$MonthDayOrders2,
                                          // 完成比例圖表
                                          'PercnetStatus' =>$PercnetStatus,
                                          // 本月獎金累積
                                          'MonthDayCommissions'=>$MonthDayCommissions,
                                          // 重點銷售圖表
                                          'RadarCatNames' => $RadarCatNames,
                                          'RadarCatNums'  => $RadarCatNums,
                                         ]);
    }
    
    


    // 網站設定相關
    
    /*
    |--------------------------------------------------------------------------
    | 中央區塊排序
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_center( Request $request ){

        // 取出所有中央區塊
        $TmpModules = DB::table('xyzs_league_block')->where('block_area','center')->get();
        
        // 轉換為array
        $TmpModules = json_decode( $TmpModules , true );
        
        $ToolModules = [];
        
        $ExtensionModules = [];

        foreach ($TmpModules as $TmpModulek => $TmpModule ) {
             
            
            // 判斷是否為擴張模組,再分別放入不同陣列
            if( $TmpModule['extension'] == 0 )
            {
                $ToolModules[ $TmpModule['id'] ]['block_name']      = $TmpModule['block_name'];
                $ToolModules[ $TmpModule['id'] ]['edit_route_name'] = $TmpModule['edit_route_name'];
            }
            else
            {
                $ExtensionModules[ $TmpModule['id'] ]['id'] = $TmpModule['ex_table'];
                $ExtensionModules[ $TmpModule['id'] ]['edit_route_name'] = $TmpModule['edit_route_name'];

            }
        }

        // 如果有擴張模組則需要將其重新組合
        if( count($ExtensionModules) > 0 )
        {
            foreach ($ExtensionModules as $ExtensionModulek => $ExtensionModule ) {
                
                $tmpExtensions = DB::table( $ExtensionModule['id'] )->where('league_id',$request->session()->get('user_id'))->get();

                $tmpExtensions = json_decode( $tmpExtensions , true );

                foreach ($tmpExtensions as $tmpExtensionk => $tmpExtension ) {

                    $ToolModules[ $ExtensionModulek.'_'.$tmpExtension['id'] ]['block_name'] = $tmpExtension['title'];
                    $ToolModules[ $ExtensionModulek.'_'.$tmpExtension['id'] ]['edit_route_name'] = $ExtensionModule['edit_route_name'];
                }
            }
        }


         
        // 取出會員的中央排序
        $TmpLeagueCenterSort = DB::table('xyzs_league_block_sort')->where('user_id',$request->session()->get('user_id'))->where('block_id',1)->first();
        
        if( $TmpLeagueCenterSort === NULL ){
            
            $TmpOnModules = [];

        }else{

            $TmpOnModules = unserialize( $TmpLeagueCenterSort->sort );
        }
    
        $OnModules = [];

        foreach( $TmpOnModules as $TmpOnModulek => $TmpOnModule ) {
            
            //$OnModules[$TmpOnModule] = $ToolModules[$TmpOnModule] ;

            if( array_key_exists($TmpOnModule, $ToolModules) ){
            
                $OnModules[$TmpOnModule] = $ToolModules[$TmpOnModule] ;

            }            

            unset($ToolModules[$TmpOnModule]);
        }

        $OffModules = $ToolModules;
        
        $PageTitle = "中央區塊排序";

        return view('/league_sort_center',['OnModules' => $OnModules , 'OffModules' => $OffModules ,'PageTitle'=>$PageTitle , 'tree' => 'sort' ]);
    }




    /*
    |--------------------------------------------------------------------------
    | 中央區塊排序功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_center_act( Request $request ){
        
        // 先確認資料庫是否已經有資料 , 再決定是要新增還是更新
        if( !isset($request->blocksort) || empty($request->blocksort) ){

        	$request->blocksort = [];
        }

        foreach ( $request->blocksort as $blocksortk => $blocksort) {
            
            $AreaValidate = DB::table('xyzs_league_block')->where('id',$blocksort)->where('block_area','center')->first();

            if(  $AreaValidate === NULL ){
                
                $league_message =   [ '0',
                                      "中央排序失敗",
                                      [ ['operate_text'=>'回中央排序','operate_path'=>'/league_sort_center'] ],
                                      3
                                    ];
 
                $request->session()->put('league_message', $league_message);                 
               
                return redirect('/league_message');
            }
        }

        DB::beginTransaction();

        try {
            
            DB::table('xyzs_league_block_sort')
                ->updateOrInsert(
                ['user_id' => $request->session()->get('user_id') , 'block_id' => 1],
                ['sort' => serialize($request->blocksort)]
            );        
        
            DB::commit();

            $league_message =   [ '1',
                                  "中央排序成功",
                                  [ ['operate_text'=>'回中央排序','operate_path'=>'/league_sort_center'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "中央排序失敗",
                                  [ ['operate_text'=>'回中央排序','operate_path'=>'/league_sort_center'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }
        
        return redirect('/league_message');
        
    }
    



    /*
    |--------------------------------------------------------------------------
    | 左側排序
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_left( Request $request ){

        // 取出所有區塊
        $TmpModules = DB::table('xyzs_league_block')->where('block_area','left')->get();
        
        // 轉換為array
        $TmpModules = json_decode( $TmpModules , true );

        $ToolModules = [];
        
        $ExtensionModules = [];

        foreach ($TmpModules as $TmpModulek => $TmpModule ) {
            
            // 非擴張群組
            if( $TmpModule['extension'] == 0 )
            {
                $ToolModules[ $TmpModule['id'] ]['block_name'] = $TmpModule['block_name'];
                $ToolModules[ $TmpModule['id'] ]['edit_route_name'] = $TmpModule['edit_route_name'];
            }
            else
            {
                $ExtensionModules[ $TmpModule['id'] ]['id'] = $TmpModule['ex_table'];
                $ExtensionModules[ $TmpModule['id'] ]['edit_route_name'] = $TmpModule['edit_route_name'];                
            }
        }

        if( count($ExtensionModules) > 0 )
        {
            foreach ($ExtensionModules as $ExtensionModulek => $ExtensionModule ) {
                
                $tmpExtensions = DB::table( $ExtensionModule['id'] )->where('league_id',$request->session()->get('user_id'))->get();

                $tmpExtensions = json_decode( $tmpExtensions , true );

                foreach ($tmpExtensions as $tmpExtensionk => $tmpExtension ) {

                    $ToolModules[ $ExtensionModulek.'_'.$tmpExtension['id'] ]['block_name'] = $tmpExtension['title'];
                    $ToolModules[ $ExtensionModulek.'_'.$tmpExtension['id'] ]['edit_route_name'] = $ExtensionModule['edit_route_name'];                    
                }
            }
        }

        
        // 取出會員的左側排序
        $TmpLeagueCenterSort = DB::table('xyzs_league_block_sort')->where('user_id',$request->session()->get('user_id'))->where('block_id',2)->first();
        
        if( $TmpLeagueCenterSort === NULL ){
            
            $TmpOnModules = [];

        }else{

            $TmpOnModules = unserialize( $TmpLeagueCenterSort->sort );
        }
        

        $OnModules = [];

        foreach( $TmpOnModules as $TmpOnModulek => $TmpOnModule ) {
            
            if( array_key_exists($TmpOnModule, $ToolModules) ){
            
                $OnModules[$TmpOnModule] = $ToolModules[$TmpOnModule] ;

            }

            unset($ToolModules[$TmpOnModule]);
        }

        

        $OffModules = $ToolModules;
                var_dump( $OffModules );


        $PageTitle = "左側區塊排序";

        return view('/league_sort_left',['OnModules' => $OnModules , 'OffModules' => $OffModules ,'PageTitle'=>$PageTitle , 'tree' => 'sort' ]);

    }
    



    /*
    |--------------------------------------------------------------------------
    | 左側排序功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_sort_left_act( Request $request ){
    

        // 先確認資料庫是否已經有資料 , 再決定是要新增還是更新
        if( !isset($request->blocksort) || empty($request->blocksort) ){

            $request->blocksort = [];
        }

        foreach ( $request->blocksort as $blocksortk => $blocksort) {
            
            $AreaValidate = DB::table('xyzs_league_block')->where('id',$blocksort)->where('block_area','left')->first();

            if(  $AreaValidate === NULL ){
                
                $league_message =   [ '0',
                                      "左側排序失敗",
                                      [ ['operate_text'=>'回左側排序','operate_path'=>'/league_sort_left'] ],
                                      3
                                    ];
 
                $request->session()->put('league_message', $league_message);                 
               
                return redirect('/league_message');
            }
        }

        DB::beginTransaction();

        try {
            
            DB::table('xyzs_league_block_sort')
                ->updateOrInsert(
                ['user_id' => $request->session()->get('user_id') , 'block_id' => 2],
                ['sort' => serialize($request->blocksort)]
            );        
        
            DB::commit();

            $league_message =   [ '1',
                                  "左側排序成功",
                                  [ ['operate_text'=>'回左側排序','operate_path'=>'/league_sort_left'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            //var_dump($e->getMessage());
            $league_message =   [ '0',
                                  "左側排序失敗",
                                  [ ['operate_text'=>'回左側排序','operate_path'=>'/league_sort_left'] ],
                                  3
                                ];

            $request->session()->put('league_message', $league_message);            

        }
        
        return redirect('/league_message');
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

        return view('league_module_banner',['PageTitle'=>$PageTitle , 'banners'=>$banners , 'tree' => 'modul' ]);
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
        
        return view('league_module_banner_new',['PageTitle'=>$PageTitle , 'tree' => 'modul']);
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
            'des'        => 'required'

        ],
        [   'banner.required'=> 'banner圖片尚未選取',
            'banner.mimes'   => 'banner只接受 jpg 及 png 格式',
            'sort.integer'   => '排序只接受數字',
            'sort.required'  => '排序為必填',    
            'des.required'   => 'banner描述為必填'


        ]);

        if ($validator->fails()) {
            return back()->withErrors( $validator->errors() );
        }

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
                    'update_date'=>$NowTime,
                    'des'     => $request->des ,
                    'url'     => $request->url,                    
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
        
        return view('league_module_banner_edit',['PageTitle'=>$PageTitle , 'Banner' => $Banner , 'tree' => 'modul']);
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
            'des'        => 'required',


        ],
        [   'banner.required'=> 'banner圖片尚未選取',
            'banner.mimes'   => 'banner只接受 jpg 及 png 格式',
            'sort.integer'   => '排序只接受數字',
            'sort.required'  => '排序為必填',
            'banner_id.required' => 'banner編號缺少',
            'banner_id.exists' => 'banner編號不存在',
            'des.required'   => 'banner描述為必填'
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
                    'update_date'=>$NowTime,
                    'des'     => $request->des,
                    'url'     => $request->url,
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
    
    public function league_test( Request $request ){
        
        $mail_content = view('mail.order_received',['customername'=>'王大牆'])->render();

        echo $mail_content;
    }
/*   
    0801 - 0831
     
    0915 找出所有條件吻合之訂單

    計算獎金
*/
}
