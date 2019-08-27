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

Route::get('/', function () {
    return view('welcome');
});




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
