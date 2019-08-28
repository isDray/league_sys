@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@section('content')
<div class='col-md-4 col-md-offset-4 col-sm-12 col-xs-12' id='login_box'>

@if($errors->count())
<div class="alert callout callout-danger">

    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    
    <h4>登入失敗</h4>
    
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
    
</div>    
@endif

<div class="login-box">
    
    <div class="login-logo">
        <a href="../../index2.html"><b>加盟會員</b>登入</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <!-- <p class="login-box-msg">Sign in to start your session</p> -->

        <form action="{{url('/login_act')}}" method="post">
            
            {{ csrf_field() }}

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="帳號" name='account' >
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('account'))
                <label id="account-error" class="form_invalid" for="account">{{ $errors->first('account') }}</label>
                @endif
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="密碼" name='password' >
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                <label id="password-error" class="form_invalid" for="password">{{ $errors->first('password') }}</label>
                @endif                
            </div>
            
            <div class="row">
<!--                 <div class="col-xs-8">
                    <div class="checkbox icheck">
                    
                    <label>
                        <input type="checkbox"> 記住帳號
                    </label>
                    
                    </div>
                </div> -->
                
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登入</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->

        <!-- <a href="#">I forgot my password</a><br> -->
        <br>
        <a href="register.html" class="text-center">註冊加盟會員</a>

  </div>
  <!-- /.login-box-body -->
</div>
</div>
@endsection

@section('selfjs')

@endsection