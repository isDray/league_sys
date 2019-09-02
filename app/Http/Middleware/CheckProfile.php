<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
class CheckProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        // 如果完全沒收到user_id則直接導回首頁
        if( empty($request->user_id) ){
            
            $league_message =   [ '0',
                                  "由於缺少必須參數 , 無法進入基本資料管理",
                                  [ ['operate_text'=>'回後台首頁','operate_path'=>'/league_dashboard'] ],
                                  3
                                ];
            $request->session()->put('league_message', $league_message);   
            
            return redirect('/league_message');

        }else{
            
            if( $request->user_id != Session::get('user_id') ){
                
                $league_message =   [ '0',
                                      "此編號非您的加盟會員編號 , 請勿嘗試非法操作。",
                                      [ ['operate_text'=>'回後台首頁','operate_path'=>'/league_dashboard'] ],
                                      3
                                    ];
                $request->session()->put('league_message', $league_message);   
            
                return redirect('/league_message');                
            }
        }

        return $next($request);
    }
}
