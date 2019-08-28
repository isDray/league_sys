<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
}
