@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_show_goods.css')}}">
@endsection

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @includeIf('block_'. $LeftBlock)

    @endforeach

@endsection

@section('content_right')
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h2 class="box-title show_goods_title">{{$GoodsData['goods_name']}}</h2>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
    	<div class="col-md-7 col-sm-7 col-xs-12">
            <picture >
                <source media="(max-width: 600px)" srcset="https://***REMOVED***.com/***REMOVED***/{{$GoodsData['goods_thumb']}}">
                <source media="(max-width: 992px)" srcset="https://***REMOVED***.com/***REMOVED***/{{$GoodsData['goods_img']}}">
                <img src="https://***REMOVED***.com/***REMOVED***/{{$GoodsData['original_img']}}" alt="{{$GoodsData['goods_name']}}" style="max-width:100%;">   
            </picture>     		

    	</div>
        
        <div class="col-md-5 col-sm-5 col-xs-12 in_goods_info">    	
            <h4>商品編號:{{ $GoodsData['goods_sn'] }}</h4>
            <h4>商品售價: <span style='color:ff4d4d;'>{{ round($GoodsData['shop_price']) }}</span></h4>
            <p><label for="in_goods_num">數量:</label>
                 
                @if( $GoodsData['goods_number'] > 0)
                <select name='in_goods_num' id='in_goods_num' class='form-control'> 
                    @for( $num = 1 ; $num <= $GoodsData['goods_number'] ; $num ++ )
                    <option value="{{$num}}" >{{$num}}</option>
                    @endfor
                </select>
                @else
                <span id='prepare' > 補貨中 </span>
                @endif

            </p>
            <span class="btn bg-maroon btn-flat margin  @if( $GoodsData['goods_number'] < 1) disabled @endif inner_add_to_cart" goods_id="{{ $GoodsData['goods_id'] }}">加入購物車</span>        	
        </div>
    </div>
    <!-- /.box-body -->
</div>

<div class="box box-solid">
    
    <div class="box-header with-border">

 
    </div>
    
    <!-- /.box-header -->
    <div class="box-body in_goods_des">
        @foreach( $goodsImgs as $goodsImgk => $goodsImg )

            
            <img
                    
         src="https://***REMOVED***.com/***REMOVED***/{{$goodsImg['img_original']}}" class="lazyload" alt="{{$GoodsData['goods_name']}}-商品詳細圖-{{$goodsImgk+1}}"/>
            
         
        @endforeach
        {!!$GoodsData['goods_desc']!!}
    </div>
    <!-- /.box-body -->
</div>
@endsection

@section('selfjs')
<script type="text/javascript">

/*
|--------------------------------------------------------------------------
| 商品內頁加入購物車
|--------------------------------------------------------------------------
| 由於商品詳細頁中 , 可以選擇數量 , 所以要另外區別
|
*/
$(function(){

    $(".inner_add_to_cart").click(function(){

        var in_goods_id  = $(this).attr('goods_id');
        var in_goods_num = $("#in_goods_num").val();
        
        if (typeof in_goods_num === "undefined") {
            
            toastr.warning('本商品目前無庫存');
            return;

        }

        var request = $.ajax({
            url: "{{url('/add_to_cart')}}",
            method: "POST",
            data: { goods_id : in_goods_id ,
                    _token: "{{ csrf_token() }}",
                    number: in_goods_num,
                  },
            dataType: "json"
        });
 
        request.done(function( res ) {

            if( res['res'] == false ){

                var alert_text = '';

                $.each(res['data'] , function( errork, errorv){

                    $.each(errorv , function( errork2, errorv2){
                        alert_text += errorv2;
                    });

                });
                
                toastr.warning(alert_text);
            }else{

                toastr.success('成功加入購物車');
                // 如果順利加入購物車 , 就重整購物車內容
                $(".cart_list_area").empty();
                
                var num_in_cart = 0;

                $.each( res['data'] , function( listk , listv ){
                    
                    var tmp_goods = "<table class='cart_table' width='100%'>";
                    /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
                    
                    num_in_cart += parseInt(listv['num']);

                    tmp_goods += "<tr><td class='tableimg'><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'></td>"+
                                      "<td width='30%'>×"+listv['num']+"="+listv['subTotal']+"</td>"+
                                      "<td width='30%'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'><i class='fa fa-fw fa-remove'></i></span></td></tr>";
                    tmp_goods += "</table>";
                    
                    $(".cart_list_area").append( tmp_goods );
                });

                $(".num_in_cart").empty();
                $(".num_in_cart").append(num_in_cart);                
            }

        });
 
        request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    })
    
});

</script>
@endsection