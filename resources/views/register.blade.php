@extends('league_front')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/register.css')}}">
@endsection

@section('content')
    <div class='col-md-4 col-md-offset-2 col-sm-12 col-xs-12' id='register_intro'>
        
        <div  class='label_box' label='加盟優勢' >
            <p>1.輕鬆自在免成本、免屯貨，沒有業績壓力，沒有庫存壓力，時間自已安排。</p>
            <p>2.銷售市場全國性、全球性，不用拋頭露面、免風吹日曬也能做生意賺大錢。</p>
            <p>3.自己當家作主，想賺錢，就上網；累了休息，訂單還是滾滾而來。</p>
            <p>4.所有進出貨、退換貨、商品上架、聯絡顧客、收款、等瑣事均由加盟總站一手包辦，加盟站只要負責讓網站曝光，其它都不用煩腦。 </p>
            <p>5.總公司每月都與日本、歐美同步上架新產品，求新求變以應付廣大的網友需求。 </p>
            <p>6.訂單獎金抽成20~30%</p>
        </div>

        <div  class='label_box' label='獎金計算' >
            
            <p>
               訂單金額的20%(需扣除訂單運費150元)
               <br>
               假設訂單有10筆，每筆1000元，您的紅利獎金就是(1000-150)x10x20%=1700元。
            </p>
 
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
                    </div>
                    
                    <div class="form-group">
                        <label for="password1">密碼</label>
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="請輸入密碼">
                    </div>
    
                    <div class="form-group">
                        <label for="password2">密碼確認</label>
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="請再次輸入密碼,確認密碼無誤">
                    </div>                
                    
                    <div class='form_subtitle'>加盟商基本資料</div>
    
                    <div class="form-group">
                        <label for="name">姓名:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名">
                    </div>

                    <div class="form-group">
                        <label for="phone">手機:</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="請輸入手機">
                    </div>     

                    <div class="form-group">
                        <label for="tel">電話:</label>
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="請輸入電話">
                    </div>  

                    <div class="form-group">
                        <label for="email">信箱:</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="請輸入信箱">
                    </div> 

                    <div class="form-group">
                        <label for="storename">加盟商店名稱:</label>
                        <input type="text" class="form-control" id="storename" name="storename" placeholder="請輸入商店名稱">
                    </div>  

                    <div class="form-group">
                        <label for="bank">獎金匯款銀行:</label>
                        <br>
                        <select name="bank" class="form-control" id="bank" original-title=''>
                                    <option value="0">請選擇</option>
                                    <option value="004">004 - 臺灣銀行</option>
                                    <option value="005">005 - 土地銀行</option>
                                    <option value="006">006 - 合作金庫商業銀行</option>
                                    <option value="007">007 - 第一銀行</option>
                                    <option value="008">008 - 華南銀行</option>
                                    <option value="009">009 - 彰化銀行</option>
                                    <option value="011">011 - 上海商業儲蓄銀行</option>
                                    <option value="012">012 - 台北富邦銀行</option>
                                    <option value="013">013 - 國泰世華銀行</option>
                                    <option value="016">016 - 高雄銀行</option>
                                    <option value="017">017 - 兆豐國際商業銀行</option>
                                    <option value="018">018 - 農業金庫</option>
                                    <option value="021">021 - 花旗（台灣）商業銀行</option>
                                    <option value="022">022 - 美國銀行</option>
                                    <option value="025">025 - 首都銀行</option>
                                    <option value="039">039 - 澳商澳盛銀行</option>
                                    <option value="040">040 - 中華開發工業銀行</option>
                                    <option value="050">050 - 臺灣企銀</option>
                                    <option value="052">052 - 渣打國際商業銀行</option>
                                    <option value="053">053 - 台中商業銀行</option>
                                    <option value="054">054 - 京城商業銀行</option>
                                    <option value="072">072 - 德意志銀行</option>
                                    <option value="075">075 - 東亞銀行</option>
                                    <option value="081">081 - 匯豐（台灣）商業銀行</option>
                                    <option value="085">085 - 新加坡商新加坡華僑銀</option>
                                    <option value="101">101 - 瑞興商業銀行</option>
                                    <option value="102">102 - 華泰銀行</option>
                                    <option value="103">103 - 臺灣新光商銀</option>
                                    <option value="104">104 - 台北五信</option>
                                    <option value="108">108 - 陽信商業銀行</option>
                                    <option value="114">114 - 基隆一信</option>
                                    <option value="115">115 - 基隆二信</option>
                                    <option value="118">118 - 板信商業銀行</option>
                                    <option value="119">119 - 淡水一信</option>
                                    <option value="120">120 - 淡水信合社</option>
                                    <option value="124">124 - 宜蘭信合社</option>
                                    <option value="127">127 - 桃園信合社</option>
                                    <option value="130">130 - 新竹一信</option>
                                    <option value="132">132 - 新竹三信</option>
                                    <option value="146">146 - 台中二信</option>
                                    <option value="147">147 - 三信商業銀行</option>
                                    <option value="158">158 - 彰化一信</option>
                                    <option value="161">161 - 彰化五信</option>
                                    <option value="162">162 - 彰化六信</option>
                                    <option value="163">163 - 彰化十信</option>
                                    <option value="165">165 - 鹿港信合社</option>
                                    <option value="178">178 - 嘉義三信</option>
                                    <option value="188">188 - 台南三信</option>
                                    <option value="204">204 - 高雄三信</option>
                                    <option value="215">215 - 花蓮一信</option>
                                    <option value="216">216 - 花蓮二信</option>
                                    <option value="222">222 - 澎湖一信</option>
                                    <option value="223">223 - 澎湖二信</option>
                                    <option value="224">224 - 金門信合社</option>
                                    <option value="503">503 - 基隆漁會</option>
                                    <option value="504">504 - 瑞芳／萬里漁會</option>
                                    <option value="505">505 - 頭城／蘇澳漁會</option>
                                    <option value="506">506 - 桃園漁會</option>
                                    <option value="507">507 - 新竹漁會</option>
                                    <option value="511">511 - 彰化區漁會</option>
                                    <option value="512">512 - 雲林區漁會</option>
                                    <option value="515">515 - 嘉義區漁會</option>
                                    <option value="517">517 - 南市區漁會</option>
                                    <option value="518">518 - 南縣區漁會</option>
                                    <option value="520">520 - 小港區漁會；高雄區漁</option>
                                    <option value="521">521 - 彌陀／永安／興達港／</option>
                                    <option value="523">523 - 東港／琉球／林邊區漁</option>
                                    <option value="524">524 - 新港區漁會</option>
                                    <option value="525">525 - 澎湖區漁會</option>
                                    <option value="600">600 - 農金資中心</option>
                                    <option value="603">603 - 基隆地區農會</option>
                                    <option value="605">605 - 高雄市農會</option>
                                    <option value="606">606 - 新北市農會</option>
                                    <option value="607">607 - 宜蘭地區農會</option>
                                    <option value="608">608 - 桃園地區農會</option>
                                    <option value="610">610 - 新竹地區農會</option>
                                    <option value="611">611 - 後龍農會</option>
                                    <option value="612">612 - 豐原市農會；神岡鄉農</option>
                                    <option value="613">613 - 名間農會</option>
                                    <option value="614">614 - 彰化地區農會</option>
                                    <option value="616">616 - 雲林地區農會</option>
                                    <option value="617">617 - 嘉義地區農會</option>
                                    <option value="618">618 - 台南地區農會</option>
                                    <option value="619">619 - 高雄地區農會</option>
                                    <option value="620">620 - 屏東地區農會</option>
                                    <option value="621">621 - 花蓮地區農會</option>
                                    <option value="622">622 - 台東地區農會</option>
                                    <option value="623">623 - 台北市農會</option>
                                    <option value="624">624 - 澎湖農會</option>
                                    <option value="625">625 - 台中市農會</option>
                                    <option value="627">627 - 連江縣農會</option>
                                    <option value="635">635 - 線西鄉農會</option>
                                    <option value="650">650 - 福興鄉農會</option>
                                    <option value="700">700 - 中華郵政</option>
                                    <option value="803">803 - 聯邦商業銀行</option>
                                    <option value="805">805 - 遠東銀行</option>
                                    <option value="806">806 - 元大銀行</option>
                                    <option value="807">807 - 永豐銀行</option>
                                    <option value="808">808 - 玉山銀行</option>
                                    <option value="809">809 - 凱基銀行</option>
                                    <option value="810">810 - 星展銀行</option>
                                    <option value="812">812 - 台新銀行</option>
                                    <option value="814">814 - 大眾銀行</option>
                                    <option value="815">815 - 日盛銀行</option>
                                    <option value="816">816 - 安泰銀行</option>
                                    <option value="822">822 - 中國信託</option>
                                    <option value="901">901 - 大里市農會</option>
                                    <option value="903">903 - 汐止農會</option>
                                    <option value="904">904 - 新莊農會</option>
                                    <option value="910">910 - 財團法人農漁會聯合資</option>
                                    <option value="912">912 - 冬山農會</option>
                                    <option value="916">916 - 草屯農會</option>
                                    <option value="919">919 - 三義鄉農會</option>
                                    <option value="922">922 - 台南市農會</option>
                                    <option value="928">928 - 板橋農會</option>
                                    <option value="951">951 - 北農中心</option>
                                    <option value="954">954 - 中南部地區農漁會</option>
                        </select>  
                        
                    </div>  
                    <div class="form-group">
                        <label for="bankaccount">分行名稱:</label>                    
                        <input type="text" class="form-control" id="banksub" name="banksub" placeholder="請輸入分行名稱" >
                     </div> 

                    <div class="form-group">
                        <label for="bankaccount">匯款帳號:</label>
                        <input type="text" class="form-control" id="bankaccount" name="bankaccount" placeholder="請輸入匯款帳號">
                    </div>  

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

    $("#stopregister_form").validate({
        
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
            }                              
        }        
    });

})
</script>
@endsection