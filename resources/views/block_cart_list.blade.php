<div class="box box-solid">
    
    <div class="box-header with-border">
        <i class="fa fa-fw fa-shopping-cart"></i>
        <h3 class="box-title">購物車</h3>
    </div>
    

    <div class="box-body" id="cart_content">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>縮圖</th>
                    <th class='nameth'>商品名稱</th>
                    <td>價格</td>
                    <th>數量</th>
                    <th>小計</th>
                    <th>移除</th>
                </tr>

            
            @if( isset( $Carts ) )
                @php 
                    $cart_total = 0;
                @endphp

                @foreach( $Carts as $Cartk => $Cart )
                <tr>
                    <td class='img_in_cart'><img src="https://***REMOVED***.com/***REMOVED***/{{$Cart['thumbnail']}}"></td>
                    <td class='nametd' >{{$Cart['name']}}</td>
                    <td>{{$Cart['goodsPrice']}}</td>
                    <td>
                        <select class="cart_goods_num" goods_id="{{$Cart['id']}}">
                            @for( $i=0 ; $i <= $GoodsNums[$Cart['id']] ; $i++)
                            <option value="{{$i}}" @if( $Cart['num'] == $i) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
  

                    </td>
                    <td>{{$Cart['subTotal']}}</td>
                    <td> <span class='btn bg-maroon btn-flat margin rmbtn_cart' goods_id="{{$Cart['id']}}"><i class='fa fa-fw fa-remove'></i></span> </td>
                </tr>
                @php 
                    $cart_total += $Cart['subTotal'];
                @endphp

                @endforeach
            @endif

               
                <tr>
                    <td colspan='4' class='text-right'>總金額</td>
                    <td colspan='2'>{{$cart_total}}</td>
                </tr>

                <tr>
                    <td colspan='6' class='text-center' >
                        <a class='btn bg-maroon btn-flat margin' href="{{url('/checkout')}}">去結帳</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>



