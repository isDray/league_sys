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
<!-------------------------->
    @if( $orderDatas['shipping_id'] == 17 || $orderDatas['shipping_id'] == 18 || $orderDatas['shipping_id'] == 19 )
    <div class="table-responsive">
        @if( $orderDatas['add_time'] )
        <div class='fourBox fourBoxActive fourBoxActiveF col-md-2 col-md-offset-1 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @else
        <div class='fourBox col-md-2 col-md-offset-1 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @endif
            <div class='outBall'>
                <div class='inBall'>
                </div>
            </div>  

            <span class='_m' align='left'>
            訂單成立
            <br>
            {{ $orderDatas['add_time'] }}
            </span>
        </div>

        @if( $orderDatas['shipping_statusN2'] == 1 || $orderDatas['shipping_statusN2'] == 2 || $orderDatas['shipping_statusN2'] == 4 || $orderDatas['shipping_statusN2'] == 6)
        <div class='fourBox fourBoxActive col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @else
        <div class='fourBox col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @endif
            <div class='outBall'>
                <div class='inBall'>
                </div>
            </div>  

            <span class='_m' align='left'>
            已出貨
            <br>
            {{ $orderDatas['shipping_time'] }}
            </span>
        </div>


        @if ( $orderDatas['out_date'] || $orderDatas['st_date'] )
        <div class='fourBox fourBoxActive col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @else
        <div class='fourBox col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @endif
            <div class='outBall'>
                <div class='inBall'>
                </div>
            </div>  

            <span class='_m' align='left'>
            物流處理中  
            <br>
            {{ $orderDatas['out_date'] }}                
            </span>            
        </div>      
        @if( $orderDatas['st_date'] || $orderDatas['tk_date'] )
        <div class='fourBox fourBoxActive col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @else
        <div class='fourBox col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @endif
            <div class='outBall'>
                <div class='inBall'>
                </div>
            </div>  

            <span class='_m' align='left'>
            已送達門市  
            <br>
            {{ $orderDatas['st_date'] }}                 
            </span>            
        </div>

        @if( $orderDatas['tk_date'] || $orderDatas['back_date'] )
        <div class='fourBox fourBoxActive col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @else
        <div class='fourBox col-md-2 col-sm-12 col-xs-12 text-center' style='height:100%;'>
        @endif
            <div class='outBall'>
                <div class='inBall'>
                </div>
            </div> 

            <span class='_m' align='left'>
            @if( $orderDatas['back_date'] )
            未取退貨  
            @else
            已取貨
            @endif  
            <br>
            @if($orderDatas['back_date'])
                {{ $orderDatas['back_date'] }}
            @else
                {{ $orderDatas['tk_date'] }}
            @endif
            </span>             
        </div>                              
    </div>
    <div class='table-responsive _w'>
        
        <div class='col-md-2 col-md-offset-1 col-sm-3 col-xs-3 text-center' style='height:100%;'>

            訂單成立
            <br>
            {{ $orderDatas['add_time'] }}

        </div>
        <div class='col-md-2 col-sm-3 col-xs-3 text-center' style='height:100%;'>

            已出貨  
            <br>
            {{ $orderDatas['shipping_time'] }}
        </div>        
        <div class='col-md-2 col-sm-3 col-xs-3 text-center' style='height:100%;'>

            物流處理中  
            <br>
            {{ $orderDatas['out_date'] }}
        </div>
        <div class='col-md-2 col-sm-3 col-xs-3 text-center' style='height:100%;'>
          
            已送達門市  
            <br>
            {{ $orderDatas['st_date'] }}              
        </div>
        <div class='col-md-2 col-sm-3 col-xs-3 text-center' style='height:100%;'>
            @if( $orderDatas['back_date'] )
            未取退貨  
            @else
            已取貨
            @endif  
            <br>
            @if( $orderDatas['back_date'] )
                {{ $orderDatas['back_date'] }}
            @else
                {{ $orderDatas['tk_date'] }}
            @endif             
        </div>                                              
    </div>    
    @endif
<!-------------------------->
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

    <style type="text/css">
    @media (max-width: 991px) {
        .outBall{
            width: 30px;
            height: 30px;
            border-radius: 20px;
            background-color: #969696;
            float:left;
            position: relative;
        }
        .inBall{
            width: 20px;
            height: 20px;
            border-radius: 20px;
            background-color: #eaeaf1;
            margin: 0 auto;  
            left:5px;
            top:5px;
            position: absolute;

        }
        .fourBox{
            height: 80px!important;
            z-index: 2;
            position: relative;

        }
        .fourBoxActive > .outBall > .inBall{
            background-color: #ff4899;
            z-index: 4;
        }
        .fourBoxActive:after{
            display: block;
            content: " ";
            width: 6px;
            height: 80px;
            background-color: #ff4899;
            position: absolute;
            left:27px;
            top:-60px;
            z-index: 1;

        }
        .fourBoxActiveF:after{
            display: none!important;
        }
        ._m{
            float: left;
            margin-left: 20px;
        }
        ._w{
            display: none!important;
        }
    }
    @media (min-width: 992px ){
        .outBall{
            width: 40px;
            height: 40px;
            border-radius: 20px;
            background-color: #969696;
            margin: 0 auto;
        }
        .inBall{
            width: 30px;
            height: 30px;
            border-radius: 20px;
            background-color: #eaeaf1;
            margin: 0 auto;  
            left:calc(50% - 15px);
            top:5px;
            position: absolute;

        }
        .fourBox{
            z-index: 2;

        }
        .fourBoxActive > .outBall > .inBall{
            background-color: #ff4899;
            z-index: 4;
        }
        .fourBoxActive:after{
            display: block;
            content: "";
            width: 100%;
            height: 10px;
            background-color: #ff4899;
            position: absolute;
            left: -50%;
            top:calc(50% - 5px);
            z-index: 1;

        }
        .fourBoxActiveF:after{
            display: none!important;
        }
        ._m{
            display: none!important;
        }
    }
    </style>
@endif