@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$hots = Lib_block::get_recommend('hot');

$hots_desc =  Lib_block::get_recommend('hot','cus_desc');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h2 class="box-title recommend_title">熱銷商品</h2>
        <h3 class="page_more_desc">@if(!empty($hots_desc)) {{$hots_desc}} @else 為您推薦情趣用品最熱銷的商品,讓您一次就買到最熱門的情趣用品 @endif</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $hots as $hotk => $hot)
        
        <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>

            <div class="thumbnail">
                <a href="{{url('/show_goods/'.$hot['goods_id'])}}" title="查看商品:{{$hot['goods_name']}}詳細內容">
                <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$hot['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="{{ $hot['goods_name'] }},貨號:{{ $hot['goods_sn'] }},價格:{{ $hot['shop_price'] }}">
                </a>
                <div class="caption">
                    <p class='goods_sn'>貨號:{{ $hot['goods_sn'] }}</p>
                    <a href="{{url('/show_goods/'.$hot['goods_id'])}}" title="查看商品:{{$hot['goods_name']}}詳細內容">
                    <h4 class="goods_title">{{ $hot['goods_name'] }}</h4></a>
                    
                    <p class='goods_price'><small>$</small>{{ $hot['shop_price'] }}</p>
                    <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$hot['goods_id']}}" title="將{{ $hot['goods_name'] }}加入購物車">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        
        @endforeach

    </div>
    <!-- /.box-body -->

</div>        