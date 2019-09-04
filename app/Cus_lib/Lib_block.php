<?php
namespace App\Cus_lib;
use DB;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| 排序區塊liberay
|--------------------------------------------------------------------------
|
*/
class Lib_block{
    
    public static function banner( Request $request ){

        $LeagueId = $request->session()->get('user_id');

        /*$banners  = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->orderBy('sort')->get();

        var_dump($banners);*/
    }
}
?>