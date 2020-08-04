@extends("web1")

@section('selfcss')

<link rel="stylesheet" href="{{url('/css/member_default.css')}}">

@endsection


@section('content_left')
    
    @includeIf( 'block_member_menu' , ['now_function'=>$now_function] )

@endsection

@section('content_right')

<div class='row custom_row'>

<div class='member_menu box box-solid'>
@includeIf( 'block_member_menu' , ['now_function'=>$now_function] )
</div>

<div class="box box-solid">

<div class="box box-default  member_default">
    
    <div class="box-header with-border">
        <h3 class="box-title member_default_title">
        {{ $page_title }}
        </h3>   
    </div>
   
    <div class="box-body">

        <div class="form-group">               
            
            <label for="name" class="col-sm-2 control-label label label-default">基本資料</label>

        </div>

    </div>

    <form class="form-horizontal" id="member_form_detail" action="{{url('/member_edit_detail_act')}}" method="post" >
        
    {{ csrf_field() }}
    <div class="box-body">

            <div class="form-group">               
                <label for="name" class="col-sm-2 control-label">姓名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ old( 'name' , $member['name'] ) }}">
                    @if( $errors->has('name') )
                        <label id="name-error" class="form_invalid" for="name">
                            {{$errors->first('name')}}
                        </label>
                    @endif                    
                </div>
            </div>

            <div class="form-group">               
                <label for="email" class="col-sm-2 control-label">信箱</label>

                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" placeholder=""  value="{{ old( 'email' , $member['email'] ) }}">
                    @if( $errors->has('email') )
                        <label id="email-error" class="form_invalid" for="email">
                            {{$errors->first('email')}}
                        </label>
                    @endif                      
                </div>
            </div>

            <div class="form-group">               
                <label for="phone" class="col-sm-2 control-label">手機</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="{{ old( 'phone' , $member['phone'] ) }}">
                    @if( $errors->has('phone') )
                        <label id="phone-error" class="form_invalid" for="phone">
                            {{$errors->first('phone')}}
                        </label>
                    @endif                       
                </div>
               
            </div>

            <div class="form-group">               
                <label for="tel" class="col-sm-2 control-label">電話</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="tel" name="tel" placeholder="" value="{{ old( 'tel' ,  $member['tel'] ) }}">
                    @if( $errors->has('tel') )
                        <label id="tel-error" class="form_invalid" for="tel">
                            {{$errors->first('tel')}}
                        </label>
                    @endif                    
                </div>
            </div>        
    </div>
    
    <div class="box-footer">

        <button type="submit" class="btn colorbtn ">修改</button>

    </div>
    
    </form>

    <div class="box-body">

        <div class="form-group">               
            
            <label for="name" class="col-sm-2 control-label label label-default">修改密碼</label>

        </div>

    </div>

    <form class="form-horizontal" id="member_form_password" action="{{url('/member_edit_password_act')}}" method="post" >
        
    {{ csrf_field() }}
    <div class="box-body">

            <div class="form-group">
                <label for="passwordo" class="col-sm-2 control-label">原密碼</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="passwordo" name="passwordo" placeholder="" value="{{ old('passwordo') }}">
                    @if( $errors->has('passwordo') )
                        <label id="passwordo-error" class="form_invalid" for="passwordo">
                            {{$errors->first('passwordo')}}
                        </label>
                    @endif                   
                </div>            
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">新密碼</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" name="password" placeholder="" value="{{ old('password') }}">
                    @if( $errors->has('password') )
                        <label id="password-error" class="form_invalid" for="password">
                            {{$errors->first('password')}}
                        </label>
                    @endif                   
                </div>            
            </div>            

            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">新密碼確認</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="" value="{{ old('password_confirm') }}">
                    @if( $errors->has('password_confirm') )
                        <label id="password_confirm-error" class="form_invalid" for="password_confirm">
                            {{$errors->first('password_confirm')}}
                        </label>
                    @endif                    
                </div>            
            </div>        
    </div>
    
    <div class="box-footer">

        <button type="submit" class="btn colorbtn ">修改</button>

    </div>
    
    </form>    
</div>

</div>    
</div>

@endsection

@section('selfjs')
<script src="{{url('/validation/jquery.validate.min.js')}}"></script>

<script type="text/javascript">

$(function(){

    // 手機格式驗證
    jQuery.validator.addMethod("check_phone", function(value, element) {
        
        var strPhone = /^09[0-9]{8}$/;

        if( value.match( strPhone ) ){
            
            return true;

        }else{
        
            return false;

        }
    }); 
    
    // 電話驗證
    jQuery.validator.addMethod("check_tel", function(value, element) {
        
        var strTel = /^0\d{1,3}\d{7,8}$/;

        if( value.match( strTel ) ){
            
            return true;

        }else{
        
            return false;

        }
    });    

    $("#member_form_detail").validate({
        
        errorClass: "form_invalid",

        rules: {

            name:{
                required: true,
            },    
            phone:{
                required: true,
                check_phone:true,
            },
            tel:{
                required: true,
                check_tel:true,                
            },
            email:{
                required: true,
                email:true,
            }
        },
        messages: {
                  
            name:{
                required: "姓名為必填",
            },
            phone:{
                required: "手機為必填",
                check_phone: "手機格式錯誤",
            },
            tel:{
                required:"電話為必填",
                check_tel:"電話格式錯誤",                
            },      
            email:{
                required: "信箱為必填",
                email:"信箱格式錯誤",
            }                 
        }        
    });



    $("#member_form_password_").validate({
        
        //debug: true,

        errorClass: "form_invalid",

        rules: {

            passwordo:{
                required: true,              
            },
            password:{
                required: true,
                minlength: 6                
            },
            password_confirm:{
                required: true,
                equalTo:"#password",
            }

        },
        messages: {

            passwordo:{
                required: "原密碼為必填",              
            },            
            password:{
                required:  "新密碼為必填",
                minlength: "密碼至少需要六個字元",                
            },
            password_confirm:{
                required: "新密碼確認為必填",
                equalTo:  "新密碼確認不相符",                
            },      

        }        
    });    
});
</script>
@endsection