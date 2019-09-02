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
    | 通用訊息相關
    |--------------------------------------------------------------------------
    |
    */
    Route::get('/league_message','LeagueMessageController@league_message');
    



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

    

    // banner 設定
    Route::get('/league_module_banner','LeagueController@league_module_banner');
    
    // banner 新增
    Route::get('/league_module_banner_new','LeagueController@league_module_banner_new');

    // banner 新增功能
    Route::post('/league_module_banner_new_act','LeagueController@league_module_banner_new_act');
    
    // banner 編輯
    Route::get('/league_module_banner_edit/{id}','LeagueController@league_module_banner_edit');

    // banner 編輯功能
    Route::post('/league_module_banner_edit_act','LeagueController@league_module_banner_edit_act');

    // banner 刪除功能
    Route::get('/league_module_banner_del_act/{banner_id}','LeagueController@league_module_banner_del_act');
    
    // banner 排序功能
    Route::post('league_module_banner_sort_act','LeagueController@league_module_banner_sort_act');


    
    // 熱銷模組
    Route::get('/league_module_recommend_hot','RecommendController@league_module_recommend_hot');

    // 熱銷模組功能
    Route::post('/league_module_recommend_hot_act','RecommendController@league_module_recommend_hot_act');    
    
    // 推薦模組
    Route::get('/league_module_recommend_recommend','RecommendController@league_module_recommend_recommend');

    // 推薦模組功能
    Route::post('/league_module_recommend_recommend_act','RecommendController@league_module_recommend_recommend_act');
    
    // 新品模組
    Route::get('/league_module_recommend_new','RecommendController@league_module_recommend_new');

    // 新品模組功能
    Route::post('/league_module_recommend_new_act','RecommendController@league_module_recommend_new_act');
});