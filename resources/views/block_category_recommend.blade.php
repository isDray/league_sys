@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;
$categorys = Lib_block::get_categorys();

@endphp
<link href="{{ asset('css/block_category_recommend.css') }}" rel="stylesheet">
<div class="box box-solid category_box" cus_title='類別商品推薦'>
    
    <div class="box-header">

        <h2 class="box-title recommend_title">類別商品推薦</h2>
        <h3 class="page_more_desc">店長嚴選分類情趣用品,大力推薦</h3>

    </div>
    
    <!-- /.box-header -->

    <div class="switch_box">
<!--         <li class='switch_label active' id="label1" >男性用品</li>
        <li class='switch_label' id="label2" >女性用品</li>
        <li class='switch_label' id="label3" >SM用品</li> -->
        @foreach($categorys as $categoryk => $category)
            <li class='switch_label @if($categoryk == 0) active @endif' id="label{{$categoryk}}" >{{ $category['cat_name'] }}</li>       
        @endforeach        
    </div>

    <div class="box-body switch_content_box">       
        @foreach($categorys as $categoryk => $category)
            <div class='@if($categoryk == 0) active @endif switch_content' for='label{{$categoryk}}'>
                <div class='categoory_ribbon'>{{ $category['cat_name'] }}</div>
                <div class='categoory_desc'>{{ $category['cat_desc'] }}</div>
                    
                    <div class='col-md-12 col-sm-12 col-xs-12 cat_main_box_out'>

                        <div class='col-md-12 col-sm-12 col-xs-12 cat_main_box_mid'>

                    @foreach( $category['goodsDatas'] as $category_k => $category_v )
                    
                        <div class='cat_main_box_in col-md-3 col-sm-3 col-xs-6'>
                            
                            <div class='cat_item'>
                                
                                <div class='cat_item_img'>
                                    <img src="https://***REMOVED***.com/***REMOVED***/{{$category_v['goods_thumb']}}">
                                </div>

                                <div class='cat_item_info'>
                                    <div class='item_sn'>{{$category_v['goods_sn']}}</div>
                                    <div class='item_name'>{{$category_v['goods_name']}}</div>
                                    <div class='item_price'>${{round($category_v['shop_price'])}}</div>
                                    <div class='item_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$category_v['goods_id']}}" title="將{{ $category_v['goods_name'] }}加入購物車">立即購買</a></div>
                                </div>
                            </div>
                        </div>
                    
                    @endforeach

                        </div>
                    </div>
                    

                

            </div>            
        @endforeach               
    </div>

</div>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(event) { 
    $(".switch_label").click(function(){

        choose_label = $(this).attr('id');
        
        // 選擇與目前顯示的不同才需要做切換
        if( $(".switch_content.active").attr('for') != choose_label )
        {
            $(".switch_content.active").hide();
            $(".switch_content.active").removeClass('active');

            $(".switch_content[for="+choose_label+"]").fadeIn();
            $(".switch_content[for="+choose_label+"]").addClass('active');

            $(".switch_label").removeClass('active');
            $(this).addClass('active');
        }


    });
});
</script>