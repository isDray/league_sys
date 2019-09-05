@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @includeIf('block_'. $LeftBlock)

    @endforeach

@endsection

@section('content_right')
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h2 class="box-title">{{$GoodsData['goods_name']}}</h2>
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
        
        <div class="col-md-5 col-sm-5 col-xs-12">    	
            
            <h2>商品售價:{{ round($GoodsData['shop_price']) }}</h2>

            <h2>商品編號:{{ $GoodsData['goods_sn'] }}</h2>
           
            <p><label for="num">數量:</label>
                 
                @if( $GoodsData['goods_number'] > 0)
                <select name='num' id='num'> 
                    @for( $num = 1 ; $num <= $GoodsData['goods_number'] ; $num ++ )
                    <option value="{{$num}}" >{{$num}}</option>
                    @endfor
                </select>
                @else
                <span id='prepare' > 補貨中 </span>
                @endif

            </p>
            <span class="waves-effect waves-light btn pink accent-1  @if( $GoodsData['goods_number'] < 1) disabled @endif inner_add_to_cart" value="{{ $GoodsData['goods_id'] }}">加入購物車</span>        	
        </div>
    </div>
    <!-- /.box-body -->
</div>

<div class="box box-solid">
    
    <div class="box-header with-border">

 
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
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

@endsection