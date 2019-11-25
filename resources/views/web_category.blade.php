@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@if( isset( $page_header ) )
    @section('page_header'){{$page_header}}@endsection
@endif

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @includeIf('block_'. $LeftBlock)

    @endforeach

@endsection

@section('content_right')
<!-- 設定區塊 -->
<!-- <div class="box box-solid">
    
    <div class="box-header with-border">
        
        <i class="fa fa-fw fa-sort-amount-asc"></i>

        <h3 class="box-title">排序</h3>
    </div>
    

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


</div> -->
<!-- /設定區塊 -->

<!-- 呈現區塊 -->
<div class="box box-solid">
    
    <div class="box-header with-border">
        
        <a class="btn @if( $CatSortItem == 'add_time')bg-yellow  @if( $CatSortWay == 'asc')sortup @else sortdown @endif @else btn-default @endif" 
            href="{{url($AddTimeURL)}}" title="點擊改變排序為@if( $CatSortWay == 'asc')時間由大到小@else時間由小到大@endif">
            <i class="fa fa-calendar-times-o"></i> 上架時間 
        </a>

        <a class="btn @if( $CatSortItem == 'shop_price')bg-yellow  @if( $CatSortWay == 'asc')sortup @else sortdown @endif @else btn-default @endif" 
            href="{{url($PriceUrl)}}" title="點擊改變排序為@if( $CatSortWay == 'asc')價格由大到小@else價格由小到大@endif">
            <i class="fa fa-dollar"></i> 價格
        </a>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        @foreach( $Goods as $Goodk => $Good)
        <a href="{{url('/show_goods/'.$Good['goods_id'])}}" title="查看商品:{{$Good['goods_name']}}詳細內容">
        <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$Good['goods_thumb']}}" alt="{{ $Good['goods_name'] }},貨號:{{ $Good['goods_sn'] }},價格:{{ $Good['shop_price'] }}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $Good['goods_name'] }}</h4>
                    <p class='goods_sn'>貨號:{{ $Good['goods_sn'] }}</p>
                    <p class='goods_price'>價格:{{ $Good['shop_price'] }}</p>
                    <p><a class="btn colorbtn add_to_cart" role="button" goods_id="{{$Good['goods_id']}}" title="將{{$Good['goods_name']}}加入購物車">立即購買</a></p>
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