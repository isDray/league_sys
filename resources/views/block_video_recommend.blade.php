@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;
$recommends = Lib_block::get_recommend('video');


@endphp
@if( count($recommends) > 0 )
<div class='block_video box box-solid'>
    <!-- /.box-header -->
    <div class="box-body owl-carousel owl-theme ">
        @foreach( $recommends as $recommendk => $recommend)
       
        <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box item'>

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

<script type="text/javascript">

</script>
@endif       