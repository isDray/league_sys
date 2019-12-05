@if($type == 0)

<div class="alert alert-warning" role="alert">{{$msg}}</div>
<style type="text/css">
#chk_too_faster{
    font-family: '微軟正黑體';
    background-color: rgba(255,165,0,1);
    animation: colorchange 0.6s;
    animation-iteration-count: 1;
    -webkit-animation-fill-mode: forwards;    
}
@keyframes colorchange
{
    0%   {background-color: rgba(255,165,0,1);}
    100% {background-color: rgba(255,165,0,0);}
}
</style>

@endif
    
@if( $type == 1)
<div class="table-responsive">
<p class="bg-primary order_query_title">訂單狀態</p>
<table class="table table-hover">
<tr>
    <td>訂單編號</td>
    <td>{{$orderDatas['order_sn']}}</td>
</tr>
<tr>
    <td>訂單狀態</td>
    <td>{{$orderDatas['order_status']}}</td>
</tr>
<tr>
    <td>配送狀態</td>
    <td>{{$orderDatas['shipping_status']}}</td>
</tr>
</table>
</div>


<div class="table-responsive">
<p class="bg-primary order_query_title">商品列表</p>
<table class="table table-hover">
<tr>
    <td>商品編號</td>
    <td>商品名稱</td>
    <td>價格</td>    
    <td>數量</td>
    <td>小計</td>
</tr>
@foreach( $orderGoodsDatas as $orderGoodsDatak => $orderGoodsData)
<tr>
    <td>
        {{$orderGoodsData['goods_sn']}}
    </td>
    <td>
        <img src="https://***REMOVED***.com/***REMOVED***/{{$orderGoodsData['goods_thumb']}}" width='100px' height='100px'>
    </td>
    <td>
        {{'$'.$orderGoodsData['goods_price']}}
    </td>    
    <td>
        {{$orderGoodsData['goods_number']}}
    </td>    
    <td>
        {{ '$'.($orderGoodsData['goods_number'] * $orderGoodsData['goods_price']) }}
    </td>
</tr>
@endforeach
</table>
</div>
<style type="text/css">
.order_query_title{
    font-size: 20px;
    font-weight: 900;
    font-family: '微軟正黑體';
}
</style>
@endif