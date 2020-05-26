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
  opacity: 0.7;
}

.card:nth-child(5) {
  z-index: 6;
  top: -60px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.6);
  transform: scale(0.6);
  opacity: 0.6;
}

.card:nth-child(6) {
  z-index: 5;
  top: -75px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.5);
  transform: scale(0.5);
  opacity: 0.5;
}

.card:nth-child(7) {
  z-index: 4;
  top: -90px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.4);
  transform: scale(0.4);
  opacity: 0.4;
}

.card:nth-child(8) {
  z-index: 3;
  top: -105px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.3);
  transform: scale(0.3);
  opacity: 0.3;
}

.card:nth-child(9) {
  z-index: 2;
  top: -120px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.2);
  transform: scale(0.2);
  opacity: 0.2;
}

.card:nth-child(10) {
  z-index: 1;
  top: -135px;
  -webkit-transform-origin: top;
  transform-origin: top;
  -webkit-transform: scale(0.1);
  transform: scale(0.1);
  opacity: 0.1;
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

/*span {
  display: inline-block;
  background: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
  margin: 60px 0 0;
  padding: 8px 10px;
}*/
</style>
<div class="box box-solid" style="background-color:rgba(1,1,1,0);">

    <!-- /.box-header -->
    <div class="box-body" style='background-color:rgba(152,152,200,0)' >
        
        <div class="container">         
          <div data-card="4" class="card"><span><img src="https://***REMOVED***.com/***REMOVED***/images/201204/goods_img/7464_P_1335134756696.jpg"></span></div>
          <div data-card="3" class="card"><span>Click Me3</span></div>
          <div data-card="2" class="card"><span>Click Me2</span></div>
          <div data-card="1" class="card"><span>Click Me1</span></div>
        </div>

    </div>
    <!-- /.box-body -->

</div> 

<script>
document.addEventListener("DOMContentLoaded", function(event) { 

  var rotate, timeline;

  rotate = function() {
    //return $('.card:first-child').fadeOut(400, 'swing', function() {
      return $('.card:first-child').appendTo('.container');
    //}).fadeIn(400, 'swing');
  };

  timeline = setInterval(rotate, 1000);

  $('body').hover(function() {
    return clearInterval(timeline);
  });

  $('.card').click(function() {

      return rotate();
  });


  $(".card`")

}).call(this);


</script>
