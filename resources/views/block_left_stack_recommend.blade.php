@php
  
  use App\Cus_lib\Lib_block;
  
  use Illuminate\Http\Request;
  
  $stacks = Lib_block::get_stack($id);
  

@endphp

<style type="text/css">
ol, ul { list-style: none }

blockquote, q { quotes: none }
/* main */

.container {
  position: relative;
  width: 100%;
  /*height: 440px;*/
}

.card {
  position: absolute;
  top: 0;
  left: 0;
  background: #c4c4c4;
  width: 100%;
  height: 240px;
  border-radius: 4px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  -webkit-transform-origin: center;
  transform-origin: center;
  -webkit-transition: 0.4s cubic-bezier(0.28, 0.55, 0.385, 1.65);
  transition: 0.4s cubic-bezier(0.28, 0.55, 0.385, 1.65);
  cursor: pointer;
}

.card:nth-child(1) {
  z-index: 10;
  top: 0px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(1);
  transform: scale(1);
  opacity: 1;
}

.card:nth-child(2) {
  z-index: 9;
  top: -15px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.9);
  transform: scale(0.9);
  opacity: 0.9;
}

.card:nth-child(3) {
  z-index: 8;
  top: -30px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.8);
  transform: scale(0.8);
  opacity: 0.8;
}

.card:nth-child(4) {
  z-index: 7;
  top: -45px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.7);
  transform: scale(0.7);
  opacity: 0;
}

.card:nth-child(5) {
  z-index: 6;
  top: -60px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.6);
  transform: scale(0.6);
  opacity: 0;
}

.card:nth-child(6) {
  z-index: 5;
  top: -75px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.5);
  transform: scale(0.5);
  opacity: 0;
}

.card:nth-child(7) {
  z-index: 4;
  top: -90px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.4);
  transform: scale(0.4);
  opacity: 0;
}

.card:nth-child(8) {
  z-index: 3;
  top: -105px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.3);
  transform: scale(0.3);
  opacity: 0;
}

.card:nth-child(9) {
  z-index: 2;
  top: -120px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.2);
  transform: scale(0.2);
  opacity: 0;
}

.card:nth-child(10) {
  z-index: 1;
  top: -135px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.1);
  transform: scale(0.1);
  opacity: 0;
}

.card:nth-child(11) {
  z-index: 0;
  top: -150px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0);
  transform: scale(0);
  opacity: 0;
}

.card:first-child:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
  -webkit-transform: scale(1.05);
  transform: scale(1.05);
}

.card:last-child { opacity: 0; }

.title {
  font-size: 36px;
  font-weight: 300;
  color: #FFF;
  text-align: center;
  margin-top:150px;
}

.title p {
  padding: 10px 0 0;
  font-size: 12px;
  opacity: 0.8;
}

.container { margin: 20px auto}; 

.card {
  color: rgba(0, 0, 0, 0.2);
  font-size: 12px;
  text-align: center;
}
.card span img{
	width: 100%;
}

.stacks_box{
  height: 300px;
  width: 100%;
  margin-bottom: 100px;
}
.stack_header{
  color:orange;
  text-align: center;
}
.stack_header h3{
  font-family: 'cwTeXYen', sans-serif;
  font-size: 30px!important;
/*   text-shadow: 0 0 5px #f562ff, 0 0 15px #f562ff, 0 0 25px #f562ff,
    0 0 20px #f562ff, 0 0 30px #890092, 0 0 80px #890092, 0 0 80px #890092;
  color: #fccaff; */
  text-shadow: 0 0 5px #ffa500, 0 0 15px #ffa500, 0 0 20px #ffa500, 0 0 40px #ffa500, 0 0 60px #ff0000, 0 0 10px #ff8d00, 0 0 98px #ff0000;
    color: #fff6a9;
  text-align: center;
  animation: blink 6s infinite;
  -webkit-animation: blink 8s infinite;  
}
@-webkit-keyframes blink {
  20%,
  24%,
  55% {
    color: #111;
    text-shadow: none;
  }

  0%,
  19%,
  21%,
  23%,
  25%,
  54%,
  56%,
  100% {
/*     color: #fccaff;
    text-shadow: 0 0 5px #f562ff, 0 0 15px #f562ff, 0 0 25px #f562ff,
      0 0 20px #f562ff, 0 0 30px #890092, 0 0 80px #890092, 0 0 80px #890092; */
  text-shadow: 0 0 5px #ffa500, 0 0 15px #ffa500, 0 0 20px #ffa500, 0 0 40px #ffa500, 0 0 60px #ff0000, 0 0 10px #ff8d00, 0 0 98px #ff0000;
    color: #fff6a9;
  }
}

@keyframes blink {
  20%,
  24%,
  55% {
    color: #111;
    text-shadow: none;
  }

  0%,
  19%,
  21%,
  23%,
  25%,
  54%,
  56%,
  100% {
/*     color: #fccaff;
    text-shadow: 0 0 5px #f562ff, 0 0 15px #f562ff, 0 0 25px #f562ff,
      0 0 20px #f562ff, 0 0 30px #890092, 0 0 80px #890092, 0 0 80px #890092; */
  text-shadow: 0 0 5px #ffa500, 0 0 15px #ffa500, 0 0 20px #ffa500, 0 0 40px #ffa500, 0 0 60px #ff0000, 0 0 10px #ff8d00, 0 0 98px #ff0000;
    color: #fff6a9;
  }
}
.card .btn{
    position: absolute;
    z-index: 30;
    left:calc(50% - 60px);
    bottom: -10px;
    width: 120px;
}
/*span {
  display: inline-block;
  background: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
  margin: 60px 0 0;
  padding: 8px 10px;
}*/
</style>

<div class="box box-solid stacks_box" style="background-color:rgba(1,1,1,0);" stacknum='{{$stacks['id']}}'>

    <!-- /.box-header -->
    <div class="box-header stack_header">
      <h3 class="box-title">{{$stacks['title']}}</h3>
    </div>

    <div class="box-body" style='background-color:rgba(152,152,200,0)' >
        
        <div class="container{{$stacks['id']}} container" stacknum='{{$stacks['id']}}'>
         
          @foreach( $stacks['goods_data'] as $goodsk => $good)         
          <div data-card="4" class="card card{{$stacks['id']}}" stacknum='{{$stacks['id']}}'>
              <span class='imgspan'>
                <img src="https://***REMOVED***.com/***REMOVED***/{{$good['img']}}">
              </span>

              <a href="{{url('/show_goods/'.$good['goods_id'])}}" title="查看商品詳細內容" target='_blank'>
                  <span class='btn colorbtn add_to_cart'>查看商品</span>
              </a>
          </div>


          @endforeach
        </div>

    </div>
    <!-- /.box-body -->

</div> 

<script>
document.addEventListener("DOMContentLoaded", function(event) { 

  var rotate{{$stacks['id']}}, timeline{{$stacks['id']}};

  rotate{{$stacks['id']}} = function() {

      return $(".card{{$stacks['id']}}:first-child").appendTo(".container{{$stacks['id']}}");

  };
  

  
  // 首次啟動輪播
  timeline{{$stacks['id']}} = setInterval(rotate{{$stacks['id']}}, 1000);
/*
  $(".stacks_box").hover(function() {
      unrotate2
  });
*/
  stop_rotate{{$stacks['id']}} = function(){

      clearInterval( timeline{{$stacks['id']}} );
  }
  restar_rotate{{$stacks['id']}} = function(){

      timeline{{$stacks['id']}} = setInterval(rotate{{$stacks['id']}}, 1000);
  }  
  
  // 滑鼠進入時 停止播放
  $(".container{{$stacks['id']}}").mouseenter(function(){

      callstop = stop_rotate{{$stacks['id']}}();

      callstop;
  });

  // 滑鼠移出 繼續播放
  $(".container{{$stacks['id']}}").mouseleave(function(){

      callstop = restar_rotate{{$stacks['id']}}();

      callstop;
  });
  
  $(".card{{$stacks['id']}} .imgspan").click(function() {

      return rotate{{$stacks['id']}}();
  });


});


</script>
