@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_cart.css')}}">
@endsection

@section('content_right')
<div class="box box-solid">
    
    <div class="box-header with-border bg-yellow">
        <i class="fa fa-fw fa-shopping-cart"></i>
        <h3 class="box-title">購物車</h3>
    </div>
    

    <div class="box-body">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>縮圖</th>
                    <th>商品名稱</th>
                    <td>價格</td>
                    <th>數量</th>
                    <th>小計</th>
                    <th>移除</th>
                </tr>

            
            @if( isset( $Carts ) )
                @foreach( $Carts as $Cartk => $Cart )
                <tr>
                    <td class='img_in_cart'><img src="https://***REMOVED***.com/***REMOVED***/{{$Cart['thumbnail']}}"></td>
                    <td>{{$Cart['name']}}</td>
                    <td>{{$Cart['goodsPrice']}}</td>
                    <td>{{$Cart['num']}}</td>
                    <td>{{$Cart['subTotal']}}</td>
                    <td> <span class='btn bg-maroon btn-flat margin rmbtn' goods_id="{{$Cart['id']}}"><i class='fa fa-fw fa-remove'></i></span> </td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('selfjs')

@endsection