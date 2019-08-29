<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeagueMessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 提示訊息功能
    |--------------------------------------------------------------------------
    |
    */
    public function league_message( Request $request ){
        
        // 先確定有接收到訊息session
        if( !$request->session()->exists('league_message') ){

        	return redirect("/login");

        }
        
        
        $MessageType = $request->session()->get('league_message')[0];
        $MessageText = $request->session()->get('league_message')[1];
        $MessageList = $request->session()->get('league_message')[2];
        $MessageSec  = $request->session()->get('league_message')[3];

        $request->session()->forget('league_message');
        
        return view('league_message',[ 'MessageType' => $MessageType,
        	                           'MessageText' => $MessageText,
                                       'MessageList' => $MessageList,
                                       'MessageSec'  => $MessageSec
        	                         ]);
    }
}
