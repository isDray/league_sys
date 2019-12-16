@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;
$recommends = Lib_block::get_recommend('video');


@endphp
@if( count($recommends) > 0 )
<div class='block_video box box-solid'>

    <div class="box-header with-border">

        <h2 class="box-title recommend_title">影音商品</h2>
        <h3 class="page_more_desc">附有使用及清潔影片的情趣用品,讓你更容易上手,成為情趣達人</h3>

    </div>    
    <!-- /.box-header -->
    <div class="box-body owl-carousel owl-theme ">
        @foreach( $recommends as $recommendk => $recommend)
       
        <div class='show_goods_box item'>

            <div class="thumbnail">
                <a href="{{url('/show_goods/'.$recommend['goods_id'])}}" title="查看商品:{{$recommend['goods_name']}}詳細內容">
                    <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$recommend['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="{{ $recommend['goods_name'] }},貨號:{{ $recommend['goods_sn'] }},價格:{{ $recommend['shop_price'] }}">
                </a>
                <div class="caption">
                    <p class='goods_sn'>貨號:{{ $recommend['goods_sn'] }}</p>
                    <a href="{{url('/show_goods/'.$recommend['goods_id'])}}" title="查看商品:{{$recommend['goods_name']}}詳細內容">
                    <h4 class="goods_title">{{ $recommend['goods_name'] }}</h4>
                    </a>
                    <p class='goods_price'><small>$</small>{{ $recommend['shop_price'] }}</p>
                    <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$recommend['goods_id']}}" title="將{{ $recommend['goods_name'] }}加入購物車">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        
        @endforeach        
    </div>
    <!-- /.box-body -->
</div>

<link rel="stylesheet" href="{{url('/css/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{url('/css/owl.theme.default.min.css')}}">

<style type="text/css">
.show_goods_box.item{
    padding-left: 15px;
    padding-right: 15px;

}
.show_goods_box.item > .thumbnail > a > img{
    width: auto;
    height: auto;
}
.glyphicon.glyphicon-chevron-left , .glyphicon.glyphicon-chevron-right{
    text-align: center;
    width: 40px;
    height: 40px;
    position: absolute;
    top:calc( 50% - 45px );    
    background-color: rgba( 0 , 0 , 0 , 0.2);
    line-height: 40px;
}
.glyphicon.glyphicon-chevron-left{
    left: 0px;
}
.glyphicon.glyphicon-chevron-right{
    right: 0px;
}


</style>
@endif       