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
<!-- 設定區塊 -->
<div class="box box-solid">
    
    <div class="box-header with-border">
        
        <i class="fa fa-fw fa-sort-amount-asc"></i>

        <h3 class="box-title">排序</h3>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        <a class="btn btn-app @if( $CatSortItem == 'add_time')bg-yellow  @if( $CatSortWay == 'asc')sortup @else sortdown @endif @endif" 
            href="{{url($AddTimeURL)}}"
        >
            <i class="fa fa-calendar-times-o"></i> 上架時間 
        </a>

        <a class="btn btn-app @if( $CatSortItem == 'shop_price')bg-yellow  @if( $CatSortWay == 'asc')sortup @else sortdown @endif @endif" 
            href="{{url($PriceUrl)}}"
        >
            <i class="fa fa-dollar"></i> 價格
        </a>

    </div>
    <!-- /.box-body -->

</div>
<!-- /設定區塊 -->

<!-- 呈現區塊 -->
<div class="box box-solid">
    
<!--     <div class="box-header with-border">
        
        <i class="fa fa-text-width"></i>

        <h3 class="box-title">Text Emphasis</h3>
    </div> -->
    
    <!-- /.box-header -->
    <div class="box-body">
        @foreach( $Goods as $Goodk => $Good)
        <a href="{{url('/show_goods/'.$Good['goods_id'])}}">
        <div class='col-md-3 col-sm-4 col-xs-6'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$Good['goods_thumb']}}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $Good['goods_name'] }}</h4>
                    <p>{{ $Good['shop_price'] }}</p>
                    <p><a class="btn colorbtn add_to_cart" role="button" goods_id="{{$Good['goods_id']}}">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        </a>
        @endforeach
        <div class="col-md-12 col-sm-12 col-xs-12">
        	{!! $Pages !!}
        </div>    	
        
    </div>
    <!-- /.box-body -->

</div>
<!-- /呈現區塊 -->
@endsection

@section('selfjs')

@endsection