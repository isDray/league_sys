@php

  use App\Cus_lib\Lib_block;
  
  use Illuminate\Http\Request;
  
  $custom_ad = Lib_block::get_custom_ad($id);
  
@endphp
@if($custom_ad)
<div class="box box-solid cga cga{{$custom_ad['background-color']}}  @if( $custom_ad['rorl'] == 1) cgal @endif">
    
    <div class="box-body">
        <h1>{{$custom_ad['title']}}</h1>

        @if( $custom_ad['rorl'] == 1)
        <div class='col-md-4 col-sm-4 col-xs-12'>
        	<div class="gadanimate{{$custom_ad['animate']}} limg imgbox">
            <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$custom_ad['img']}}" data-holder-rendered="true" class="lazyload" alt="" src="https://***REMOVED***.com/***REMOVED***/{{$custom_ad['img']}}">
            </div>
        </div>         
        <div class='col-md-8 col-sm-8 col-xs-12 cga_desc'>
            <p>
        	    {{$custom_ad['descript']}}
            </p>

        	<a href="{{$custom_ad['link']}}"><div class='cga_more col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-6' >了解更多</div></a>
        </div>
        @elseif($custom_ad['rorl'] == 2)
        <div class='col-md-8 col-sm-8 col-xs-12 cga_desc'>
            <p>
        	    {{$custom_ad['descript']}}
            </p>
        	<a href="{{$custom_ad['link']}}"><div class='cga_more col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-0'>了解更多</div></a>
        </div>
        <div class='col-md-4 col-sm-4 col-xs-12'>
        	<div class="gadanimate{{$custom_ad['animate']}} rimg imgbox">
            <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$custom_ad['img']}}" data-holder-rendered="true" class="lazyload" alt="" src="https://***REMOVED***.com/***REMOVED***/{{$custom_ad['img']}}">
            </div>
        </div>
        @endif
    </div>
</div>

@endif