<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/','LoginController@index');



/*
|--------------------------------------------------------------------------
| 註冊相關
|--------------------------------------------------------------------------
| 
|
*/
// 申請
Route::get('/register','RegisterController@index');

Route::post('/league_account_exist','RegisterController@league_account_exist');

// 申請表單寫入
Route::post('/register_act','RegisterController@register_act');

// 重新產生驗證碼
Route::get('/get_captcha/{config?}', function (\Mews\Captcha\Captcha $captcha, $config = 'default') {

    return $captcha->src($config);

});

// 註冊成功
Route::get('/register_result/{status}','RegisterController@register_result');




/*
|--------------------------------------------------------------------------
| 登入相關
|--------------------------------------------------------------------------
|
*/

// 登入頁面
Route::get('/login','LoginController@index');

// 登入驗證
Route::post('/login_act','LoginController@login_act');

// 登出
Route::get('/logout_act','LoginController@logout_act');



/*
|--------------------------------------------------------------------------
| 會員相關操作
|--------------------------------------------------------------------------
| 在此群組中的頁面 , 都必須要登入後才可以存取
|
*/
Route::group(['middleware' => ['CheckLogin']], function () {
    
    // 儀錶板頁面
    Route::get('/league_dashboard','LeagueController@index');

    // 測試
    Route::get('/league_test','LeagueController@league_test');


    /*
    |--------------------------------------------------------------------------
    | 網站設定相關
    |--------------------------------------------------------------------------
    |
    */

    // 中央區塊排序
    Route::get('/league_sort_center','LeagueController@league_sort_center');    
    
    // 中央區塊排序功能
    Route::post('/league_sort_center_act','LeagueController@league_sort_center_act');
    

});