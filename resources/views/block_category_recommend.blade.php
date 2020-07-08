@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;
$categorys = Lib_block::get_categorys( $id );

@endphp

@if( $categorys )
<link href="{{ asset('css/block_category_recommend.css') }}" rel="stylesheet">
<div class="box box-solid category_box" cus_title='類別商品推薦'>
    
    <div class="box-header">

        <h2 class="box-title recommend_title">類別商品推薦</h2>
        <h3 class="page_more_desc">店長嚴選分類情趣用品,大力推薦</h3>

    </div>
    
    <!-- /.box-header -->

    <div class="switch_box">
        @foreach($categorys as $categoryk => $category)
            <li class='switch_label{{$id}} @if($categoryk == 0) active @endif' id="label{{$categoryk}}" >{{ $category['cat_name'] }}</li>       
        @endforeach        
    </div>

    <div class="box-body switch_content_box">       
        @foreach($categorys as $categoryk => $category)
            <div class='@if($categoryk == 0) active @endif switch_content{{$id}}' for='label{{$categoryk}}'>
                
                <div class='categoory_ribbon'>{{ $category['cat_name'] }}</div>
                <div class='categoory_desc'>{{ $category['cus_des'] }}</div>
                
                @foreach( $category['goodsDatas'] as $category_k => $category_v )
                    
                <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>
        
                    <div class="thumbnail">
                        <a href="{{url('/show_goods/'.$category_v['goods_id'])}}" title="查看商品:{{$category_v['goods_name']}}詳細內容">
                        <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$category_v['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="{{ $category_v['goods_name'] }},貨號:{{ $category_v['goods_sn'] }},價格:{{ $category_v['shop_price'] }}">
                        </a>
                        <div class="caption">
                            <p class='goods_sn'>貨號:{{ $category_v['goods_sn'] }}</p>
                            <a href="{{url('/show_goods/'.$category_v['goods_id'])}}" title="查看商品:{{$category_v['goods_name']}}詳細內容">
                            <h4 class="goods_title">{{ $category_v['goods_name'] }}</h4></a>
                            
                            <p class='goods_price'><small>$</small>{{ $category_v['shop_price'] }}</p>
                            <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$category_v['goods_id']}}" title="將{{ $category_v['goods_name'] }}加入購物車">立即購買</a></p>
                        </div>
                    </div>                
                    
                </div>
                    
                @endforeach

            </div>            
        @endforeach               
    </div>

</div>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(event) { 
    $(".switch_label{{$id}}").click(function(){

        choose_label = $(this).attr('id');
        
        // 選擇與目前顯示的不同才需要做切換
        if( $(".switch_content{{$id}}.active").attr('for') != choose_label )
        {
            $(".switch_content{{$id}}.active").hide();
            $(".switch_content{{$id}}.active").removeClass('active');

            $(".switch_content{{$id}}[for="+choose_label+"]").fadeIn();
            $(".switch_content{{$id}}[for="+choose_label+"]").addClass('active');

            $(".switch_label{{$id}}").removeClass('active');
            $(this).addClass('active');
        }


    });
});
</script>
@endif