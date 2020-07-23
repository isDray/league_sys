@php
    
    use App\Cus_lib\Lib_block;
  
    use Illuminate\Http\Request;
  
    $progress_percent = Lib_block::get_progress_percent();
    
@endphp

<style type="text/css">
.shipping_fee_free_box{
    background-color: white;
}
.shipping_fee_free_box h1 , .shipping_fee_free_box h2 , .shipping_fee_free_box h3,
.shipping_fee_free_box h4 , .shipping_fee_free_box h5 , .shipping_fee_free_box h6{
    font-family: 'cwTeXYen', sans-serif!important; 
    font-weight: 900;
}
</style>
<div class="box box-solid shipping_fee_free_box">    
    
    <div class="box-body" id="cart_content">
        <h3>台灣本島滿額1000元免運費</h3>
        <h4>{{$progress_percent['msg']}}</h4>
        <div class="cart-progress-bar col-md-8 col-sm-12 col-xs-12" percent="{{$progress_percent['percent']}}%">
            <div class="cart-progress" id="progress" style="width:{{$progress_percent['percent']}}%;" ></div>
        </div>
        @if( count($progress_percent['shipping_goods']) > 0 )
        <div class='col-md-12 col-sm-12 col-xs-12' style='padding:0px;'>
            <h4> 這裡有些商品 , 把喜歡的加入購物車 , 享免運又可暢玩更多情趣 </h4>
        </div>
        @foreach( $progress_percent['shipping_goods'] as $hot)
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
        @endif
    </div>

</div>

<style type="text/css">
.cart-progress-bar {
  position: relative;
  background: #e6eeff;
  box-shadow: inset 0 0 3px rgba(0,0,0,0.5);
  /*width: 500px;*/
  height: 10px;
  border-radius: 50px;
  margin: auto;
  /*overflow: hidden;*/
  padding-left: 0px;
  padding-right: 0px;
}
.cart-progress-bar:after{
    font-family: 'cwTeXYen', sans-serif!important; 
    font-weight: 900;
    display: block;
    width: 80px;
    height: 20px;
    content: attr(percent);
    background-color: #ffabca;
    position: absolute;
    right:-86px;
    top:calc(50% - 10px);
    text-align: center;
    line-height: 20px!important;
    border-radius: 4px;

}
@media( max-width: 991px){
.cart-progress-bar:after{
    font-family: 'cwTeXYen', sans-serif!important; 
    font-weight: 900;
    display: block;
    width: 80px;
    height: 20px;
    content: attr(percent);
    background-color: #ffabca;
    position: absolute;
    right:0px;
    top:-26px;
    text-align: center;
    line-height: 20px!important;
    border-radius: 4px;

}  
}

.cart-progress-bar .cart-progress {
  background-image: -webkit-gradient(linear, left top, right top, from(#8aaaff), to(#fa8cff));
  background-image: linear-gradient(90deg, #8aaaff, #fa8cff);
  /*width: 70%;*/
  height: 10px;
  border-radius: 5px;
}
</style>