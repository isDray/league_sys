<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@if( !empty($title) ){{$title}}-@endif{{$LeagueData['store_name']}}</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @if( isset($description) )
    <meta name="description" content="{{$description}}">
    @endif

    @if( isset($keywords) )
    <meta name="keywords" content="{{$keywords}}">
    @endif    

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

    <link href="{{url('/toastr-master/build/toastr.min.css')}}" rel="stylesheet"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{url('/css/weball1.css')}}">
    <link rel="stylesheet" href="{{url('/css/colorset'.$LeagueData['colorset'].'.css')}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="" style="background-color:{{$LeagueData['back_color']}};">


<div class='container-fluid'>

<h1 class='page_more_desc'>@yield('page_header','')</h1>

<div class='row'>

    <div class='col-md-12 col-sm-12 col-xs-12' id='menu_box' webname="SEXY-TOY STORE">

        <div class="col rwd_menu">
            <div class="hamburger" id="hamburger-1">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>

        @if( !empty($LeagueData['logo']) )
        <a href="{{url('/')}}">
        <img src="{{url('/league_logo/'.$LeagueData['logo'])}}" class='menulogo only_m' >
        </a>
        @endif


        <nav class='web_nav1'>
            @if( !empty($LeagueData['logo']) )
            <a href="{{url('/')}}">
            <img src="{{url('/league_logo/'.$LeagueData['logo'])}}" class='menulogo over_m' >
            </a>
            @endif
            <!-- 電腦版用 -->
            <ul id='nav_main_ul' class='over_m'>
                <li class='menu_root over_m'><span class='web_nav_tree_name root_tree'>商品分類<i class='fa fa-fw fa-sort-down'></i></span>
                    <ul>
                        @foreach( $categorys as $categoryk => $category)
                        <li class='menu_li'><a class='web_nav_tree_name child_tree menua' href="{{url('/category/'.$category['rcat'])}}">{{ $category['rcat_name'] }}</a>
                            <ul class="menu_ul">
                                @foreach( $category['child'] as $childk => $childv )
                                <a href="{{url('/category/'.$childv['ccat'])}}"><li>{{ $childv['ccat_name'] }}</li></a>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach                       
                    </ul>
                </li>
            </ul>
            <!-- 電腦版用 -->
            
            @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
            <div id='login_member_box' class='over_m'>
                <a href="{{url('/member_logout_act')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-sign-out'></i>會員登出【{{session('member_name')}}】
                </a>
            </div>   
            <div id='add_member_box' class='over_m'>
                <a href="{{url('/member_index')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-dashboard'></i>會員中心
                </a>
            </div>               
            @else
            <div id='login_member_box' class='over_m'>
                <a href="{{url('/member_login')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-sign-in'></i>會員登入
                </a>
            </div>            
            @endif            

            <div id='add_member_box' class='over_m'>
                <a href="{{url('/join_member')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-user-plus'></i>加入會員
                </a>
            </div>

            <div id='search_form' class='over_m'>
                <form action="{{url('/search')}}" method="POST">
                    {{ csrf_field() }}
                    <input type='text' class='form-control' name='keyword'> 
                    <button class='btn colorbtn form-control'>查詢</button>
                </form>
            </div>

            <!-- 手機板 -->
            <div class="only_m rwd_menu_box">
                
                <div class="rwd_root_menu">
                    <a href="{{url('/')}}">
                    <h4 class="box-title">
                        首頁
                    </h4>
                    </a>                          
                <div>

                <div class="rwd_root_menu">

                    <a data-toggle="collapse" data-parent="#accordion" href="#root_accordion_cat" aria-expanded="true" class="collapsed rwd_collapsed_icon">
                    <h4 class="box-title">
                        商品分類
                        <i class='fa fa-fw fa-angle-down'></i>
                    </h4>   
                    </a>

                    <!-- 選單開合改為永久開啟 , 所以不需要開關
                    <a data-toggle="collapse" data-parent="#accordion" href="#root_accordion_cat_bk" aria-expanded="false" class="collapsed rwd_collapsed_icon">
                        
                    </a>            
                    -->
                <div>

                <div class="panel-collapse collapse in" aria-expanded="false" id="root_accordion_cat">
                    @foreach($categorys as $categoryk => $category)
                    <div class="panel rwd_panel">
            
                        <div class="box-header">

                            @if( count($category['child'] ) > 0)
                            <a data-toggle="collapse" data-parent="#accordion" href="#rwd_accordion_{{$category['rcat']}}" aria-expanded="false" class="collapsed">
                            <h4 class="box-title">
                                
                                {{ $category['rcat_name'] }}
                                <i class='fa fa-fw fa-angle-down' ></i>
                                                                
                            </h4>
                            </a>
                            @else
                            <a href="{{url('/category/'.$category['rcat'])}}">
                            <h4 class="box-title">
                                {{ $category['rcat_name'] }}                             
                            </h4>
                            </a>                            
                            @endif


                        </div>
                
                        @if( count($category['child'] ) > 0)
                        <div id="rwd_accordion_{{$category['rcat']}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                            @foreach( $category['child'] as $leftchildk => $leftchild )
                                <a href="{{url('/category/'.$leftchild['ccat'])}}">
                                    <li class=''>
                                        <i class="fa fa-fw fa-angle-right"></i> 
                                        {{$leftchild['ccat_name']}}
                                    </li>
                                </a>
                            @endforeach
                            </div>
                        </div>
                        @endif
                    </div>            
                    @endforeach 
                </div>
              
            </div>           
            <!-- /手機板 -->

        </nav>  
        
        <!-- 購物車 -->
        <div class="dropdown cart_btn">
                
            <a id="dLabel" data-target="#" href="http://example.com/" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <li class='fa fa-fw fa-shopping-cart'></li>
            </a>

            <ul id='cart_list' class="dropdown-menu" aria-labelledby="dLabel">                    
                <li class="cart_list_area">
                    @if( isset( $Carts) )

                        @foreach( $Carts as $Cartk => $Cart)
                        <table class='cart_table' width='100%'>
                            <tr><td class='tableimg'><img src="https://***REMOVED***.com/***REMOVED***/{{$Cart['thumbnail']}}"></td>
                                <td width='30%'>×{{$Cart['num']}}={{$Cart['subTotal']}}</td>
                                <td width='30%'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id="{{$Cart['id']}}"><i class='fa fa-fw fa-remove'></i></span></td></tr>
                        </table>
                        @endforeach

                    @endif
                </li>
                <li class="cart_btn_area">
                    <a href="{{url('/cart')}}" class='btn colorbtn btn-flat margin'>去結帳</a>
                </li>
            </ul>

            <div class='num_in_cart'>{{$num_in_cart}}</div>            
        </div>
        <!-- /購物車 -->
    </div>
    <div class='col-md-2 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-12 over_m' id='content_left'>
        @yield('content_left')
    </div>
    
    <div class='col-md-8 col-sm-12 col-xs-12' id="content_right">
        @yield('content_right')
    </div>   
</div>

<div class='row' id='footer'>
        
        <div class='col-md-5 col-md-offset-1 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_left'>
            
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_left_center">

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>如何購買</h4>
                        <li> <a href="{{url('/article/10')}}">購買流程</a> </li>
                        <li> <a href="{{url('/article/15')}}">配送說明</a></li>
                        <li> <a href="{{url('/article/17')}}">支付方式說明</a></li>
                        <li> <a href="{{url('/article/21')}}">退換貨原則</a></li>
                    </ul>
                </div>

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>常見問題</h4>
                        <li> <a href="{{url('/article/9')}}">發票說明</a></li>
                        <li> <a href="{{url('/article/33')}}">情趣用品清洗及收納注意事項</a></li>
                        <li> <a href="{{url('/article/48')}}">常見Q&A</a></li>
                        <li> <a href="{{url('/article/51')}}">產品保固說明</a></li>
                    </ul>
                </div>                

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>會員中心</h4>
                        <li> <a href="{{url('/article/47')}}">批發合作說明</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class='col-md-5 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_right'>
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

<!-- 手機用 bottom tool -->
<div id='rwd_tool_bar' class='only_m'>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        <a href="{{url('/')}}">
        <i class="fa fa-fw fa-home"></i>
        <br>
        首頁
        </a>
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3 rwd_search_btn'>
        <a>
        <i class="fa fa-fw fa-search"></i>
        <br>
        搜尋
        </a>        
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        <a href="{{url('/join_member')}}">
        <i class="fa fa-fw fa-user-plus"></i>
        <br>
        加入會員
        </a>          
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
        <a href="{{url('/member_index')}}">
        <i class="fa fa-fw fa-dashboard"></i>
        <br>
        會員中心
        </a>        
        @else
        <a href="{{url('/member_login')}}">
        <i class="fa fa-fw fa-sign-in"></i>
        <br>
        登入
        </a>
        @endif    
    </div>            
</div>

<div class="rwd_search_box">
    <form action="{{url('/search')}}" method="POST">
        {{ csrf_field() }}
        <input type='text' class='form-control' name='keyword'> 
        <button class='btn colorbtn form-control'>查詢</button>
    </form>    
</div>
<!-- /手機用 bottom tool -->

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
<script src="{{url('/toastr-master/build/toastr.min.js')}}"></script>


@yield('selfcss')

@yield('selfjs')

<script type="text/javascript">

$(function(){
    $(".rwd_search_btn").click(function(){
        $(".rwd_search_box").toggleClass( "active" );
    })
})

/*
|--------------------------------------------------------------------------
| 手機板menu按鍵
|--------------------------------------------------------------------------
|
*/
$(document).ready(function(){

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "200",
      "hideDuration": "200",
      "timeOut": "1000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }  

    $(".hamburger").click(function(){
        $(this).toggleClass("is-active");
        
        $(".web_nav1").toggle("slide");

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

                $.each( res['data'] , function( listk , listv ){
                    
                    var tmp_goods = "<table class='cart_table' width='100%'>";
                    /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
                    num_in_cart += parseInt(listv['num']);

                    tmp_goods += "<tr><td class='tableimg'><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'></td>"+
                                      "<td width='30%'>×"+listv['num']+"="+listv['subTotal']+"</td>"+
                                      "<td width='30%'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'><i class='fa fa-fw fa-remove'></i></span></td></tr>";
                    tmp_goods += "</table>";
                    
                    $(".cart_list_area").append( tmp_goods );
                });

                $(".num_in_cart").empty();
                $(".num_in_cart").append(num_in_cart);
            }

        });
 
        request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    })
    
});




/*
|--------------------------------------------------------------------------
| 自購物車移除
|--------------------------------------------------------------------------
|
*/
$('body').on('click', '.rmbtn', function() {
    
    var rm_id = $(this).attr('goods_id');
    
    var rmrequest = $.ajax({
        url: "{{url('/rm_from_cart')}}",
        method: "POST",
        data: { goods_id : rm_id ,
                _token: "{{ csrf_token() }}",
        },
        dataType: "json"
    });
 
    rmrequest.done(function( res ) {
        
        toastr.success('成功移除商品');

        // 如果順利加入購物車 , 就重整購物車內容
        $(".cart_list_area").empty();
        
        var num_in_cart = 0;

        $.each( res['data'] , function( listk , listv ){
                    
            var tmp_goods = "<table class='cart_table' width='100%'>";
            /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
            
            num_in_cart += parseInt(listv['num']);

            tmp_goods += "<tr><td class='tableimg'><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'></td>"+
                             "<td width='30%'>×"+listv['num']+"="+listv['subTotal']+"</td>"+
                             "<td width='30%'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'><i class='fa fa-fw fa-remove'></i></span></td></tr>";
            tmp_goods += "</table>";
                    
            $(".cart_list_area").append( tmp_goods );
        });

        $(".num_in_cart").empty();
        $(".num_in_cart").append(num_in_cart);        
    });
 
    rmrequest.fail(function( jqXHR, textStatus ) {
        //alert( "Request failed: " + textStatus );
    });

});




/*
|--------------------------------------------------------------------------
| 避免移除商品時dropdown消失
|--------------------------------------------------------------------------
|
*/
$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
});
</script>
</body>
</html>
