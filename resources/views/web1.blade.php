<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@if( !empty($title) ){{$title}} - @endif{{ config('app.name') }}</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{url('/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{url('/AdminLTE/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('/AdminLTE/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{url('/AdminLTE/dist/css/skins/_all-skins.min.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{url('/css/weball1.css')}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="" style="background-color:{{$LeagueData['back_color']}};">


<div class='container-fluid'>

<div class="cart_list">
    <div class="cart_goods">
        <table width='100%' >
            <tr>
                <td colspan=4 >66666666</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
  
        </table>
    </div>
    <div class="cart_operate">
        <a  class="btn btn-block btn-primary" href="">去結帳</a>
    </div>
</div>

<div class='row'>
    <div class='col-md-12 col-sm-12 col-xs-12' id='menu_box'>

        <div class="col rwd_menu">
            <div class="hamburger" id="hamburger-1">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>

        <nav class='web_nav1'>
            <ul id='nav_main_ul'>
                <li class='menu_root'><span class='web_nav_tree_name root_tree'>商品分類</span>
                    <ul>
                        @foreach( $categorys as $categoryk => $category)
                        <li class='menu_li'><span class='web_nav_tree_name child_tree'>{{ $category['rcat_name'] }}</span>
                            <ul class="menu_ul">
                                @foreach( $category['child'] as $childk => $childv )
                                <li>{{ $childv['ccat_name'] }}</li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
<!--                         <li class='menu_li'><span class='web_nav_tree_name child_tree'>子分類</span>
                            <ul class="menu_ul">
                                <li>99999</li>
                                <li>88888</li>
                                <li>77777</li>
                            </ul>
                        </li>
                        <li class='menu_li'><span class='web_nav_tree_name child_tree'>子分類2</span>
                            <ul class="menu_ul">
                                <li>99999</li>
                                <li>88888</li>
                                <li>77777</li>
                            </ul>
                        </li> -->                        
                    </ul>
                </li>

            </ul>

            <div id="cart_btn" class='btn bg-maroon '>
                <i class='fa fa-fw fa-shopping-cart'></i>
            </div>            

        </nav>  


    </div>
    <div class='col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2 col-xs-12' id='content_left'>
                       
        @yield('content_left')
    </div>
    <div class='col-md-6 col-sm-6 col-xs-12' id="content_right">
        @yield('content_right')
    </div>   
</div>

<div class='row' id='footer'>
        
        <div class='col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-2 col-xs-12' id='footer_left'>
            
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_left_center">

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>如何購買</h4>
                        <li>購買流程</li>
                        <li>配送說明</li>
                        <li>支付方式說明</li>
                        <li>退換貨原則</li>
                    </ul>
                </div>

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>常見問題</h4>
                        <li>發票說明</li>
                        <li>情趣用品清洗及收納注意事項</li>
                        <li>常見Q&A</li>
                        <li>產品保固說明</li>
                    </ul>
                </div>                

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>會員中心</h4>
                        <li>批發合作說明</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class='col-md-4 col-sm-4 col-xs-12' id='footer_right'>
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_right_center">
                <div class="col-md-12 col-sm-12 col-xs-12"> 
                    <img src="https://***REMOVED***.com/***REMOVED***/ecs_static/img/18.png" style='float:right'>                    
                    <p id='footer_des'>
                        享愛網採全站情趣用品購物滿千免運，「包裝隱密」保護您的購物隱私，本購物網站支援－貨到付款－超商取貨－宅配到府，讓您購物輕鬆無負擔！本站提供數千種情趣用品批發、零售， 並有滿額贈品選擇，歡迎參觀選購
                    </p>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" id='contact_box'>
                    <p><i class="fa fa-fw fa-headphones"></i>客服專線：(04)874-0413</p>
                    <p><i class="fa fa-fw fa-mobile-phone"></i>客服手機：0915-588-683</p>
                    <p><i class="fa fa-fw fa-group"></i>客服Line ID： @enjoy-love</p>
                    <p><i class="fa fa-fw fa-envelope-o"></i>聯絡信箱：mykk97956@yahoo.com.tw</p>
                </div>

            </div>
        </div>

        <div class='col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 text-center' id='footer_bottom' >
            <img src="https://***REMOVED***.com/***REMOVED***/ecs_static/img/165_1160.png" style="max-width:100%;">
        </div>

</div>

</div>




<!-- jQuery 3 -->
<script src="{{url('/AdminLTE/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{url('/AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('/AdminLTE/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('/AdminLTE/dist/js/adminlte.min.js')}}"></script>

@yield('selfcss')

@yield('selfjs')

<script type="text/javascript">

$(document).ready(function(){
    $(".hamburger").click(function(){
        $(this).toggleClass("is-active");
        
        $(".web_nav1").toggle("slide");

    });
});


$(document).ready(function(){

    $("#cart_btn").click(function(){
        //$(".cart_list").slideToggle();
        $(".cart_list").animate({width:'toggle'},350);


    });
});

/*
|--------------------------------------------------------------------------
| 加入購物車
|--------------------------------------------------------------------------
|
*/
$(function(){

    $(".add_to_cart").click(function(){

        var goods_id = $(this).attr('goods_id');

        var request = $.ajax({
            url: "{{url('/add_to_cart')}}",
            method: "POST",
            data: { goods_id : goods_id ,
                    _token: "{{ csrf_token() }}",
                    number: 1,
                  },
            dataType: "json"
        });
 
        request.done(function( res ) {
            
            console.log(res);

            if( res['res'] == false ){

                var alert_text = '';

                $.each(res['data'] , function( errork, errorv){

                    $.each(errorv , function( errork2, errorv2){
                        alert_text += errorv2;
                    });

                });
                
                alert( alert_text );
            
            }else{

                // 如果順利加入購物車 , 就重整購物車內容
                $(".cart_goods").empty();

                $.each( res['data'] , function( listk , listv ){
                    
                    var tmp_goods = "<table width='100%'>";
                    tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";
                    tmp_goods += "<tr><td colspan='2' class='tableimg'><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'></td><td><span class='btn'>移除</span></td></tr>";
                    tmp_goods += "</table>";
                    
                    $(".cart_goods").append( tmp_goods );
                });
            }

        });
 
        request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    })
    
})

</script>
</body>
</html>
