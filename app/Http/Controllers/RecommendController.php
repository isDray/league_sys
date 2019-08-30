<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;

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
        
        $PageTitle = "熱銷商品功能管理";
        
        // 取出所有分類
        $Categorys = DB::table('xyzs_category')->get();
        
        $Categorys = Lib_common::GetCategorys();
        
        // var_dump($Categorys);
        return view('/league_module_recommend_hot',[ 'PageTitle' => $PageTitle ,
                                                     'Categorys' => $Categorys]);
    }

}
