<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 商品分類頁面
    |--------------------------------------------------------------------------
    |
    */
    public function category( Request $request ){
        
        echo $request->cat_id;
    }
}
