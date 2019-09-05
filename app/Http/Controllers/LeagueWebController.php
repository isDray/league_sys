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

        $CenterBlocks = unserialize( $CenterBlock['sort'] );
        
        foreach ($CenterBlocks as $CenterBlockk => $CenterBlock) {
            
            $BlockName = DB::table('xyzs_league_block')->where('id',$CenterBlock)->first();

            if( $BlockName != NULL ){

                $CenterBlocks[$CenterBlockk] = $BlockName->name;

            }
        }
        
        return view('web_index', [ 'CenterBlocks' => $CenterBlocks ]);
    }

}
