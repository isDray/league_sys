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
    @if( $LeagueData['colorset'] == 1 || $LeagueData['colorset'] == 2)
    <link rel="stylesheet" href="{{url('/css/weball1.css')}}">
    @else
    <link rel="stylesheet" href="{{url('/css/weball'.$LeagueData['colorset'].'.css')}}">
    @endif

    <link rel="stylesheet" href="{{url('/css/colorset'.$LeagueData['colorset'].'.css')}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<body class="" style="background-color:{{$LeagueData['back_color']}};">

@if( $over18 == false )
<div id='over18'>
    <table id='table18'>
        <tr>
            <td align="right" id='over_no'><img src="{{url($over18_l)}}" alt="??????18???,????????????"></td>
            <td id='over_yes'><img src="{{url($over18_r)}}" alt="??????18???????????????????????????"></td>
        </tr>
    </table>
</div>
@endif

@if( $LeagueData['colorset'] == 3)
    
    @include( 'web_template_3' ,[ 'centent_type' => '1'])

@else

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
        <img src="{{url('/league_logo/'.$LeagueData['logo'])}}" class='menulogo only_m' alt="{{$LeagueData['store_name']}}logo">
        </a>
        @endif
        
        

        <nav class='web_nav1'>
            @if( !empty($LeagueData['logo']) )
            <a href="{{url('/')}}">
            <img src="{{url('/league_logo/'.$LeagueData['logo'])}}" class='menulogo over_m' alt="{{$LeagueData['store_name']}}logo">
            </a>
            @endif
            <!-- ???????????? -->
            <ul id='nav_main_ul' class='over_m'>
                <li class='menu_root over_m'><span class='web_nav_tree_name root_tree'>????????????<i class='fa fa-fw fa-sort-down'></i></span>
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

            <!-- ???????????? -->
            
            @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
            <div id='login_member_box' class='over_m'>
                <a href="{{url('/member_logout_act')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-sign-out'></i>???????????????{{session('member_name')}}???
                </a>
            </div>   
            <div id='add_member_box' class='over_m'>
                <a href="{{url('/member_index')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-dashboard'></i>????????????
                </a>
            </div>               
            @else
            <div id='login_member_box' class='over_m'>
                <a href="{{url('/member_login')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-sign-in'></i>????????????
                </a>
            </div>            
            @endif            

            <div id='add_member_box' class='over_m'>
                <a href="{{url('/join_member')}}" class="btn colorbtn">
                <i class='fa fa-fw fa-user-plus'></i>????????????
                </a>
            </div>

            <div id='search_form' class='over_m'>
                <form action="{{url('/search')}}" method="POST">
                    {{ csrf_field() }}
                    <input type='text' class='form-control' name='keyword'> 
                    <button class='btn colorbtn form-control'>??????</button>
                </form>
            </div>

            <!-- ????????? -->
            <div class="only_m rwd_menu_box">
                
                <div class="rwd_root_menu">
                    <a href="{{url('/')}}">
                    <h4 class="box-title">
                        ??????
                    </h4>
                    </a>                          
                <div>

                <div class="rwd_root_menu">

                    <a data-toggle="collapse" data-parent="#accordion" href="#root_accordion_cat" aria-expanded="true" class="collapsed rwd_collapsed_icon">
                    <h4 class="box-title">
                        ????????????
                        <i class='fa fa-fw fa-angle-down'></i>
                    </h4>   
                    </a>

                    <!-- ?????????????????????????????? , ?????????????????????
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
            <!-- /????????? -->

        </nav>  
        
        <!-- ????????? -->
        <div class="dropdown cart_btn">
                
            <a id="dLabel" data-target="#" href="http://example.com/" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <li class='fa fa-fw fa-shopping-cart'></li>
            </a>

            <ul id='cart_list' class="dropdown-menu" aria-labelledby="dLabel">                    
                <li class="cart_list_area">
                    @if( isset( $Carts) )
                        @php
                            $cartTotal = 0;
                        @endphp
                        @foreach( $Carts as $Cartk => $Cart)
                        @php
                            $cartTotal += $Cart['subTotal'];
                        @endphp
                        <table class='cart_table' width='100%'>
                            <tr>
                                <table>
                                    <tr >
                                        <td class='tableimg cart_img_box' colspan='2'>
                                            <img src="https://***REMOVED***.com/***REMOVED***/{{$Cart['thumbnail']}}">
                                            <span>{{$Cart['name']}}</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width='30%'>${{$Cart['goodsPrice']}}??{{$Cart['num']}}={{$Cart['subTotal']}}</td>
                                        <td width='30%' align='right'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id="{{$Cart['id']}}">??????</span></td>
                                    </tr>
                                </table>
                            </tr>
                        </table>
                        @endforeach
                        <p>??????:{{$cartTotal}}</p>
                    @endif
                </li>
                <li class="cart_btn_area">
                    <a href="{{url('/cart')}}" class='btn colorbtn btn-flat margin'>?????????</a>
                </li>
            </ul>

            <div class='num_in_cart'>{{$num_in_cart}}</div>            
        </div>
        <!-- /????????? -->
      

    </div>
    
    <div class='col-md-12 col-sm-12 col-xs-12 over_m' id="sub_menu">
        <div class='col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 over_m' id='sub_menu_main'>
            
            <a href="{{url('/new_arrival')}}">
                <span class="col-md-3 col-sm-3 col-xs-0 text-center sub_menu_item">????????????</span>
            </a>
            <a href="{{url('/register')}}">
                <span class="col-md-3 col-sm-3 col-xs-0 text-center sub_menu_item">????????????</span>
            </a>
            <a href="{{url('/check_order')}}">
                <span class="col-md-3 col-sm-3 col-xs-0 text-center sub_menu_item">????????????</span>
            </a>            
            <a href="{{url('/article/49')}}">
                <span class="col-md-3 col-sm-3 col-xs-0 text-center sub_menu_item">???????????????</span>
            </a>            
        </div>
    </div>

    <!-- ??????menu?????? -->
    <div id='fast_cat_box'>
        <div id='fast_cat_box_left'>
            @foreach( $categorys as $categoryk => $category)<input type='checkbox' class='fast_cat_root' id='fast_cat_root_{{$category["rcat"]}}'><label class='fast_cat_root_label' for='fast_cat_root_{{$category["rcat"]}}' refor='fast_cat_root_{{$category["rcat"]}}' >{{$category['rcat_name']}}</label>@endforeach
        </div>

        <div id='fast_cat_box_right'>
            <input type='checkbox' id='all_cat_root'><label id='all_cat_root_label' for='all_cat_root'></label>
        </div>

        <div id='fast_cat_box_show'>
            @foreach( $categorys as $categoryk => $category)
            <div class='fast_cat_box_child' id="fast_cat_root_{{$category["rcat"]}}_child_box">

            @if( count($category['child']) > 0 )
                @foreach( $category['child'] as $categorykc => $categoryc)<a href="{{url('/category')}}/{{$categoryc['ccat']}}"><div class='fast_cat_box_child_item'>{{$categoryc['ccat_name']}}</div></a>@endforeach
            @else

            @endif
            </div>
            @endforeach

            <!-- ?????????-->
            <div id='all_cat_box'>
            
                @foreach( $categorys as $categoryk => $category)
                <input type='checkbox' id='all_cat_{{$category["rcat"]}}' class='all_cat_check'><label class='all_cat_label' for='all_cat_{{$category["rcat"]}}' backfor='all_cat_{{$category["rcat"]}}'><span class='return_cat'></span>{{$category['rcat_name']}}</label>
                <div class='all_cat_child_box' id='all_cat_child_box_{{$category["rcat"]}}'>
                    @if( count($category['child'] > 0) )
                        
                        @foreach( $category['child'] as $categorykc => $categoryc)
                            <a href="{{url('/category')}}/{{$categoryc['ccat']}}">
                                <div>
                                    {{$categoryc['ccat_name']}}
                                </div>
                            </a>
                        @endforeach

                    @else

                    @endif
                </div>
                @endforeach

            </div>
                
            <!-- /?????????-->   

        </div>
    </div>
    <!-- /??????menu?????? -->

    <div class='col-md-2 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-12 over_m' id='content_left'>
        @yield('content_left')
    </div>
    
    <div class='col-md-8 col-sm-12 col-xs-12' id="content_right">
        @yield('content_right')

        @if( isset($viewed_goods) && count($viewed_goods) > 0)

            @include('viewed_goods')
    
        @endif
    </div>   
</div>


<div class='row' id='footer'>
        
        <div class='col-md-5 col-md-offset-1 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_left'>
            
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_left_center">

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>????????????</h4>
                        <li> <a href="{{url('/article/10')}}">????????????</a> </li>
                        <li> <a href="{{url('/article/15')}}">????????????</a></li>
                        <li> <a href="{{url('/article/17')}}">??????????????????</a></li>
                        <li> <a href="{{url('/article/21')}}">???????????????</a></li>
                    </ul>
                </div>

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>????????????</h4>
                        <li> <a href="{{url('/article/9')}}">????????????</a></li>
                        <li> <a href="{{url('/article/33')}}">???????????????????????????????????????</a></li>
                        <li> <a href="{{url('/article/48')}}">??????Q&A</a></li>
                        <li> <a href="{{url('/article/51')}}">??????????????????</a></li>
                    </ul>
                </div>                

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <!--<ul class='footer_list'><h4>????????????</h4>
                        <li> <a href="{{url('/article/47')}}">??????????????????</a></li>
                    </ul> -->
                </div>
            </div>

        </div>

        <div class='col-md-5 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_right'>
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_right_center">
                <div class="col-md-12 col-sm-12 col-xs-12"> 
                    <img lazysrc="https://***REMOVED***.com/***REMOVED***/ecs_static/img/18.png" style='float:right' class="lazyload" alt="????????????18???????????????">                    
                    <p id='footer_des'>
                        {{$LeagueData['store_name']}}????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? ?????????????????????????????????????????????
                    </p>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" id='contact_box'>
                    <p><i class="fa fa-fw fa-headphones"></i>???????????????(04)874-0413</p>
                    <p><i class="fa fa-fw fa-mobile-phone"></i>???????????????0915-588-683</p>
                    <p><i class="fa fa-fw fa-group"></i>??????Line ID??? @***REMOVED***</p>
                    <p><i class="fa fa-fw fa-envelope-o"></i>???????????????mykk97956@yahoo.com.tw</p>
                </div>

            </div>
        </div>

        <div class='col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 text-center' id='footer_bottom' >
            <img lazysrc="https://***REMOVED***.com/***REMOVED***/ecs_static/img/165_1160.png" style="max-width:100%;" class="lazyload" alt="???????????????,???????????????????????????????????????????????????????????? , ????????????(04)874-0413??????">
        </div>



</div>

<!-- ????????? bottom tool -->
<div id='rwd_tool_bar' class='only_m'>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        <a href="{{url('/')}}">
        <i class="fa fa-fw fa-home"></i>
        <br>
        ??????
        </a>
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3 rwd_search_btn'>
        <a>
        <i class="fa fa-fw fa-search"></i>
        <br>
        ??????
        </a>        
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        <a href="{{url('/join_member')}}">
        <i class="fa fa-fw fa-user-plus"></i>
        <br>
        ????????????
        </a>          
    </div>
    <div class='col-md-3 col-sm-3 col-xs-3'>
        @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
        <a href="{{url('/member_index')}}">
        <i class="fa fa-fw fa-dashboard"></i>
        <br>
        ????????????
        </a>        
        @else
        <a href="{{url('/member_login')}}">
        <i class="fa fa-fw fa-sign-in"></i>
        <br>
        ??????
        </a>
        @endif    
    </div>            
</div>

<div class="rwd_search_box">
    <form action="{{url('/search')}}" method="POST">
        {{ csrf_field() }}
        <input type='text' class='form-control' name='keyword'> 
        <button class='btn colorbtn form-control'>??????</button>
    </form>    
</div>
<!-- /????????? bottom tool -->

</div>
@endif



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

<script src="{{url('/js/lazyload.js')}}"></script>

<!-- ???????????? -->
@if( isset($owl) )
<script src="{{url('/js/owl.carousel.min.js')}}"></script>
<script type="text/javascript">
$( document ).ready(function() {
$('.owl-carousel').owlCarousel({
    loop:true,
    margin:0,
    nav:true,
    navElement:'span',
    navText: ["", ""],
    autoplay:true,
    navClass:['glyphicon glyphicon-chevron-left','glyphicon glyphicon-chevron-right'],
    responsive:{
        0:{
            items:2
        },
        768:{
            items:3
        },
        992:{
            items:4
        }
    }
})
});
</script>
@endif
<!-- /???????????? -->


@yield('selfcss')

@yield('selfjs')

<script type="text/javascript">

$(function(){
    $(".rwd_search_btn").click(function(){
        $(".rwd_search_box").toggleClass( "active" );
    })

    $("img.lazyload").lazyload();

})

/*
|--------------------------------------------------------------------------
| ?????????menu??????
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

        if ( $( ".web_nav1" ).length ) {
            
            $(".web_nav1").toggle("slide");
        }
        
        if ( $( ".rwd_menu_slide" ).length ) {
            
            $(".rwd_menu_slide").animate({width:'toggle'},350);

        }
        

    });

    // ?????????????????????
    currentWidth0 = $('.fastLi.active').outerWidth();
    var moveDistance = 0;
    $(".fast_cat_root_label").each(function(){

        if( $(this).hasClass('active') )
        {
            return false;
        }

        moveDistance += $(this).outerWidth();
    });

    $('#fast_cat_box_left').animate({scrollLeft:moveDistance}, 200);
});




/*
|--------------------------------------------------------------------------
| ???????????????
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

                toastr.success('?????????????????????');
                // ??????????????????????????? , ????????????????????????
                $(".cart_list_area").empty();
                
                var num_in_cart = 0;
                
                var cartTotal = 0;
                
                $.each( res['data'] , function( listk , listv ){
                    
                    cartTotal += listv['subTotal'];

                    var tmp_goods = "<table class='cart_table' width='100%'>";
                    /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
                    num_in_cart += parseInt(listv['num']);
                    tmp_goods += '<tr><td><table>';
                    tmp_goods += "<tr><td class='tableimg cart_img_box' colspan='2' ><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'><span>"+listv['name']+"</span></td></tr>"+
                                      "<tr><td>$"+listv['goodsPrice']+"??"+listv['num']+"="+listv['subTotal']+"</td>"+
                                      "<td align='right'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'>??????</span></td></tr>";

                    tmp_goods += '</table></td></tr>';
                    tmp_goods += "</table>";
                    
                    $(".cart_list_area").append( tmp_goods );
                });
                
                $(".cart_list_area").append( '<p>??????'+cartTotal+'</p>' );

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
| ??????????????????
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
        
        toastr.success('??????????????????');

        // ??????????????????????????? , ????????????????????????
        $(".cart_list_area").empty();
        
        var num_in_cart = 0;
        var cartTotal = 0;
        $.each( res['data'] , function( listk , listv ){
            cartTotal += listv['subTotal'];
            var tmp_goods = "<table class='cart_table' width='100%'>";
            /*tmp_goods += "<tr><td colspan='4' class='cart_item_title'>"+listv['name']+"</td></tr>";*/
            
            num_in_cart += parseInt(listv['num']);

            tmp_goods += '<tr><td><table>';
            tmp_goods += "<tr><td class='tableimg cart_img_box' colspan='2' ><img src='https://***REMOVED***.com/***REMOVED***/"+listv['thumbnail']+"'><span>"+listv['name']+"</span></td></tr>"+
                                      "<tr><td>$"+listv['goodsPrice']+"??"+listv['num']+"="+listv['subTotal']+"</td>"+
                                      "<td align='right' ><span class='btn bg-maroon btn-flat margin rmbtn' goods_id='"+listv['id']+"'>??????</span></td></tr>";

            tmp_goods += '</table></td></tr>';
            tmp_goods += "</table>";
                    
            $(".cart_list_area").append( tmp_goods );
        });

        $(".cart_list_area").append( '<p>??????'+cartTotal+'</p>' );

        $(".num_in_cart").empty();
        $(".num_in_cart").append(num_in_cart);        
    });
 
    rmrequest.fail(function( jqXHR, textStatus ) {
        //alert( "Request failed: " + textStatus );
    });

});




/*
|--------------------------------------------------------------------------
| ?????????????????????dropdown??????
|--------------------------------------------------------------------------
|
*/
$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
});

@if( $over18 == false )
$("#over_no").click(function(){

    window.location.href = "https://google.com";
});

$("#over_yes").click(function(){
    var ms = new Date().getTime() + 86400000;
    var exd = new Date(ms);
    document.cookie = "over18=true;domain={{$cookie_domain}}; path=/; expires="+ exd;
    $("#over18").hide();
});
@endif




/*
|--------------------------------------------------------------------------
| ????????????????????????
|--------------------------------------------------------------------------
|
*/
$(".fast_cat_root").click(function(){

    //$( "fast_cat_box_left" ).scrollLeft( 300 );

    //console.log( $(this).attr('id') );


    // ??????????????????id????????????    
    var selectId = $(this).attr('id');
    
    // ??????????????????checkbox?????????
    $(".fast_cat_root").prop('checked',false);

    $("#"+selectId).prop('checked',true);
    
    var selectDisplay = $("#"+selectId+"_child_box").css('display');
    
    /*// ?????????????????????
    $(".fast_cat_box_child").slideUp();
    */
    //console.log( $("label[for="+selectId+"]").outerWidth() );
    
    //var paretnsLeft = $("#fast_cat_box_left").offset();
    //var selfLeft    = $("label[for="+selectId+"]").offset();
    
    var moveDistance = 0;
    $(".fast_cat_root_label").each(function(){

        if( $(this).prop('for') == selectId )
        {
            return false;
        }

        moveDistance += $(this).outerWidth();
    });

    $('#fast_cat_box_left').animate({scrollLeft:moveDistance}, 200);
    //console.log( moveDistance );

    if( selectDisplay == 'none')
    {   
        
        $("#all_cat_box").hide();
        $("#all_cat_root").prop('checked',false);
        
        if( $(".fast_cat_box_child:visible").length > 0 )
        {    
            $(".fast_cat_box_child").hide();

            // ????????????????????????
            $("#"+selectId+"_child_box").fadeIn();
        }
        else
        {    
            $(".fast_cat_box_child").hide();

            // ????????????????????????
            $("#"+selectId+"_child_box").slideDown();
        }


    }
    else
    {   // ????????????????????????
        $("#"+selectId+"_child_box").slideUp(); 
    }

});




/*
|--------------------------------------------------------------------------
|
|--------------------------------------------------------------------------
|
*/
$("#all_cat_root").click(function(){
    
    if( $("#all_cat_root:checked").length > 0 )
    {   
        $(".fast_cat_box_child").hide();
        $("#all_cat_box").slideDown();
    }
    else{
        
        $("#all_cat_box").slideUp();
    }

})



/*
|--------------------------------------------------------------------------
| ??????????????????
|--------------------------------------------------------------------------
|
*/
$(".all_cat_check").click(function(){
    
    if( $(".all_cat_check:checked").length > 0)
    {
        var selectAllCat = $(this).attr('id');
         
        $(".all_cat_check").removeClass('checkroot');
        $(".all_cat_check").addClass('uncheckroot');
    
        $("#"+selectAllCat).addClass('checkroot');
        $("#"+selectAllCat).removeClass('uncheckroot');
        
        $(".all_cat_label").prop('for','');
    }

})



$(".return_cat").click(function(){

    $(".all_cat_check").removeClass('checkroot');
    $(".all_cat_check").removeClass('uncheckroot');

    $(".all_cat_label").each(function(k,i){
        
        tmpfor = i.getAttribute('backfor');

        $(this).prop('for',tmpfor);
    })
})



$("#viewed_switch").click(function(){
    
    
    // ??????????????????????????????
    if( $( "#viewed_switch p" ).hasClass( "fa-chevron-left" ) )
    {   
        //$("#viewed_goods_box").css("right","10px");

        $('#viewed_goods_box').animate({"margin-right": '+=110'});

        $( "#viewed_switch p").removeClass("fa-chevron-left");
        $( "#viewed_switch p").addClass( "fa-chevron-right" );

    }
    else
    {
        //$("#viewed_goods_box").css("right","-100px");

        $('#viewed_goods_box').animate({"margin-right": '-=110'});

        $( "#viewed_switch p").removeClass("fa-chevron-right");
        $( "#viewed_switch p").addClass( "fa-chevron-left" );        
    }


});
</script>
@if( $LeagueData['colorset'] == 3)
<script type="text/javascript">
$(function(){
    $(".rwd_menu_li_more").click(function(){
 
        tmp_expand = "rwd_menu_ul"+$(this).attr('toggole_num');
        
        if( $("."+tmp_expand).is(":visible") )
        {
            $(this).removeClass("fa-angle-right");
            $(this).addClass( "fa-angle-down" );              
        }
        else
        {
            $(this).removeClass("fa-angle-down");
            $(this).addClass( "fa-angle-right" );                      
        }

        $("."+tmp_expand).slideToggle('slow');
    });

    $(".cart_toggle").click(function(){
        if ( $( ".rwd_cart_slide" ).length ) {
            
            $(".rwd_cart_slide").animate({width:'toggle'},350);

        }
    });
});
</script>
@endif
</body>
</html>
