<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LeagueWebController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 網站首頁
    |--------------------------------------------------------------------------
    |
    */
    public function index( Request $request ){

        $LeagueId = $request->session()->get('user_id');

        $CenterBlock = DB::table('xyzs_league_block_sort')->where('user_id', $LeagueId)->first();
        
        $CenterBlock = (array)$CenterBlock;

        $CenterBlock = unserialize( $CenterBlock['sort'] );

        return view('web_index', [ 'CenterBlock' => $CenterBlock ]);
    }

    public static function mytest(){

    	return "123456";

    }
}
