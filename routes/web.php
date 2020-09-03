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
//Route::get('/','LoginController@index');



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

Route::get('/no_league', function () {
    return view('no_league', ['name' => 'none']);
});


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


// index_123.blade.php

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
    
    /*
    |--------------------------------------------------------------------------
    | 報表相關
    |--------------------------------------------------------------------------
    |
    */
    Route::match(['get', 'post'],'/league_report_order','ReportController@league_report_order');
    
    Route::match(['get', 'post'],'/league_report_commission','ReportController@league_report_commission');

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

    // 左側排序
    Route::get('/league_sort_left','LeagueController@league_sort_left'); 

    // 左側排序功能
    Route::post('/league_sort_left_act','LeagueController@league_sort_left_act'); 

    // 購物車排序
    Route::get('/league_sort_cart','LeagueController@league_sort_cart');
    
    // 購物車排序功能
    Route::post('/league_sort_check_cart_act','LeagueController@league_sort_check_cart_act'); 
 


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
    
    // 類別推薦模組列表
    Route::get('league_module_recommend_category_list','RecommendController@league_module_recommend_category_list');

    // 類別推薦模組
    Route::get('/league_module_recommend_category/{recommend_id?}','RecommendController@league_module_recommend_category');
    
    // 類別推薦模組功能
    Route::post('/league_module_recommend_category_act','RecommendController@league_module_recommend_category_act');

    // 類別推薦刪除
    Route::post('/league_module_recommend_category_del','RecommendController@league_module_recommend_category_del');
    
    // 堆疊商品輪播功能管理
    Route::get('/league_module_recommend_stack','RecommendController@league_module_recommend_stack_list');
    
    // 堆疊商品輪播編輯
    Route::get('/league_module_recommend_stack_edit/{stack_id?}','RecommendController@league_module_recommend_stack_list_edit');
    
    // 堆疊商品輪播編輯功能
    Route::post('/league_module_recommend_stack_edit_act','RecommendController@league_module_recommend_stack_edit_act');
    
    // 堆疊商品輪播編輯刪除
    Route::post('/league_module_recommend_stack_del','RecommendController@league_module_recommend_stack_del');

    // 客製化推薦商品清單
    Route::get('/league_module_recommend_custom_ad','RecommendController@league_module_recommend_custom_ad_list');
    
    // 客製化推薦商品編輯
    Route::get('/league_module_recommend_custom_ad_edit/{id?}','RecommendController@league_module_recommend_custom_ad_edit');
    
    // 客製化推薦商品編輯功能
    Route::post('/league_module_recommend_custom_ad_edit_act','RecommendController@league_module_recommend_custom_ad_edit_act');
    
    // 客製化推薦商品刪除
    Route::post('/league_module_recommend_custom_gad_del','RecommendController@league_module_recommend_custom_gad_del');

    // 免運差額推薦編輯
    Route::get('/league_module_recommend_shipping_free','RecommendController@league_module_recommend_shipping_free');

    // 免運差額推薦功能
    Route::post('/league_module_recommend_shipping_free_act','RecommendController@league_module_recommend_shipping_free_act');

    // 網站設定
    Route::get('/league_webset','WebsetController@league_webset');
    
    // 網站設定功能
    Route::post('/league_webset_act','WebsetController@league_webset_act');
    
    /*
    |--------------------------------------------------------------------------
    | 加盟會員 - 私有會員相關
    |--------------------------------------------------------------------------
    |
    */
    Route::get('/league_member_list/{page?}/{perpage?}','LeagueMemberController@index');

    Route::get('/league_member_show/{member_id}','LeagueMemberController@edit');

    Route::post('/league_member_update','LeagueMemberController@update');


    /*
    |--------------------------------------------------------------------------
    | 加盟會員相關
    |--------------------------------------------------------------------------
    |
    */
    Route::group(['middleware' => ['CheckProfile']], function () {
        
        // 個人資料設定
        Route::get('/league_profile_basic/{user_id}','LeagueInfoController@league_profile_basic');
        
        // 個人資料設定功能 
        Route::post('league_profile_basic_act','LeagueInfoController@league_profile_basic_act');
        
        // 加盟會員帳密設定
        Route::get('/league_profile_password/{user_id}','LeagueInfoController@league_profile_password');   

        // 加盟會員密碼設定功能
        Route::post('/league_profile_password_act','LeagueInfoController@league_profile_password_act');

    });

    /*
    |--------------------------------------------------------------------------
    | 工具相關
    |--------------------------------------------------------------------------
    |
    */
    Route::post('/backtool','BackToolController@ajax_get_category');
    
});

/*
|--------------------------------------------------------------------------
| 網站前台相關
|--------------------------------------------------------------------------
|
*/
Route::group(['middleware' => ['CheckLeague']], function () {
    
    Route::get('/test','LeagueWebController@test');

    // 首頁
    Route::get('/','LeagueWebController@index');

    // 絕對首頁
    Route::get('/index','LeagueWebController@index');

    // 過橋頁
    Route::get('/over18','LeagueWebController@over18');
    
    // 商品內頁
    Route::get('/show_goods/{goods_id}','GoodsController@show_goods');

    // 分類頁面
    Route::get('/category/{cat_id}/{cat_sort_item?}/{cat_sort_way?}/{now_page?}/{per_page?}','CategoryController@category');

    // 搜尋頁面
    Route::match(['get', 'post'],'/search/{keyword?}/{cat_sort_item?}/{cat_sort_way?}/{now_page?}/{per_page?}','SearchController@search');
    
    // 新品
    Route::get('/new_arrival/{now_page?}/{per_page?}','SearchController@newest');

    // 訂單查詢頁面
    Route::get('/check_order' , 'LeagueWebController@check_order');
    
    // 訂單查詢
    Route::post('/check_order_act' , 'LeagueWebController@check_order_act');

    // 加入購物車
    Route::post('/add_to_cart','CartController@add_to_cart');

    // 移除購物車商品
    Route::post('/rm_from_cart','CartController@rm_from_cart');

    // 修改購物車商品數量
    Route::post('/change_goods_num','CartController@change_goods_num');

    // 購物車頁面
    Route::get('/cart','CartController@cart');

    // 結帳頁面
    Route::get('/checkout','CartController@checkout');
    
    // 宅配站所取得
    Route::post('/get_cat_store','CartController@get_cat_store');

    // 宅配站所取得
    Route::get('/get_cat_map/{address}','CartController@get_cat_map');

    // ajax免運差額
    Route::post('/ajax_shipping_free_recommend','CartController@ajax_shipping_free_recommend');

    Route::post('/ajax_df_address','CartController@ajax_df_address');

    // 寫入訂單
    Route::post('/done','CartController@done');
    
    // 付款完成
    Route::get('/payed','CartController@payed');

    Route::post('/areaChange','CartController@areaChange');

    Route::post('/shipChange','CartController@shipChange');

    Route::any('/storeMap/{device}/{type}','CartController@storeMap');
    
    Route::post('/validate_bonus','CartController@validate_bonus');

    // 網站文章
    Route::get('/article/{article_id}','LeagueWebController@article');
    

    /**
     * 加盟商私人會員
     *
     **/
    Route::get('/join_member','MemberController@create');

    Route::post('/join_member_store','MemberController@store');

    Route::post('/member_account_exit','MemberController@member_account_exit');

    Route::get('/member_login','MemberController@login');
    
    Route::post('/fblogin','MemberController@fblogin');

    Route::post('/googlelogin','MemberController@googlelogin');
    

    Route::post('/member_login_act','MemberController@member_login_act');

    Route::get('/member_logout_act','MemberController@member_logout_act');
    
    /*
    |--------------------------------------------------------------------------
    | 判斷是否有登入加盟會員知會員
    |--------------------------------------------------------------------------
    |
    */
    Route::group(['middleware' => ['CheckMemberLogin']], function () {
        
        // 私有會員首頁
        Route::get('/member_index','MemberController@member_index');
        
        // 私有會員編輯頁面
        Route::get('/member_edit','MemberController@member_edit');
        
        // 私有會員基本資料編輯實作
        Route::post('/member_edit_detail_act','MemberController@member_edit_detail_act');
        
        // 私有會員密碼編輯實作
        Route::post('/member_edit_password_act','MemberController@member_edit_password_act');
        
        // 私有會員訂單查詢
        Route::get('/member_order/{page?}/{perpage?}','MemberController@member_order');

    });
    
});

Route::get('/getTop3','CronController@getTop3');