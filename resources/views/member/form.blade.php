@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/league_member.css')}}">
@endsection

@section('content_right')
<div class="box box-solid">
<div class="box box-default">
    
    <div class="box-header with-border">
        <h3 class="box-title">加入會員</h3>
    </div>

    
    <form class="form-horizontal" id="member_form" action="{{url('/join_member_store')}}" method="post" >
        
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

            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">密碼確認</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="" value="{{ old('password_confirm') }}">
                    @if( $errors->has('password_confirm') )
                        <label id="password_confirm-error" class="form_invalid" for="password_confirm">
                            {{$errors->first('password_confirm')}}
                        </label>
                    @endif                    
                </div>            
            </div>

            <div class="form-group">               
                <label for="name" class="col-sm-2 control-label">姓名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ old('name') }}">
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
                    <input type="email" class="form-control" id="email" name="email" placeholder=""  value="{{ old('email') }}">
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
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="{{ old('phone') }}">
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
                    <input type="text" class="form-control" id="tel" name="tel" placeholder="" value="{{ old('tel') }}">
                    @if( $errors->has('tel') )
                        <label id="tel-error" class="form_invalid" for="tel">
                            {{$errors->first('tel')}}
                        </label>
                    @endif                    
                </div>
            </div>

        </div>

        <div class="box-footer text-center">
          
            <button type="submit" class="btn colorbtn ">註冊</button>

        </div>

    </form>

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

    $("#member_form_").validate({
        
        //debug: true,

        errorClass: "form_invalid",

        rules: {
            account: {
                required: true,
                minlength: 6,
                remote: {
                    url: "{{url('/member_account_exit')}}",
                    type: "post",
                    data: {
                        account: function() {
                            return $( "#account" ).val();
                        },
                        _token: "{{ csrf_token() }}",
                    }
                }
            },
            password:{
                required: true,
                minlength: 6                
            },
            password_confirm:{
                required: true,
                equalTo:"#password",
            },
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
            }/*,
            storename:{
                required: true,
                maxlength: 10, 
            },
            bank:{
                check_bank:true,
            },
            banksub:{
                required: true,
            },
            bankaccount:{
                required: true,
            },
            captcha:{
                required: true,
            }*/
        },
        messages: {
            
            account:{
                required:  "帳號為必填",
                minlength: "帳號至少需要六個字元",
                remote: "帳號已經存在 , 請選用其他帳號",
            },
            password:{
                required:  "密碼為必填",
                minlength: "密碼至少需要六個字元",                
            },
            password_confirm:{
                required: "密碼確認為必填",
                equalTo:  "密碼確認不相符",                
            },      
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
            },                  
            storename:{
                required: "加盟商店名稱為必填",
                maxlength: "加盟商店名稱最多為十個字元",  
            },
            bank:{
                check_bank:"請由選單中選取銀行",
            },
            banksub:{
                required: "分行名稱為必填",
            },
            bankaccount:{
                required: "匯款帳號為必填",
            },
            captcha:{
                required: "驗證碼為必填",
            }
        }        
    });
});
</script>
@endsection