@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$hots = Lib_block::get_recommend('hot');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h2 class="box-title recommend_title">熱銷商品</h2>
        <h3 class="page_more_desc">為您推薦情趣用品最熱銷的商品,讓您一次就買到最熱門的情趣用品</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $hots as $hotk => $hot)
        <a href="{{url('/show_goods/'.$hot['goods_id'])}}" title="查看商品:{{$hot['goods_name']}}詳細內容">
        <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$hot['goods_thumb']}}" alt="{{ $hot['goods_name'] }},貨號:{{ $hot['goods_sn'] }},價格:{{ $hot['shop_price'] }}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $hot['goods_name'] }}</h4>
                    <p class='goods_sn'>貨號:{{ $hot['goods_sn'] }}</p>
                    <p class='goods_price'>價格:{{ $hot['shop_price'] }}</p>
                    <p><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$hot['goods_id']}}" title="將{{ $hot['goods_name'] }}加入購物車">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        </a>
        @endforeach

    </div>
    <!-- /.box-body -->

</div>        