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

        
        $LeagueId = Session::get( 'league_id' );

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

        $LeagueId = Session::get( 'league_id' );

        // 取出要排除的類別
        if( $_type == 'hot' ){
                
            $RecommendType = 1;

        }elseif( $_type == 'best' ){
                
            $RecommendType = 2;

        }elseif( $_type == 'new' ){
            
            $RecommendType = 3;

        }elseif( $_type =='video'){

            $RecommendType = 4;
        }

        $ExcludeCat = DB::table('xyzs_league_recommend')->where('user_id', $LeagueId )->where('recommmand_type',$RecommendType)->first();

        if( $ExcludeCat != NULL){
            
            $ExcludeCat =  unserialize( $ExcludeCat->avoid_cat );

        }else{
            
            $ExcludeCat = [];
        }
        

        // 如果傳入的推薦種類不存在 , 直接回傳空字串
        if( !in_array( $_type , [ 'new' , 'best' , 'hot' , 'video' ]) ){

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
                                ->select('*',DB::raw( "ROUND(shop_price) as shop_price" ) )                        
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
                        ->whereNotNull('goods_thumb')
                        ->select('*',DB::raw( "ROUND(shop_price) as shop_price" ) ) 
                        ;

        if( $_type == 'best' ){

            $recommendDB->where('is_best',1);
        }

        if( $_type == 'hot' ){

            $recommendDB->where('is_hot',1);
        }        

        if( in_array( $_type , ['best' , 'hot'] ) ){

            $recommendDB->orderBy('sort_order','ASC');
        }
        
        if( $_type == 'video' ){

            $recommendDB->whereNotNull('video_name');

            $recommendDB->where('video_name','!=','');
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
    




    /*
    |--------------------------------------------------------------------------
    | 類別推薦功能
    |--------------------------------------------------------------------------
    |
    */
    public static function get_categorys( $id ){

        $LeagueId = Session::get( 'league_id' );
        
        $is_exist = DB::table('xyzs_league_category_recommend')->where('league_id',$LeagueId)->where('id',$id)->first();
        
        $is_exist = (array)$is_exist;
        

        if( !$is_exist )
        {
            return [];
        }

        if( $is_exist )
        {   
            // 取出三個類別
            $categorys = DB::table('xyzs_category')->whereIn('cat_id',[$is_exist['cate_name1'],$is_exist['cate_name2'],$is_exist['cate_name3']])/*->orderBy('cat_id','DESC')*/->get();
            
            $categorys = json_decode( $categorys , true);
            


            for ($i=0; $i < 3; $i++) { 

                $tmpGoods = unserialize($is_exist['cate_goods'.($i+1)]);
                
                $tmpGoodsRes = [];

                foreach ($tmpGoods as $tmpGoodsk => $tmpGoodv) {
                    
                    $getGoodsData = json_decode( DB::table('xyzs_goods')->where('goods_sn',$tmpGoodv)->where('goods_number','>',0)->select('*',DB::raw( "ROUND(shop_price) as shop_price" ) )->get() , true ); 
                    
                    if( $getGoodsData ){

                        array_push($tmpGoodsRes, $getGoodsData[0]);

                    }

                    if( count($tmpGoodsRes) >= 4 ){
                        break;
                    }
                }
                

                //$tmpGoods[$i] = json_decode( DB::table('xyzs_goods')->whereIn('goods_sn',$tmpGoods)->limit(4)->get() , true );
                
                $tmpGoods[$i] = $tmpGoodsRes;
                //$tmpGoods[$i][0]['cat_id'];
                //echo '<br>';
                for ($j=0; $j < 3 ; $j++) { 

                    
                    if(  isset($categorys[$j]) && isset($tmpGoods[$i][0]) ){

                        if( $categorys[$j]['cat_id'] == $tmpGoods[$i][0]['cat_id']){
    
                            $categorys[$j]['goodsDatas'] = $tmpGoods[$i];

                            $categorys[$j]['cus_des']    = $is_exist['cat_des'.($i+1)];
    
                        }
                    }
                  
                }
            }


        }
        else
        {

            $categorys = DB::table('xyzs_category')->where('parent_id','!=',0)->where('is_show',1)->orderByRaw("RAND()")->limit(3)->get();
            
            $categorys  = json_decode( $categorys , true );
            
    
            foreach ($categorys as $categoryk => $category) {
    
                $tmpGoods = DB::table('xyzs_goods')->where(['cat_id'=>$category['cat_id']])->orderByRaw("RAND()")->limit(4)->offset(10)->get();
    
                //$categorys[$categoryk]['goodsDatas'] = $tmpGoods;
    
                $categorys[$categoryk]['goodsDatas'] = json_decode( $tmpGoods , true );
            }
        }

        return $categorys;
    }



    
    /*
    |--------------------------------------------------------------------------
    | 取堆疊資料
    |--------------------------------------------------------------------------
    |
    */
    public static function get_stack( $id ){
        
        $LeagueId = Session::get( 'league_id' );
        $stacks =  DB::table('xyzs_league_stack')
                        ->where('league_id',$LeagueId)
                        ->where('id',$id)
                        ->first();
        
        

        $stacks = (array)$stacks;
        
        if( !$stacks )
        {
            return [];
        }

        $stacks['goods'] = unserialize($stacks['goods']);
        
        $stacks['goods_data'] = [];


        foreach ($stacks['goods'] as $goods ) {
            
            //$tmp = explode('.', $goods)[1];
            
            $goodimg = DB::table('xyzs_goods')->where('goods_sn',$goods)->select('goods_thumb','goods_id')->first();
            

            array_push($stacks['goods_data'], ['img'=>$goodimg->goods_thumb , 'goods_id'=>$goodimg->goods_id]);


        }
        
        return $stacks;
    }




    /*
    |--------------------------------------------------------------------------
    | 取客製商品推薦
    |--------------------------------------------------------------------------
    |
    */
    public static function get_custom_ad( $id ){
        
        $LeagueId = Session::get( 'league_id' );

        $datas =  DB::table('xyzs_league_custom_ad')
                        ->where('league_id',$LeagueId)
                        ->where('id',$id)
                        ->first();        

        $datas = (array)$datas;
        
        if( !$datas )
        {
            return [];
        }        

        $goodimg = DB::table('xyzs_goods')->where('goods_sn',$datas['goods_sn'])->select('goods_thumb','goods_id')->first();
            

        $datas['img']=$goodimg->goods_thumb;
        $datas['goods_id']=$goodimg->goods_id;

        return $datas;
    }




    /*
    |--------------------------------------------------------------------------
    | 免暈差額提示、推薦
    |--------------------------------------------------------------------------
    | 
    */
    public static function get_progress_percent(){
        
        $total = 0;

        if( Session::has('cart') )
        {
            
            $goods = Session::get( 'cart' );

            foreach ($goods as $goodk => $good) {
                
                $total += $good['subTotal'];
            }
        }

        $percent = round(( $total / 1000 )*100);
        
        $msg = "";
        
        // 取出推薦商品
        $shipping_goods = [];

        if( $percent >= 100 )
        {               $percent = 100;

            $msg = " 您目前已達免運門檻 ";
        }
        else
        {   

            $msg = " 您只差".(1000 - $total)."元即達到免運門檻 ";

            $diff_prcie = 1000 - $total;

            $range = ceil( $diff_prcie/100 );



            $tmp_shippings = DB::table('xyzs_league_shipping_recommend')->first();

            if( $tmp_shippings )
            {
                $need_name = "need_{$range}";

                $all_needs  = unserialize( $tmp_shippings->$need_name );

                if( count( $all_needs ) > 0)
                {
                    $tmp_shipping_goods = DB::table('xyzs_goods')
                                    ->select( '*' , DB::raw( "ROUND(shop_price) as shop_price") )
                                    ->whereIn('goods_sn', $all_needs )
                                    ->get();

                    if( $tmp_shipping_goods )
                    {
                        $shipping_goods =  json_decode($tmp_shipping_goods,true);
                    }
                }
            }

        }


        return ['percent'=>$percent,'msg'=>$msg , 'shipping_goods'=>$shipping_goods ];
    }

}
?>