<!-- 瀏覽紀錄模組 2020/07/14 -->
<div id='viewed_goods_box'>

    <div id='viewed_goods_box_title'>
    	<p>瀏覽紀錄</p>
    </div>

    <div id='viewed_goods_box_in'>
        
        @foreach( $viewed_goods as $viewed_goodk => $viewed_good )
        <a href="{{url('/show_goods/'.$viewed_good['goods_id'])}}" target="_blank">
            <div class='viewed_goods_item' >
                <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$viewed_good['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="">
            </div>
        </a>
        @endforeach
        
        <div id='viewed_switch' >
            <p class='fa fa-fw fa-chevron-left'></p>
            展<br>開
        </div>
    </div>

</div>
