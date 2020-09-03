<!--
結帳頁面動態計算免運差額推薦商品 blade ajax
-->
@if( isset($check_sub) && count($check_sub) )
<div id='check_sub_in' class="col-md-12 col-sm-12 col-xs-12">
    
    <table>
        <tr>
            <td class='textright'>商品金額 : {{$check_sub['goods_amount']}}</td>
        </tr>
        
        <tr>
            <td class='textright'>運費 : {{$check_sub['shipping_fee']}}</td>
        </tr>

        <tr>
            <td class='textright'>總價 : {{$check_sub['order_amount']}}</td>
        </tr>                                 
    </table>
                
    
    <h4>@if( $check_sub['achieve_percent'] < 100) 目前只要再{{$check_sub['diff_for_free']}}即享免運 @else 已達免運標準 @endif</h4>
    
    <div class="cart-progress-bar col-md-8 col-sm-12 col-xs-12" percent="{{$check_sub['achieve_percent']}}%">
        <div class="cart-progress" id="progress" style="width:{{$check_sub['achieve_percent']}}%;" percent="{{$check_sub['achieve_percent']}}%"></div>
    </div>
    
    @if( count($shipfree_recommends) > 0)
    <div class='col-md-12 col-sm-12 col-xs-12'></div>
    
    @foreach( $shipfree_recommends as $shipfree_recommendk => $shipfree_recommend )
    <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>
        <div class="thumbnail">
            <a href="{{url('/show_goods/'.$shipfree_recommend['goods_id'])}}" title="查看商品:{{$shipfree_recommend['goods_name']}}詳細內容">
                <img src="https://***REMOVED***.com/***REMOVED***/{{$shipfree_recommend['goods_thumb']}}" data-holder-rendered="true"  alt="{{ $shipfree_recommend['goods_name'] }},貨號:{{ $shipfree_recommend['goods_sn'] }},價格:{{ $shipfree_recommend['shop_price'] }}">
            </a>
                            
            <div class="caption">
                <p class='goods_sn'>貨號:{{ $shipfree_recommend['goods_sn'] }}</p>
                
                <a href="{{url('/show_goods/'.$shipfree_recommend['goods_id'])}}" title="查看商品:{{$shipfree_recommend['goods_name']}}詳細內容">
                    <h4 class="goods_title">{{ $shipfree_recommend['goods_name'] }}</h4>
                </a>
                    
                <p class='goods_price'><small>$</small>{{ $shipfree_recommend['shop_price'] }}</p>
                                
                <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$shipfree_recommend['goods_id']}}" title="將{{ $shipfree_recommend['goods_name'] }}加入購物車">立即購買</a></p>
            </div>
        </div>                
    </div>                    
    @endforeach

    @endif
</div>
@endif