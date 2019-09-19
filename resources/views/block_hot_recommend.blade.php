@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$hots = Lib_block::get_recommend('hot');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h3 class="box-title recommend_title">熱銷商品</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $hots as $hotk => $hot)
        <a href="{{url('/show_goods/'.$hot['goods_id'])}}">
        <div class='col-md-3 col-sm-4 col-xs-6'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$hot['goods_thumb']}}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $hot['goods_name'] }}</h4>
                    <p class='goods_sn'>貨號:{{ $hot['goods_sn'] }}</p>
                    <p class='goods_price'>價格:{{ $hot['shop_price'] }}</p>
                    <p><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$hot['goods_id']}}">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        </a>
        @endforeach

    </div>
    <!-- /.box-body -->

</div>        