@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;
$bannsers = Lib_block::banner();
$hots = file_get_contents( public_path().'/top3.json' );

$hots =  json_decode($hots,true);


@endphp

<style type="text/css">
#topbuy{  
}
</style>

@if( count($bannsers) > 0 )
<div class='block_banner box box-solid'>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        <div class='col-md-8 col-sm-12 col-xs-12 _np'>

        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            
            <ol class="carousel-indicators">
                @foreach( $bannsers as $bannserk => $bannser)
                <li data-target="#carousel-example-generic" data-slide-to="{{$bannserk}}" class="@if( $bannserk == 0) active @endif"></li>              
                @endforeach                
            </ol>
            
            <div class="carousel-inner">
                
                @foreach( $bannsers as $bannserk => $bannser)
                
                <div class="item @if( $bannserk == 0) active @endif">
                    <a href="{{$bannser['url']}}">
                    <img src="{{url('/banner/'.$bannser['user_id'].'/'.$bannser['banner'])}}" alt="{{$bannser['des']}}">
                    </a> 
                    <div class="carousel-caption">
                    
                    </div>
                </div>        
                       
                @endforeach

            </div>
            
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>

        </div>
        

        <div class='col-md-4 col-sm-12 col-xs-12 _np' id='topbuy'>
            <div id='topbuytitle'><h2 class='_nm'>本週熱銷排行</h2></div>
            @foreach( $hots as $hotk => $hot)
            <a href="">
            <div class='col-md-12 top_recommend'>
                
                <div class='col-md-6 col-sm-4 col-xs-2 top_recommend_img'>
                    <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$hot['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="{{ $hot['goods_name'] }},貨號:{{ $hot['goods_sn'] }},價格:{{ $hot['shop_price'] }}">
                </div>

                <div class='col-md-6 col-sm-8 col-xs-10 top_recommend_text _np'>
                    <div class='top_recommend_name'>
                        {{$hot['goods_name']}}
                    </div>
                    <div class='top_recommend_sn'>
                        貨號:{{ $hot['goods_sn'] }}
                    </div>
                    <div class='top_recommend_price'>
                        {{ $hot['shop_price'] }}<small>元</small>
                    </div>
                </div>

            </div>
            </a>
            @endforeach

        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- 輪播banner -->   
@endif
<style type="text/css">
#topbuy{
    min-height:100%;
    border:1px solid black;    
}
#topbuytitle{
    height: 40px;
    background-color: black;
    color:white;
    padding-left: 15px;

}
#topbuytitle > h2 {
    font-family: '微軟正黑體';
    line-height: 40px;
    font-size: 18px;    
}
.top_recommend{
    height: calc( (96% - 40px) / 3  );
    margin-top: 1%;
    overflow: hidden;
    transition: .3s;
}
.top_recommend:hover{
    background-color: #ececec;
}

.top_recommend_img{
    height: 100%;
    margin-top: calc( 2% - 2px );    
}
@media( max-width:991px){
    .top_recommend_img{
        margin-top: 0px;
        padding: 0px;
    }
}
.top_recommend_img img{
    max-width: 100%;
    max-height: 96%;

}
.top_recommend_text{
    height: 96%;
    margin-top: calc( 2% - 2px );
}
@media( max-width:991px){
    .top_recommend_text{
        margin-top: 0px;
    }
}
.top_recommend_name , .top_recommend_sn , .top_recommend_price{
    font-family: '微軟正黑體';
}
.top_recommend_name {
    color: #444444;
    height: 40px;
    line-height: 20px;
    overflow: hidden;
    font-weight: 900;
    font-size: 16px;
}
.top_recommend_sn{
    font-size: 16px;
    color: #4c4c4c;
    height: 20px;
}
.top_recommend_price{
    height: 20px;
    color: #ff6464;
    font-size: 16px;
    font-weight: 900;    
}
@media( max-width:1400px){
.top_recommend{
    padding: 0px;
}    
.top_recommend_name {
    color: #444444;
    height: 30px;
    line-height: 15px;
    overflow: hidden;
    font-weight: 900;
    font-size: 14px;
}
.top_recommend_sn{
    font-size: 14px;
    color: #4c4c4c;
    height: 15px;
}
.top_recommend_price{
    height: 15px;
    color: #ff6464;
    font-size: 14px;
    font-weight: 900;
}
@media( max-width:1100px){
.top_recommend{
    padding: 0px;
}    
.top_recommend_name {
    color: #444444;
    height: 26px;
    line-height: 13px;
    overflow: hidden;
    font-weight: 900;
    font-size: 12px;
}
.top_recommend_sn{
    font-size: 12px;
    color: #4c4c4c;
    height: 13px;
}
.top_recommend_price{
    height: 13px;
    color: #ff6464;
    font-size: 12px;
    font-weight: 900;
}    
}

@media( max-width:991px){
#topbuy{
    margin-top: 20px;
}
.top_recommend{
    padding-left: 15px;
    padding-right: 15px;    
}      
}

}

</style>
<script type="text/javascript">
window.onload = function(){
    
    targetHeight = $(".carousel ").outerHeight();
    
    deviceWidth = $(window).width();
    
    if( deviceWidth < 500 ){
        
        $("#topbuy").height( (targetHeight * 1.4 - 2) );

    }else{
        
        $("#topbuy").height( targetHeight - 2  );
    }
}
</script>       