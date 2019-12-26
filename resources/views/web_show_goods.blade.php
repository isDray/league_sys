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
{!!$Breadcrum!!}
<div class="box box-solid">
    
<!--     <div class="box-header with-border">

        <h1 class="box-title show_goods_title">{{$GoodsData['goods_name']}}</h1>
    </div> -->
    
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
            <h1 class="box-title show_goods_title">{{$GoodsData['goods_name']}}</h1>

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

        <!------- -->
                @if($GoodsData['video_name'])
                <div class="wrappers video_wrapper">
                 
                    <video class='video self_video' id='self_video' controls controlsList='nodownload'>
                        <source src="http://127.0.0.1/***REMOVED***2/video/{{$GoodsData['video_name']}}#t=0.01" type='video/mp4'>
                    </video>

                    <div class="playpause">
                        <i class="glyphicon glyphicon-play align-middle" style="font-size:40px;position: absolute;top:calc(50% - 20px );left:calc(50% - 16px);"></i>
                    </div>

                
                    <!-- 控制 -->
                    <div class="player-container">
                    
                        <div class="play-control">
                        
                            <div class="play-button"><i class="fa fa-play"></i></div>

                            <div class="pause-button"><i class="fa fa-pause"></i></div>

                        </div>

                        <div class="volume-control">
                        
                            <div class="volume-button"><i class="fa fa-volume-up"></i></div>

                            <div class="volume-button-mute"><i class="fa fa-volume-off"></i></div>

                        </div>

                        <div class="indicator">0:00 / 0:00</div>

                        <div class="progress">
                        
                            <div class="progress-background"></div>

                            <div class="progress-over"></div>

                            <div class="progress-hidden"></div>

                        </div>

                        <div class="fullscreen-button"><i class="fa fa-arrows-alt"></i></div>

                    </div>                              
                    <!-- /控制 -->
                

                </div>
                @endif        
        <!------- -->
        @foreach( $goodsImgs as $goodsImgk => $goodsImg )

            
        <img src="https://***REMOVED***.com/***REMOVED***/{{$goodsImg['img_original']}}" class="lazyload" alt="{{$GoodsData['goods_name']}}-商品詳細圖-{{$goodsImgk+1}}"/>
        <br>
            
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
                
                var cartTotal = 0;
                
                $.each( res['data'] , function( listk , listv ){
                    cartTotal += listv['subTotal'];                    
                    var tmp_goods = "<table class='cart_table' width='100%'>";
                    /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
                    
                    num_in_cart += parseInt(listv['num']);

                    tmp_goods += '<tr><table>';
                    tmp_goods += "<tr><td class='tableimg cart_img_box' colspan='2' ><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'><span>"+listv['name']+"</span></td></tr>"+
                                              "<tr><td>$"+listv['goodsPrice']+"×"+listv['num']+"="+listv['subTotal']+"</td>"+
                                              "<td align='right' ><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'>刪除</span></td></tr>";
        
                    tmp_goods += '<table><tr>';
                    tmp_goods += "</table>";
                            
                    $(".cart_list_area").append( tmp_goods );
                });

                $(".cart_list_area").append( '<p>小計'+cartTotal+'</p>' );

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

<script type="text/javascript">
$('video').show().bind('ended', function () { 
    this.currentTime = 0;
});

$('.video').bind('canplay', function (e) {

    if( $('.video').get(0).paused ){

        $(".playpause").fadeIn(2500);
    } 



});
$('.video').bind('play', function (e) {

    $(".playpause").fadeOut();

});

$('.video').bind('pause', function (e) {

    $(".playpause").fadeIn();

});

$(".playpause").bind('click',function(){

    $('.video').get(0).play();
  
    $(".play-button").css("display", "none");

    $(".pause-button").css("display", "table-cell");
})
</script>

<style type="text/css">
/*----CCss*/
@charset "UTF-8";

/* ______________________________ GENERAL-SETTINGS ______________________________ */
/**{margin: 0px; padding: 0px;}*/


.container-inner {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
}

.video-player-container{
    width: 648px;
    height: 350px;
    z-index: 2;
}

.video-container {
    height: 310px;
    background-color: black;
}

#media-video {
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.player-container {
    height: 40px;
    background-color: #f6f6f6;
    width: 100%;
    position: absolute;
    left:0px;
    bottom: -40px;
    z-index: 23;
}

.play-control, .volume-control {
    width: 40px;
    height: 40px;
    display: table;
    border-right: 1px solid #dddddd;
    cursor: pointer;
    float: left;
}

.play-button, .volume-button-mute {
    vertical-align: middle;
    text-align: center;
    display: none;
}

.pause-button, .volume-button {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
}

.play-control .fa {
    font-size: 0.6em;
}

.volume-control .fa {
    font-size: 0.8em;
}

.indicator {
    width: 100px;
    height: 40px;
    border-right: 1px solid #dddddd;
    font-family: "Open Sans", Arial, Helvetica, sans-serif;
    font-size: 11px;
    text-align: center;
    float: left;
    line-height: 40px;
}

.progress {
    width: 425px;
    height: 40px;
    float: left;
    -webkit-box-shadow:0px 0px 0px white!important;
}

.progress-background {
    position: absolute;
    width: 385px;
    height: 5px;
    background-color: gray;
    margin-left: 20px;
    margin-top: 20px;
}

.progress-over {
    position: absolute;
    width: 0px;
    height: 5px;
    background-color: #5e9f62;
    margin-left: 20px;
    margin-top: 20px;
}

.progress-hidden {
    position: absolute;
    width: 385px;
    height: 40px;
    margin-left: 20px;
    cursor: pointer;
}

.fullscreen-button {
    width: 40px;
    height: 40px;
    float: right;
    display: table;
    cursor: pointer;
}

.fullscreen-button i {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
    border-left: 1px solid #dddddd;
}
.video_wrapper{
    text-align: center;
}
/*----*/
.playpause{
    border-radius: 30px;
    background-color: #c5c5c5;
    text-align: center;
    display: none;
}
.ytbox{
    width: 500px;
    margin-bottom: 20px;
}
.self_video{
    width: 500px;
    /*margin-bottom: 20px;*/
}
.wrappers{
    display: inline-block;
    position: relative;
    margin-bottom: 40px;
}
.playpause {
    font-size: 20px;
    text-align: center;
    width: 60px;
    height: 60px;
    position:absolute;
    left:0%;
    right:0%;
    top:0%;
    bottom:0%;
    margin:auto;
    background-size:contain;
    background-position: center;
    z-index: 20;
}
.one_minute_download{
    border-radius: 4px!important;
}

@media (max-width:992px){
.playpause{
    display: none!important;
}
.ytbox{
    width: 100%;
    margin-bottom: 20px;
}
.self_video{
    max-width: 100%; 
    margin-bottom: 20px;
}
}

</style>
<script type="text/javascript">
mainFunction = 
{
    elements: {
        container           : ".container",
        containerInner      : ".container-inner",
        videoPlayerContainer: ".video-player-container",
        videoContainer      : ".video_wrapper",
        playerContainer     : ".player-container",
        mediaVideo          : "#self_video",
        playControl         : ".play-control",
        playButton          : ".play-button",
        pauseButton         : ".pause-button",
        volumeControl       : ".volume-control",
        volumeButton        : ".volume-button",
        volumeButtonMute    : ".volume-button-mute",
        progress            : ".progress",
        progressOver        : ".progress-over",
        progressHidden      : ".progress-hidden",
        progressBackground  : ".progress-background",
        indicator           : ".indicator",
        fullScreenButton    : ".fullscreen-button"
    },

    isPlay      : false,
    isVolume    : true,
    isEnd       : false,
    progressBarHeight :50,

    init: function(){  
        
        mainFunction.defaultSettings();
        mainFunction.clickSettings();
        mainFunction.playControlVideo();
        mainFunction.volumeControlVideo();
        mainFunction.progressControlVideo();
        //mainFunction.mouseHideControl();

        document.addEventListener('fullscreenchange', mainFunction.exitHandler);
        document.addEventListener('webkitfullscreenchange', mainFunction.exitHandler);
        document.addEventListener('mozfullscreenchange', mainFunction.exitHandler);
        document.addEventListener('MSFullscreenChange', mainFunction.exitHandler);      
    },

    defaultSettings: function(){
        
    
        $(mainFunction.elements.mediaVideo)[0].controls = false;

        $(window).on("resize", onResize);

        function onResize(){

            $(mainFunction.elements.progress).width( $(mainFunction.elements.playerContainer).width() - 223 );
            $(mainFunction.elements.progressBackground).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
            $(mainFunction.elements.progressHidden).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
            mainFunction.progressBarHeight = $(mainFunction.elements.playerContainer).width() - 223 - 40;
            $(mainFunction.elements.progressOver).css("width", String( (mainFunction.progressBarHeight / $(mainFunction.elements.mediaVideo)[0].duration) * $(mainFunction.elements.mediaVideo)[0].currentTime ));
        }

        onResize();

    },
    clickSettings: function(){
        $(mainFunction.elements.playControl).on("click", mainFunction.playControlVideo);
        $(mainFunction.elements.volumeControl).on("click", mainFunction.volumeControlVideo);
        $(mainFunction.elements.mediaVideo).on("click", mainFunction.playControlVideo);
        $(mainFunction.elements.fullScreenButton).on("click", mainFunction.fullScreenControl);
        $("body").on("keyup", function(e){ if(e.which == 27) { mainFunction.exitFullScreen(); } });
    },

    fullScreenControl:function(){
        
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement)
        {   
            
            if (document.documentElement.requestFullscreen)
            {  
                $(".video_wrapper")[0].requestFullscreen();
                mainFunction.onFullScreen();
            }
            else if (document.documentElement.msRequestFullscreen)
            {
                // document.documentElement.msRequestFullscreen();
                $(".video_wrapper")[0].msRequestFullscreen();
                mainFunction.onFullScreen();
            }
            else if (document.documentElement.mozRequestFullScreen)
            {
                // document.documentElement.mozRequestFullScreen();
                $(".video_wrapper")[0].mozRequestFullScreen();
                mainFunction.onFullScreen();
            }
            else if (document.documentElement.webkitRequestFullscreen)
            {
                // document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                $(".video_wrapper")[0].webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                mainFunction.onFullScreen();
            }
        }
        else
        {   
            if (document.exitFullscreen)
            {
                document.exitFullscreen();
                mainFunction.exitFullScreen();
            }
            else if(document.msExitFullscreen)
            {
                document.msExitFullscreen();
                mainFunction.exitFullScreen();
            }
            else if (document.mozCancelFullScreen)
            {
                document.mozCancelFullScreen();
                mainFunction.exitFullScreen();
            }
            else if (document.webkitExitFullscreen)
            {
                document.webkitExitFullscreen();
                mainFunction.exitFullScreen();
            }
        }
    },

    onFullScreen: function(){

        $(mainFunction.elements.progressOver).css("width", String( (mainFunction.progressBarHeight / $(mainFunction.elements.mediaVideo)[0].duration) * $(mainFunction.elements.mediaVideo)[0].currentTime ));
        $(mainFunction.elements.container).css("display", "block");
        $(mainFunction.elements.videoPlayerContainer).width("100%");
        $(mainFunction.elements.videoPlayerContainer).height("100%");
        $(mainFunction.elements.videoContainer).height("calc(100% - 40px)");
        $(mainFunction.elements.progress).width( $(mainFunction.elements.playerContainer).width() - 223 );
        $(mainFunction.elements.progressBackground).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        $(mainFunction.elements.progressHidden).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        
        mainFunction.progressBarHeight = $(mainFunction.elements.playerContainer).width() - 223 - 40;
        $(".self_video").css('width', 'auto');
        
        if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
            $(".self_video").css('height', "calc(100% - 0px)");
        }else{

            $(".player-container").css('bottom','0px');
            $(".self_video").css('height', "calc(100% - 40px)");
        }
        //console.log(window.screen.availHeight);
        $(".container-inner").css("display", "none");

    },

    exitFullScreen: function(){
        
        // $(mainFunction.elements.container).css("display", "-webkit-box");
        // $(mainFunction.elements.container).css("display", "-moz-box");
        // $(mainFunction.elements.container).css("display", "-ms-flexbox");
        // $(mainFunction.elements.container).css("display", "-webkit-flex");
        // $(mainFunction.elements.container).css("display", "flex");
        $(mainFunction.elements.videoPlayerContainer).width("648");
        $(mainFunction.elements.videoPlayerContainer).height("350");
        $(mainFunction.elements.videoContainer).height("500");
        $(mainFunction.elements.progress).width( $(mainFunction.elements.playerContainer).width() - 223 );
        $(mainFunction.elements.progressBackground).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        $(mainFunction.elements.progressHidden).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        mainFunction.progressBarHeight = $(mainFunction.elements.playerContainer).width() - 223 - 40;
        $(mainFunction.elements.progressOver).css("width", String( (mainFunction.progressBarHeight / $(mainFunction.elements.mediaVideo)[0].duration) * $(mainFunction.elements.mediaVideo)[0].currentTime ));
        
        $(".self_video").css('width', '500px');
        $(".self_video").css('height', "auto");
        $(".player-container").css('bottom','-40px');
        //.css('bottom', "0px");

        $(".container-inner").css("display", "inherit");

    },

    exitHandler: function () {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {

        $(mainFunction.elements.videoPlayerContainer).width("648");
        $(mainFunction.elements.videoPlayerContainer).height("350");
        $(mainFunction.elements.videoContainer).height("500");
        $(mainFunction.elements.progress).width( $(mainFunction.elements.playerContainer).width() - 223 );
        $(mainFunction.elements.progressBackground).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        $(mainFunction.elements.progressHidden).width( $(mainFunction.elements.playerContainer).width() - 223 - 40 );       
        mainFunction.progressBarHeight = $(mainFunction.elements.playerContainer).width() - 223 - 40;
        $(mainFunction.elements.progressOver).css("width", String( (mainFunction.progressBarHeight / $(mainFunction.elements.mediaVideo)[0].duration) * $(mainFunction.elements.mediaVideo)[0].currentTime ));
        
        $(".self_video").css('width', '500px');
        $(".self_video").css('height', "auto");
        $(".player-container").css('bottom','-40px');
        $(".container-inner").css("display", "inherit");
        }
    }, 
    mouseHideControl: function(){

        var mouseHide = setTimeout(onMouseHide, 3000);

        $(mainFunction.elements.containerInner).on("mousemove", function(){

            clearTimeout( mouseHide );

            onMouseShow();

        });

        $(mainFunction.elements.containerInner).on("mousemoveend", function(){

            clearTimeout( mouseHide );

            mouseHide = setTimeout(onMouseHide, 3000);

        });

        function onMouseHide(){ 
            $("body").css("cursor", "none"); 
        }

        function onMouseShow(){ 
            $("body").css("cursor", "inherit"); 
        }

    },

    playControlVideo: function(){
        if(mainFunction.isPlay){
            $(mainFunction.elements.mediaVideo)[0].play(); 
        } else { 
            $(mainFunction.elements.mediaVideo)[0].pause(); 
        }
        $(mainFunction.elements.playButton).css("display", ( (mainFunction.isPlay) ? "none" : "table-cell" ) );
        $(mainFunction.elements.pauseButton).css("display", ( (!mainFunction.isPlay) ? "none" : "table-cell" ) );
        mainFunction.isPlay = !mainFunction.isPlay;
        mainFunction.isEnd = false;
    },

    volumeControlVideo: function(){
        $(mainFunction.elements.mediaVideo)[0].muted = !mainFunction.isVolume;
        $(mainFunction.elements.volumeButtonMute).css("display", ( (mainFunction.isVolume) ? "none" : "table-cell" ) );
        $(mainFunction.elements.volumeButton).css("display", ( (!mainFunction.isVolume) ? "none" : "table-cell" ) );
        mainFunction.isVolume = !mainFunction.isVolume;
    },

    progressControlVideo: function(){
        
        var mouseX          = 0;
        var isDown          = false;
        var currentMinute   = 0;
        var currentSecond   = 0;
        var mediaPlayer     = $(mainFunction.elements.mediaVideo)[0];

        mediaPlayer.addEventListener("timeupdate", onProgressVideo, false);

        function onProgressVideo(){

            $(mainFunction.elements.progressOver).css("width", String( (mainFunction.progressBarHeight / mediaPlayer.duration) * mediaPlayer.currentTime ));
            videoEndControl();
            setIndicator(mediaPlayer.currentTime, mediaPlayer.duration);
        }

        function videoEndControl(){
            if(mediaPlayer.currentTime >= mediaPlayer.duration)
            {
                mainFunction.isPlay = false;
                mainFunction.playControlVideo();
                mainFunction.isEnd = true;
            }
        }

        function setIndicator(current, duration){
            var durationMinute      = Math.floor(duration / 60);
            var durationSecond      = Math.floor(duration - durationMinute * 60);
            var durationLabel       = durationMinute + ":" + durationSecond;
            currentSecond           = Math.floor(current);
            currentMinute           = Math.floor(currentSecond / 60);
            currentSecond           = currentSecond - ( currentMinute * 60 );
            currentSecond           = ( String(currentSecond).length > 1 ) ? currentSecond : ( String("0") + currentSecond );
            var currentLabel        = currentMinute + ":" + currentSecond;
            var indicatorLabel      = currentLabel + " / " + durationLabel;
            $(mainFunction.elements.indicator).text( indicatorLabel );
        }

        // $(mainFunction.elements.progressHidden).on("mousemove", onProgressHiddenMouseMove);
        $(mainFunction.elements.progressHidden).on("click", onProgressHiddenMouseMove);



        function onProgressHiddenMouseMove(e){
            var parentOffset    = $(this).parent().offset(); 
            mouseX              = Math.floor( e.pageX - parentOffset.left - 20 );
            if(isDown) { mediaPlayer.currentTime = (mediaPlayer.duration / mainFunction.progressBarHeight) * mouseX; }
        }

        $(mainFunction.elements.progressHidden).on("click", function(){ if(!isDown) { mediaPlayer.currentTime = (mediaPlayer.duration / mainFunction.progressBarHeight) * mouseX; } });

        $(mainFunction.elements.progressHidden).on("mousedown", onProgressHiddenMouseDown);

        function onProgressHiddenMouseDown(){

            isDown = true;

            mediaPlayer.currentTime = (mediaPlayer.duration / mainFunction.progressBarHeight) * mouseX;

            $(mainFunction.elements.mediaVideo)[0].pause();
        }

        //$(mainFunction.elements.progressHidden).on("mouseup", function(){ isDown = false; if(!mainFunction.isEnd) {  mainFunction.isPlay = true; mainFunction.playControlVideo(); } });

        //$(mainFunction.elements.progressHidden).on("mouseout", function(){ isDown = false; if(!mainFunction.isEnd) {  mainFunction.isPlay = true; mainFunction.playControlVideo(); } });

    }
};

// 加載完成初始化
function isMobile() {

  try{ document.createEvent("TouchEvent"); return true; }

  catch(e){ return false;}

}

if(isMobile()){

    $(".player-container").hide();

}else{

    $(document).on("ready", mainFunction.init.call());

}
</script>

@endsection