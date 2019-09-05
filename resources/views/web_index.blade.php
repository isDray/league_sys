@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@section('content')
<div class='col-md-4 col-md-offset-4 col-sm-12 col-xs-12' id='login_box'>

<div class="login-box">
    
    <div class="login-logo">
        <a href="../../index2.html"><b>加盟會員</b>登入</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <!-- <p class="login-box-msg">Sign in to start your session</p> -->



        <!-- /.social-auth-links -->

        <!-- <a href="#">I forgot my password</a><br> -->
        <br>
        <a href="register.html" class="text-center">註冊加盟會員</a>

  </div>
  <!-- /.login-box-body -->
</div>
</div>
@endsection

@section('content_right')
    
    @foreach( $CenterBlocks as $CenterBlockk => $CenterBlock)
        
        @includeIf('block_'. $CenterBlock)

    @endforeach

@endsection


@section('selfjs')

@endsection