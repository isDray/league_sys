@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$recommends = Lib_block::get_recommend('new');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h2 class="box-title recommend_title ">新品上市</h2>
        <h3 class="page_more_desc">為您推薦最新潮最刺激的情趣用品,讓你走在情趣的時代尖端</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $recommends as $recommendk => $recommend)
        
        <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>

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
                    <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$recommend['goods_id']}}" title="將{{ $recommend['goods_name'] }}加入購物車" >立即購買</a></p>
                </div>
            </div>                
            
        </div>
       
        @endforeach

    </div>
    <!-- /.box-body -->

</div> 