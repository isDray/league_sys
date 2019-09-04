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
<div class='row'>
    <div class='col-md-12 col-sm-12 col-xs-12' id='menu_box'>

        <div class=" col rwd_menu">
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
        </nav>  
    </div>
    <div class='col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2 col-xs-12' id='content_left'>
                       
        @yield('content_left')
    </div>
    <div class='col-md-6 col-sm-6 col-xs-12' id="content_right">
        @yield('content_right')
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
        
        /*if( $(".hamburger").hasClass('is-active') ){

        }*/
        $(".web_nav1").toggle("slide");

    });
});

</script>
</body>
</html>
