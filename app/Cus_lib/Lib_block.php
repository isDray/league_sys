<?php
namespace App\Cus_lib;
use DB;
use Illuminate\Http\Request;
use Session;
/*
|--------------------------------------------------------------------------
| 排序區塊liberay
|--------------------------------------------------------------------------
|
*/
class Lib_block{
    
    public static function banner(){

        
        $LeagueId = Session::get( 'user_id' );

        $banners  = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->orderBy('sort','DESC')->orderBy('update_date')->get();

        $banners  = json_decode( $banners , true );

        return $banners;
    }
}
?>