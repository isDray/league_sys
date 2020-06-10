<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;

use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('good_class', function( $attribute, $goods_sn, $parameters ) {

        $goods_datas = DB::table('xyzs_goods')->where('goods_sn',$goods_sn)->first();
        
        if( $goods_datas )
        {
            if( $goods_datas->cat_id == $parameters[0] )
            {
                return true;
            }
            else
            {   
                $goods_datas2 = DB::table('xyzs_goods_cat')->where('goods_id',$goods_datas->goods_id)->where('cat_id',$parameters[0])->first();
                
                if( $goods_datas2 )
                {
                    return true;
                }
                else
                {
                    return false;
                }

            }

        }
        else
        {
            return false;
        }
            
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }  
}
