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
    
    /*
    |--------------------------------------------------------------------------
    | 輪播banner
    |--------------------------------------------------------------------------
    |
    */
    public static function banner(){

        
        $LeagueId = Session::get( 'user_id' );

        $banners  = DB::table('xyzs_league_banner')->where('user_id',$LeagueId)->orderBy('sort','DESC')->orderBy('update_date')->get();

        $banners  = json_decode( $banners , true );

        return $banners;
    }




    /*
    |--------------------------------------------------------------------------
    | 推薦商品
    |--------------------------------------------------------------------------
    |
    */
    public static function get_recommend( $_type ){

        $LeagueId = Session::get( 'user_id' );

        // 取出要排除的類別
        if( $_type == 'hot' ){
                
            $RecommendType = 1;

        }elseif( $_type == 'best' ){
                
            $RecommendType = 2;

        }elseif( $_type == 'new' ){
            
            $RecommendType = 3;
        }

        $ExcludeCat = DB::table('xyzs_league_recommend')->where('user_id', $LeagueId )->where('recommmand_type',$RecommendType)->first();

        if( $ExcludeCat != NULL){
            
            $ExcludeCat =  unserialize( $ExcludeCat->avoid_cat );
        }
        

        // 如果傳入的推薦種類不存在 , 直接回傳空字串
        if( !in_array( $_type , [ 'new' , 'best' , 'hot' ]) ){

            return array();
        }
        
        
        // 如果是熱銷或者推薦 , 就先檢查是否有xml資料
        if( in_array( $_type , [ 'best' , 'hot' ] ) ){
            

                                
                
            $recommends = [];
            
            //$xmlStr = @simplexml_load_file("https://***REMOVED***.com/***REMOVED***/data"."/apparel_$_type".".xml");    

            $xmlStr = DB::table('xyzs_league_recommend')->where('user_id',$LeagueId)->where('recommmand_type',$RecommendType)->first();
                
            if( $xmlStr != NULL ){

                $pre = [];
                    
                    /*$sliders = json_decode(json_encode((array)$xmlStr), TRUE)['item'];
        
                    foreach ($sliders as $sliderk => $slider) {
            
                        if( array_key_exists('@attributes', $slider)){
        
                            $recommends[] = trim($slider["@attributes"]["sn"]);
                        }else{
        
                            $recommends[] = trim($slider["sn"]);                        
                        }
                    }*/

                    $recommends = unserialize(  $xmlStr->custom_set );
                    
                    foreach ($recommends as $recommendk => $recommend ) {
                        
                        if( !empty( $recommend ) ){
                            
                            $recommend = "NO.".trim($recommend);

                            $tmpPre =  DB::table('xyzs_goods')
                                ->where('goods_sn', $recommend)
                                ->where('is_on_sale',1)
                                ->where('goods_number','>',0)
                                ->where('brand_id','!=','116')
                                ->where('is_alone_sale',1)
                                ->where('is_delete',0)
                                ->where('goods_thumb','<>','')
                                ->whereNotIn('cat_id',  $ExcludeCat )
                                ->whereNotNull('goods_thumb')                        
                                ->first();
                            
                            $tmpPre = (array)$tmpPre ;
                            
                            if( $tmpPre ){
                                array_push($pre, $tmpPre);
                            }

                        }

                    }

                    //var_dump($pre);

                }
                

            
        }
        

        $recommendDB =  DB::table('xyzs_goods')
                        /*->whereIn('cat_id', [25,103,31,104,113,27,29])*/
                        ->where('is_on_sale','1')
                        ->where('goods_number','>',0)
                        ->where('brand_id','!=','116')
                        ->where('is_alone_sale',1)
                        ->where('is_delete',0)
                        ->where('goods_thumb','<>','')
                        ->whereNotIn('cat_id',  $ExcludeCat )
                        ->whereNotNull('goods_thumb');

        if( $_type == 'best' ){

            $recommendDB->where('is_best',1);
        }

        if( $_type == 'hot' ){

            $recommendDB->where('is_hot',1);
        }        

        if( in_array( $_type , ['best' , 'hot'] ) ){

            $recommendDB->orderBy('sort_order','ASC');
        }
        

        $returnDatas = $recommendDB->limit(8)->offset(0)->orderBy('add_time','DESC')->get();
        
        $returnDatas = json_decode($returnDatas,true);
        
       // $returnDatas = $pre + $returnDatas;
       
        if( isset($pre) && count($pre)> 0  ){

            $returnDatas = array_merge($pre,$returnDatas);
        }
        

        if( count($returnDatas) > 8 ){

            return array_slice( $returnDatas , 0 , 8);

        }else{

            return $returnDatas;

        }
        
    }
}
?>