<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>享愛加盟會員管理後台</title>
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

    <link rel="stylesheet" href="{{url('/css/admin_all.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>後台</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>加盟會員</b>管理後台</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
<!--           <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li> -->
          <li>
            <a href="/logout_act" ><i class="fa fa-fw fa-sign-out"></i> 登出</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li><a href="{{url('/league_dashboard')}}"><i class="fa fa-fw fa-dashboard text-light-blue"></i> <span>管理後台首頁</span></a></li>

        <li class="header">報表</li>
        <li class="treeview @if( isset($tree) && $tree=='report' ) active @endif">
          <a href="{{url('/league_dashboard')}}">
            <i class="fa fa-fw fa-bar-chart text-light-blue"></i> <span>報表查詢</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu ">
            <li><a href="{{url('/league_report_order')}}"><i class="fa fa-fw fa-angle-right"></i>訂單報表</a></li>
            <li><a href="{{url('/league_report_commission')}}"><i class="fa fa-fw fa-angle-right"></i>獎金報表</a></li>
          </ul>
        </li>

        
        <li class="header">網站</li>
        <!-- 功能排序 -->
        <li class="treeview  @if( isset($tree) && $tree=='sort' ) active @endif" >
            
            <a href="#">
                <i class="fa fa-fw fa-sort text-light-blue"></i> <span>功能排序</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>                
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('/league_sort_center')}}"><i class="fa fa-fw fa-angle-right"></i>首頁區塊排序</a></li>
                <li><a href="{{url('/league_sort_left')}}"><i class="fa fa-fw fa-angle-right"></i>左側區塊排序</a></li>
                <li><a href="{{url('/league_sort_cart')}}"><i class="fa fa-fw fa-angle-right"></i>購物車區塊排序</a></li>
            </ul>        
            
        </li>
        <!--  /功能排序 -->
        
        <!-- 模組管理 -->
        <li class="treeview  @if( isset($tree) && $tree=='modul' ) active @endif" >
            <a href="#">
                <i class="fa fa-fw fa-cubes text-light-blue"></i> <span>功能管理</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>                
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('/league_module_banner')}}"><i class="fa fa-fw fa-angle-right"></i>banner功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_hot')}}"><i class="fa fa-fw fa-angle-right"></i>熱銷商品功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_recommend')}}"><i class="fa fa-fw fa-angle-right"></i>推薦商品功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_new')}}"><i class="fa fa-fw fa-angle-right"></i>新品上市功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_category_list')}}"><i class="fa fa-fw fa-angle-right"></i>類別商品功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_stack')}}"><i class="fa fa-fw fa-angle-right"></i>堆疊商品輪播功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_custom_ad')}}"><i class="fa fa-fw fa-angle-right"></i>推薦卡片功能管理</a></li>
                <li><a href="{{url('/league_module_recommend_shipping_free')}}"><i class="fa fa-fw fa-angle-right"></i>免運差額推薦管理</a></li>
            </ul>                
        </li>
        <!-- /模組管理 -->

        <li><a href="{{url('/league_webset')}}"><i class="fa fa-fw fa-globe text-light-blue"></i> <span>網站設定</span></a></li>
        
        <!-- 網站地圖 -->
        <!-- <li><a href="{{url('/league_webset')}}"><i class="fa fa-fw fa-globe text-light-blue"></i> <span>網站地圖下載</span></a></li> -->
        <!-- /網站地圖 -->

        <!-- 會員 -->
        <li class="header">會員管理</li>
        <li><a href="{{url('/league_member_list')}}"><i class="fa fa-fw fa-users text-light-blue"></i> <span>會員列表</span></a></li>
        <!-- /會員-->
        <li class="header">個人資料</li>
        <!-- <li><a href="{{url('/league_user')}}"><i class="fa fa-fw fa-user text-green"></i> <span>個人資料設定</span></a></li> -->
        <li class="treeview @if( isset($tree) && $tree=='info' ) active @endif" >
            <a href="#">
                <i class="fa fa-fw fa-user text-light-blue"></i> <span>帳號管理</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>                
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('/league_profile_basic/'.Session::get('user_id'))}}"><i class="fa fa-fw fa-angle-right"></i>基本資料設定</a></li>
                <li><a href="{{url('/league_profile_password/'.Session::get('user_id'))}}"><i class="fa fa-fw fa-angle-right"></i>密碼設定</a></li>
            </ul>                
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @if( isset( $PageTitle ) )

        {{$PageTitle}}

        @endif
        <!-- <small>it all starts here</small> -->
      </h1>
<!--       <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="container-fluid">
    @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <!-- <b>Version</b> 2.4.13 -->
    </div>
    <strong>享愛加盟系統</strong> 版權所有
  </footer>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
<!-- AdminLTE fmo purposes -->
<script src="{{url('/AdminLTE/dist/js/demo.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
@yield('selfcss')

@yield('selfjs')
</body>
</html>
