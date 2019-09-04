<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeagueWebController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 網站首頁
    |--------------------------------------------------------------------------
    |
    */
    public function index( Request $request ){
        
        return view('web_index');
    }
}
