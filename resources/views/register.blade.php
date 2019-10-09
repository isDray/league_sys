@extends('league_front')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/register.css')}}">
@endsection

@section('content')
    <div class='col-md-4 col-md-offset-2 col-sm-12 col-xs-12' id='register_intro'>
        
        <div  class='label_box' label='加盟優勢' >
            <p>&nbsp; <span class="label pull-left bg-orange">免成本</span><span class="label pull-left text-black"> 免加盟金 ， 免壓年金 ， 免囤貨 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">高度自由</span><span class="label pull-left text-black"> 不論在家或者咖啡廳都能輕鬆完成 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">專業協助</span><span class="label pull-left text-black"> 訂單處理 、 商品包裝配送 、 售後服務退換貨 ，所有關於商品問題皆由享愛完成 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">貨源充足</span><span class="label pull-left text-black"> 享愛加盟平台定期依照銷售曲線預估貨量 , 確保您的訂單都能夠順利出貨 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">優渥獎金</span><span class="label pull-left text-black"> 提供高比例獎金 ， 銷售輛與獎金成正比 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">專屬品牌</span><span class="label pull-left text-black"> 網頁上的曝光讓客群更認識屬於您的專屬品牌 ， 讓品牌名聲與獎金形成良性循環</span></p>

        </div>

        <div  class='label_box' label='獎金計算' >
            
            <p>
               <span class="label pull-left bg-maroon">訂單的20%</span>
               
               <br>
               假設您有一筆1000元的訂單 , 則此筆訂單可以抽成200元 
            </p>
 
        </div>

        <div  class='label_box' label='簡單加盟程序' >
            
            <ul class="timeline">
                <!-- timeline time label -->
                                
                <!-- /.timeline-label -->

                <!-- timeline item -->
            <li>
              <i class="fa fa-fw fa-pencil bg-maroon"></i>

              <div class="timeline-item">

                <h3 class="timeline-header"><a href="#">申請</a> </h3>

                <div class="timeline-body">
                    於加盟會員表單中填寫您的個人資料 , 包含自訂帳號密碼 、姓名、電話 、收款銀行帳戶...等
                    完成後送出申請表單。
                </div>
              </div>
            </li>

            <li>
              <i class="fa fa-fw fa-thumbs-o-up bg-maroon"></i>

              <div class="timeline-item">

                <h3 class="timeline-header"><a href="#">審核</a></h3>

                <div class="timeline-body">
                    享愛加盟系統接收到您的申請資料後 , 開始進行審核動作 , 在審核完成之後由系統發送審核通知信
                    至您的信箱。
                </div>
              </div>
            </li> 

            <li>
              <i class="fa fa-fw fa-usd bg-maroon"></i>

              <div class="timeline-item">

                <h3 class="timeline-header"><a href="#">開始使用</a></h3>

                <div class="timeline-body">
                    完成審核之後 , 您將可以用您註冊的帳號密碼進入管理後台 , 管理網站樣式以及查看目前銷售成效。
                </div>
              </div>
            </li>                       

          </ul>
 
        </div>

        <div  class='label_box' label='網站管理' >
            
            <p>&nbsp; <span class="label pull-left bg-orange">報表</span><span class="label pull-left text-black"> 網站提供圖形化報表 ， 為您提供策略化經營的利器 。</span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">訂製</span><span class="label pull-left text-black"> 網站版型區塊可讓您自由決定位置 ， 讓您設計出獨一無二的網站 </span></p>
            <p>&nbsp; <span class="label pull-left bg-orange">擴充</span><span class="label pull-left text-black"> 網站版型及功能持續開發及維護 </span></p>

        </div>        


    </div>

    <div class='col-md-4 col-sm-12 col-xs-12' >
        <div class="box box-primary" id='register_form_box'>
            <div class="box-header with-border">
              <h3 class="box-title">加盟會員申請表單</h3>
            </div>
          
            
            <!-- 註冊表單 -->
            <form id="register_form" method="POST" action="{{url('register_act')}}"> 

                {{ csrf_field() }}

                <div class="box-body">      

                    <div class='form_subtitle'><span>設定帳號及密碼</span></div>    

                    <div class="form-group">
                        <label for="account">帳號:</label>
                        <input type="text" class="form-control" id="account" name="account" placeholder="請輸入帳號">
                        @if ($errors->has('account'))
                        <label id="account-error" class="form_invalid" for="account">{{ $errors->first('account') }}</label>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="password1">密碼</label>
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="請輸入密碼">
                        @if ($errors->has('password1'))
                        <label id="password1-error" class="form_invalid" for="password1">{{ $errors->first('password1') }}</label>
                        @endif                        
                    </div>
    
                    <div class="form-group">
                        <label for="password2">密碼確認</label>
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="請再次輸入密碼,確認密碼無誤">
                        @if ($errors->has('password2'))
                        <label id="password2-error" class="form_invalid" for="password2">{{ $errors->first('password2') }}</label>
                        @endif                          
                    </div>                
                    
                    <div class='form_subtitle'>加盟商基本資料</div>
    
                    <div class="form-group">
                        <label for="name">姓名:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名">
                        @if ($errors->has('name'))
                        <label id="name-error" class="form_invalid" for="name">{{ $errors->first('name') }}</label>
                        @endif                            
                    </div>

                    <div class="form-group">
                        <label for="phone">手機:</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="請輸入手機">
                        @if ($errors->has('phone'))
                        <label id="phone-error" class="form_invalid" for="phone">{{ $errors->first('phone') }}</label>
                        @endif                           
                    </div>     

                    <div class="form-group">
                        <label for="tel">電話:</label>
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="請輸入電話">
                        @if ($errors->has('tel'))
                        <label id="tel-error" class="form_invalid" for="tel">{{ $errors->first('tel') }}</label>
                        @endif                          
                    </div>  

                    <div class="form-group">
                        <label for="email">信箱:</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="請輸入信箱">
                        @if ($errors->has('email'))
                        <label id="email-error" class="form_invalid" for="email">{{ $errors->first('email') }}</label>
                        @endif                         
                    </div> 

                    <div class="form-group">
                        <label for="storename">加盟商店名稱:</label>
                        <input type="text" class="form-control" id="storename" name="storename" placeholder="請輸入商店名稱">
                        @if ($errors->has('storename'))
                        <label id="storename-error" class="form_invalid" for="storename">{{ $errors->first('storename') }}</label>
                        @endif                        
                    </div>  

                    <div class="form-group">
                        <label for="bank">獎金匯款銀行行號:</label>
                        <input type="text" class="form-control" id="bank" name="bank" placeholder="請輸入銀行行號">  
                        @if ($errors->has('bank'))
                        <label id="bank-error" class="form_invalid" for="bank">{{ $errors->first('bank') }}</label>
                        @endif 
                    </div>  
                    <div class="form-group">
                        <label for="bankaccount">分行名稱:</label>                    
                        <input type="text" class="form-control" id="banksub" name="banksub" placeholder="請輸入分行名稱" >
                        @if ($errors->has('banksub'))
                        <label id="banksub-error" class="form_invalid" for="banksub">{{ $errors->first('banksub') }}</label>
                        @endif                         
                     </div> 

                    <div class="form-group">
                        <label for="bankaccount">匯款帳號:</label>
                        <input type="text" class="form-control" id="bankaccount" name="bankaccount" placeholder="請輸入匯款帳號">
                        @if ($errors->has('bankaccount'))
                        <label id="bankaccount-error" class="form_invalid" for="bankaccount">{{ $errors->first('bankaccount') }}</label>
                        @endif                            
                    </div>  

                    <div class="form-group">
                        <label for="captcha">驗證碼:</label>
                        <input type="text" class="form-control" id="captcha" name="captcha" placeholder="請輸入下方驗證碼">
                        @if ($errors->has('captcha'))
                        <label id="captcha-error" class="form_invalid" for="captcha">{{ $errors->first('captcha') }}</label>
                        @endif                        
                    </div>  
                    <img src="{{Captcha::src()}}" id="captcha_img" data-refresh-config="default">

                    <button type="submit" class="btn btn-primary">確認送出</button>
                </div>        
            </form>
            <!-- 註冊表單結束 -->
            
    
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
    


    // 銀行驗證
    jQuery.validator.addMethod("check_bank", function(value, element) {
        
        var all_bank = [];

        $("#bank option").each(function( key ,  opt ){

            all_bank.push($("#bank option")[key].value);

        });

        if( value > 0 && $.inArray(value,all_bank ) ){
            return true;
        }else{
            return false;
        }
    });

    $("#register_form").validate({
        
        //debug: true,

        errorClass: "form_invalid",

        rules: {
            account: {
                required: true,
                minlength: 6,
                remote: {
                    url: "{{url('/league_account_exist')}}",
                    type: "post",
                    data: {
                        account: function() {
                            return $( "#account" ).val();
                        },
                        _token: "{{ csrf_token() }}",
                    }
                }
            },
            password1:{
                required: true,
                minlength: 6                
            },
            password2:{
                required: true,
                equalTo:"#password1",
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
            },
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
            }
        },
        messages: {
            
            account:{
                required:  "帳號為必填",
                minlength: "帳號至少需要六個字元",
                remote: "帳號已經存在 , 請選用其他帳號",
            },
            password1:{
                required:  "密碼為必填",
                minlength: "密碼至少需要六個字元",                
            },
            password2:{
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
    



    /*
    |----------------------------------------------------------------
    | 重新產生驗證碼
    |----------------------------------------------------------------
    | 為了避免驗證碼不清楚 , 如果點擊驗證碼後立即產生一組驗證碼
    |
    */
    $("#captcha_img").click(function(){

        var captcha = $(this);
        var config = captcha.data('refresh-config');


        $.ajax({
            method: 'GET',
            url: '/get_captcha/' + config,
        
        }).done(function (response) {
            
            captcha.prop('src', response);
        });
    
    });

})
</script>
@endsection