@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/member_login.css')}}">
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

<div class="box box-solid">

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
@endsection


@section('selfjs')
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