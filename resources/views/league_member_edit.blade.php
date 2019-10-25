@extends('league_admin')

@section('selfcss')
<style type="text/css">
.colorsetbox>div>input[name=colorset]{
    opacity: 0;
}
.colorsetbox>div>label{
    background-color: #eeeeee;
    display: block;
    height: 40px;
    text-align: center;
    border:2px solid #eeeeee;
    padding-left: 0px;
}
.colorsetbox>div> input[name=colorset]:checked + label{
    border:2px solid #3c8dbc;
}
.colorsetbox>div>label{
    line-height: 36px;  
}
</style>
@endsection

@section('content')
<div class='row custom_row'>
    
    @if( $errors->has('operation') )
    <div  class='col-md-12 col-sm-12 col-xs-12'>
        <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i>錯誤</h4>

        {{ $errors->first('operation') }}
        
    </div>
    </div>
    @endif

    @if (Session::has('success'))
    <div  class='col-md-12 col-sm-12 col-xs-12'>
        <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> 成功</h4>
        {{Session::get('success')}}
        </div>        
    </div>
    </div>    

    @endif


    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">會員資料</h3>
            </div>

    <form class="form-horizontal" id="member_form_detail" action="{{url('/league_member_update')}}" method="post" >
    
    <input type='hidden' name='member_id' value="{{ $member['id'] }}">

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

});
</script>
@endsection