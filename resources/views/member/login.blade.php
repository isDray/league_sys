@extends("web2")

@section('selfcss')
<style type="text/css">
#cus_g_btn > .abcRioButton{
    border-radius: 20px!important;
    border:1px solid #bebebe;
}
</style>
<link rel="stylesheet" href="{{url('/css/member_login.css')}}">

<script>
    
    function statusChangeCallback(response) 
    {   

        
        // 如果登入成功了就取回資料
        if( response.status == 'connected' )
        {   
            //console.log(response.authResponse['accessToken']);

            var _tk = response.authResponse['accessToken'];
            var request = $.ajax({
                
                url: "{{url('/fblogin')}}",
                method: "POST",
                data: 
                { 
                    "_token": "{{ csrf_token() }}",
                    _tk : _tk 
                },
                dataType: "json"
            });
 
            request.done(function( msg ) {

                if( msg === false ){
                    
                    toastr.warning("Facebook登入過程出錯,請稍後再嘗試");
                }
                else
                {
                    window.location.href = "{{url('/')}}"+msg;
                }

            });
 
            request.fail(function( jqXHR, textStatus ) {
               
            });            
        }
        else
        {
            // 登入失敗

        }
    }

    function checkLoginState() 
    {   
        FB.getLoginStatus(function(response) {

            statusChangeCallback(response);

        });
    }    

    window.fbAsyncInit = function() {

        FB.init({
            appId      : '***REMOVED***',
            cookie     : true,
            xfbml      : true,
            version    : 'v7.0'
        });
      
        FB.AppEvents.logPageView();   

    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // google 登入
    function gsign( googleUser ) {
        
        id_token = googleUser.getAuthResponse().id_token;

        if( id_token )
        {   

            var _tk = id_token;
            var request = $.ajax({
                
                url: "{{url('/googlelogin')}}",
                method: "POST",
                data: 
                { 
                    "_token": "{{ csrf_token() }}",
                    _tk : _tk 
                },
                dataType: "json"
            });
 
            request.done(function( msg ) {

                if( msg === false ){
                    
                    toastr.warning("Google登入過程出錯,請稍後再嘗試");
                }
                else
                {
                    window.location.href = "{{url('/')}}"+msg;
                }

            });
 
            request.fail(function( jqXHR, textStatus ) {
               
            }); 
        }
    }
    
    function onFailure(){}

    function onLoadCallback(){
        $('span[id^="not_signed_"]').html('使用 Google 登入');
        $('span[id^="connected"]').html('使用 Google 登入');

        gapi.load('auth2', function() {
            auth2 = gapi.auth2.init({
                client_id: '***REMOVED***',
            });
            element = document.getElementById('cus_g_btn');
            auth2.attachClickHandler(element, {}, gsign, onFailure);
        });              
    }

    
</script>

@endsection

@section('content_right')
{!!$Breadcrum!!}
@if( $errors->has('login') )
<div class="alert alert-danger alert-dismissible">
    
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    
    <h4><i class="icon fa fa-ban"></i>登入失敗</h4>

    {{$errors->first('login')}}
    
</div>
@endif
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v7.0&appId=***REMOVED***&autoLogAppEvents=1" nonce="o8BN8n8g"></script>

<div class="box box-solid">

<div class='col-md-7 col-sm-7 col-xs-12 _np'>
<div class="box box-default">
    
    <div class="box-header with-border">
        <h3 class="box-title">會員登入</h3>
    </div>

    
    <form class="form-horizontal" id="login_form" action="{{url('/member_login_act')}}" method="post" >
        
        {{ csrf_field() }}

        <div class="box-body">
            
            <div class="form-group">               
                <label for="account" class="col-sm-2 control-label">帳號</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="account" name="account" placeholder="" value="{{old('account')}}">
                    @if( $errors->has('account') )
                        <label id="account-error" class="form_invalid" for="account">
                            {{$errors->first('account')}}
                        </label>
                    @endif                    
                </div>

            </div>
            
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">密碼</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" name="password" placeholder="" value="{{ old('password') }}">
                    @if( $errors->has('password') )
                        <label id="password-error" class="form_invalid" for="password">
                            {{$errors->first('password')}}
                        </label>
                    @endif                   
                </div>            
            </div>

        </div>

        <div class="box-footer text-center">
          
            <button type="submit" class="btn colorbtn ">登入</button>
            

        </div>

    </form>

</div>
</div>



<div class='col-md-4 col-md-offset-1 col-sm-4 col-sm-offset-1 col-xs-12 _np'>
<div class="box box-default">
    
    <div class="box-header with-border">
        <h3 class="box-title">社群登入</h3>
    </div>

    <div class="box-body">

        <div class="fb-login-button" data-size="medium" data-button-type="login_with" data-layout="rounded" data-auto-logout-link="false" data-use-continue-as="true" data-width="" onlogin="checkLoginState();" style='width: 200px;'></div>
        <br><br>
        <div id='cus_g_btn' class="g-signin2"  data-height="28" data-width="200" data-longtitle="true"></div>

    </div>

</div>
</div>


</div>
@endsection


@section('selfjs')
<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>    

<script src="{{url('/validation/jquery.validate.min.js')}}"></script>

<script type="text/javascript">

$(function(){
    
    $("#login_form").validate({
        
        //debug: true,

        errorClass: "form_invalid",

        rules: {
            account: {
                required: true,
            },
            password:{
                required: true,
            },
           
        },
        messages: {
            
            account:{
                required:  "帳號為必填",
            },
            password:{
                required:  "密碼為必填",               
            },
            
        }        
    });
});
</script>
@endsection