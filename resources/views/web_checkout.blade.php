@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_checkout.css')}}">
@endsection

@section('content_right')
{!!$Breadcrum!!}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible">
    
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    
    <h4><i class="icon fa fa-ban"></i> 錯誤 </h4>
    
        
        @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
</div>
@endif

<div class="box box-solid">
    
    <div class="box-header with-border">
        <i class="fa fa-fw fa-shopping-cart"></i>
        <h3 class="box-title">結帳頁面</h3>
    </div>
    
    
    <div class="box-body" id="checkout_content">

        <form action="{{url('/done')}}" method="post" id="checkout_form" >
        {!! csrf_field() !!}
        <!-- 配送區域 -->
        <div class="col-md-12 col-sm-12 col-xs-12">
        
            <span>配送區域:</span>

            <div class="form-group" id='ship_area_select'>
            
            <!-- 國家 -->
            <select id='country' name="country" class="form-control custom_form_control">
            @foreach( $countrys as $countryk => $country)
                @if(session()->has('chsCountry'))
                    <option @if( $country['region_id'] == session()->get('chsCountry') ) SELECTED @endif value="{{$country['region_id']}}">{{$country['region_name']}}</option>
                @else
                    <option @if( $country['region_id'] == 1 ) SELECTED @endif value="{{$country['region_id']}}">{{$country['region_name']}}</option>
                @endif
            @endforeach
            </select>
            <!-- 國家結束 -->
                
            <!-- 州 -->
            @if( $provinces != false )
            <select id='province' name="province" class="form-control custom_form_control">
            @foreach( $provinces as $provincek => $province)
                @if(session()->has('chsCountry'))
                    <option @if( $province['region_id'] == session()->get('chsProvince') ) SELECTED @endif value="{{$province['region_id']}}" >{{$province['region_name']}}</option>
                @else
                    <option @if( $province['region_id'] == 1 ) SELECTED @endif value="{{$province['region_id']}}">{{$province['region_name']}}</option>
                @endif
            @endforeach
            </select>
            @endif
            <!-- 州結束 -->
                
            <!-- 縣市 -->
            @if( $citys != false )
            <select id='city' name="city" class="form-control custom_form_control">
            @foreach( $citys as $cityk => $city)

                @if(session()->has('chsCity'))
                    <option @if( $city['region_id'] == session()->get('chsCity') ) SELECTED @endif value="{{$city['region_id']}}" >{{$city['region_name']}}</option>
                @else
                    <option @if( $city['region_id'] == 1 ) SELECTED @endif value="{{$city['region_id']}}">{{$city['region_name']}}</option>
                @endif  

            @endforeach                    
            </select>
            @endif
            <!-- 縣市結束 --> 

            </div>


        </div>    
        <!-- /配送區域 -->

            <!-- 配送方式 -->
            <div class="col-md-12 col-sm-12 col-xs-12" id='shipArea'>
                <span>配送方式:</span><br>

                @foreach( $shipping_list as $shipping_listk => $shipping_listv)
                <div class='col-md-3 col-sm-3 col-xs-6 shipBox'>
                    <input type="radio" name="shipping" id="shipbox{{$shipping_listk}}" value="{{$shipping_listv['shipping_id']}}" @if( session()->has('chsShip') && session()->get('chsShip') == $shipping_listv['shipping_id']) checked @endif>
                    <label class='shipLabel' for="shipbox{{$shipping_listk}}" >
                        {{$shipping_listv['shipping_name']}}
                        <br>
                        {{$shipping_listv['shipping_fee']}}
                        <br>
                        消費滿{{$shipping_listv['shipping_fee_free']}}免運
                    </label>

                    <span class='hideInput'>
                        
                        <div class="striped">
                            <!-- 針對全家增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get' )  
                           
            <!--             <th>超商店名：</th>
                                <td>
                                    <input type="text" class="super_name2 form-control custom_form_control " name="super_name2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled"/>
                                    <span id="super_get_btn" onclick="open_select_store('FAMI')" style="margin-bottom:10px">選擇全家門市</span>
                                </td> -->
                            
                            <div class="form-group">
                                <label for="">超商店名：</label>
                                <input type="text" class="super_name2 form-control custom_form_control" name="super_name2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSStoreName']}}@endif" readonly disabled="disabled"/>
                                <span id="super_get_btn" onclick="open_select_store('FAMI')" style="margin-bottom:10px">選擇全家門市</span>
                            </div>                                
                            
                            <div class="form-group">
                                <label for="">超商地址：</label>
                                <input type="text" class="super_addr2 form-control custom_form_control" name="super_addr2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSAddress']}}@endif"  readonly disabled="disabled"/>
                                <input type="hidden" class="super_no2 form-control custom_form_control" name="super_no2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSStoreID']}}@endif" disabled="disabled" />
                                <input type="hidden" value="" class="now_shipping_code form-control custom_form_control" name="now_shipping_code" disabled="disabled" />
                                <input type="hidden" class="super_type form-control custom_form_control" name="super_type" value="FAMI" disabled="disabled" />                                  
                            </div>
                                   
                            @endif
                            <!-- 針對全家增設獨立表格結束 -->

                            <!-- 針對7-11增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get2' )   
                            <div class="form-group">
                                <label for="">超商店名：</label>
                                <input type="text" class="super_name2 form-control custom_form_control" name="super_name2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled"/>
                                <span id="super_get_btn" onclick="open_select_store('UNIMART')" style="margin-bottom:10px" >選擇7-11門市</span><br> 
                            </div>  

                            <div class="form-group">
                                <label for="">超商地址：</label>
                                <input type="text" class="super_addr2 form-control custom_form_control" name="super_addr2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSAddress']}}@endif" readonly disabled="disabled"/>
                                
                                <input type="hidden" class="super_no2" name="super_no2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSStoreID']}}@endif" disabled="disabled" />
                                
                                <input type="hidden" value="" class="now_shipping_code" name="now_shipping_code" disabled="disabled" />
                                    
                                <input type="hidden" class="super_type" name="super_type" value="UNIMART" disabled="disabled" /> 
                            </div> 

                            @endif
                            <!-- 針對7-11增設獨立表格結束 -->

                            <!-- 針對萊爾富增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get3' ) 
                            <div class="form-group">
                                <label for="">超商店名：</label>
                                <input type="text" class="super_name2 form-control custom_form_control" name="super_name2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled"/>
                                <span id="super_get_btn" onclick="open_select_store('HILIFE')" style="margin-bottom:10px">選擇萊爾富門市</span>
                            </div> 

                            <div class="form-group">
                                <label for="">超商地址：</label>
                                    <input type="text" class="super_addr2 form-control custom_form_control" name="super_addr2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSAddress']}}@endif" readonly disabled="disabled"/>
                                    <input type="hidden" class="super_no2" name="super_no2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSStoreID']}}@endif" disabled="disabled"/>
                                    <input type="hidden" value="" class="now_shipping_code" name="now_shipping_code" disabled="disabled"/>
                                    <input type="hidden" class="super_type" name="super_type" value="HILIFE" disabled="disabled"/>
                            </div>                             
                                                        
                            @endif
                            <!-- 針對萊爾富增設獨立表格結束 -->
                            
                            @if( COUNT($address_sets) > 0 )
                            <div class="form-group">
                                <label for=""><font color="red"></font>快速地址：</label>
                                <select id='address_set' class="form-control custom_form_control">
                                    <option value='0' >不使用</option>
                                    @foreach( $address_sets as $address_setk=>$address_set )
                                    <option value="{{$address_set['id']}}">{{$address_set['address_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @if( $shipping_listv['shipping_code'] == 'super_get' || $shipping_listv['shipping_code'] == 'super_get2' || $shipping_listv['shipping_code'] == 'super_get3')
                            <div class="form-group">
                                <label for=""><font color="red">*</font>收貨人：</label>
                                <input type="text" class="super_consignee form-control " name="super_consignee" disabled="disabled" @if($member_datas && $member_datas['name'])value="{{ $member_datas['name']}}"@endif/>

                            </div> 

                            <div class="form-group">
                                <label for=""><font color="red">*</font>手機：</label>
                                <input type="text" class="super_mobile form-control" name="super_mobile" placeholder="格式:0912345678" disabled="disabled" @if($member_datas && $member_datas['phone'])value="{{ $member_datas['phone']}}"@endif/>

                            </div> 

                            <div class="form-group">
                                <label for="">電子郵件：</label>
                                <input type="text" class="super_email form-control" name="super_email" disabled="disabled" @if($member_datas && $member_datas['email'])value="{{ $member_datas['email']}}"@endif/>(訂單收信用)
                            </div> 

                            @else
                            <div class="form-group">
                                <label for=""><font color="red">*</font>收貨人：</label>
                                <input type="text" name="consignee" class="form-control" disabled="disabled" @if($member_datas && $member_datas['name'])value="{{ $member_datas['name']}}"@endif />
                            </div>

                            <div class="form-group">
                                <label for="" style='display:block;vertical-align:bottom;'><font color="red">*</font>收貨地址：</label>
                                <input type="text" name="address" value="" class="form-control"/>
                            </div>                            
                                 
                            <div class="form-group">
                                <label for=""><font color="red"></font>郵遞區號：</label>
                                <input type="text" name="zipcode" value="" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label for=""><font color="red"></font>電子郵件：</label>
                                <input name="email" type="text" class="form-control" @if($member_datas && $member_datas['email'])value="{{ $member_datas['email']}}"@endif />(訂單收信用)
                            </div>                            

                            <div class="form-group">
                                <label for=""><font color="red">*</font>手機：</label>
                                <input name="mobile" type="text" class="form-control" placeholder="格式:0912345678" @if($member_datas && $member_datas['phone'])value="{{ $member_datas['phone']}}"@endif/>
                            </div>                                

                            <div class="form-group">
                                <label for=""><font color="red"></font>電話：</label>
                                <input type="text" name="tel" class="form-control" @if($member_datas && $member_datas['tel'])value="{{ $member_datas['tel']}}"@endif/>
                            </div>                             

                            
                            <div class="form-group">
                                
                                <label for=""><font color="red"></font>送貨時間</label>
                                <select class="form-control" name="best_time" >
                                    <option value="" >請選擇</option>
                                    <option value="13點前" >13點前</option>
                                    <option value="13~18點前" >13~18點前</option>
                                    <option value="不指定" >不指定</option>
                                </select> 

                            </div>

                            <div class="form-group">
                                
                                <label for=""><font color="red"></font>收貨備註</label>
                                <select class="form-control" name="sign_building" >
                                        <option value="本人親收">
          
                                            本人親收
          
                                        </option>
                                
                                        <option value="管理員(警衛)代收" >管理員(警衛)代收</option>
                                
                                        <option value="親友代收" >親友代收</option>
                                </select> 
                                
                            </div>  
                            @endif                                                

                        </div>                        

                    </span>
                </div>
                @endforeach
            </div>
            <!-- 配送方式結束 --> 

            <div class="col-md-12 col-sm-12 col-xs-12" id='consigneeArea'>
            
            </div>    

            <!-- 付款方式 -->
            <div class="col-md-12 col-sm-12 col-xs-12" id='payArea'>
                <span><font color="red">*</font>付款方式:</span><br>

                @foreach( $payment_list as $paymentk => $payment)
                <div class="col-md-3 col-sm-3 col-xs-12 payBox">
                    
                    <input type="radio" name="payment" id="paybox{{$paymentk}}" value="{{$payment['pay_id']}}">
                    <label class='paymentLabel' for="paybox{{$paymentk}}" >
                        {{$payment['pay_name']}}
                    </label>
                    <span class='payIntro'>
                        {!!$payment['pay_desc']!!}
                    </span>

                </div>
                @endforeach

            </div>
            <!-- 付款方式結束 -->

            <!-- 付款方式解釋 -->
            <div class="col-md-12 col-sm-12 col-xs-12" id='payDesArea'>
                <div class="col-md-12 col-sm-12 col-xs-12" id="payDesBox">
                </div>
            </div>
            <!-- 付款方式解釋結束 -->    

            @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
            <div class="col-md-12 col-sm-12 col-xs-12">
                <span><font color="red"></font>折價券:</span><br>
                
                <div class="input-group">
                    <input type="text" class="form-control" id='bonus_sn' name='bonus_sn'>
                    <span class="input-group-btn">
                        <button type="button" class="btn colorbtn btn-flat" id='validation_bonus'>檢查折價券</button>
                    </span>
                </div>

            </div>
            @endif

            <!-- 電子發票 -->
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="invArea">
            
                <label for=""><font color="red"></font>電子發票:</label>

                <select class="form-control" id="carruer_type" name="carruer_type" onChange="return showdiv()" style="margin-bottom:10px;">
                    <OPTION value="1">依會員(無載具者用)</OPTION>
                    <OPTION value="2">自然人憑證</OPTION>
                    <OPTION value="3">手機載具</OPTION>
                    <OPTION value="4">捐贈</OPTION>
                    <OPTION value="5">索取紙本發票</OPTION>
                </select>                 

            </div>
            <!-- 電子發票結束 -->

            <!-- 電子發票附加  -->
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="invMoreArea">

                <span id='invText1' style="display:none;" class="invTool" >輸入自然人憑證號碼:</span>
                <span id='invText2' style="display:none;" class="invTool" >輸入向財政部申請之手機條碼:</span>

                <br>
                    
                    <select class="form-control" name="loveCode" style="margin-bottom:10px;display:none" class="invTool">
                        <OPTION value="5252">社團法人中華民國身心障礙聯盟</OPTION>
                        <OPTION value="321">財團法人中華民國唐氏症基金會</OPTION>
                        <OPTION value="885521">財團法人中華民國兒童福利聯盟文教基金會</OPTION>
                        <OPTION value="919">財團法人創世社會福利基金會</OPTION>
                        <OPTION value="7699">財團法人基督教瑪喜樂社會福利基金會</OPTION> 
                    </select>                     
                      
                    <input type="text" name="ei_code" size="30" id="eiCode" style="margin: 5px;display:none" class="invTool form-control">
                    
                    <p id="loveText" style="color:rgb(253, 0, 115);margin: 0px;display:none;" class="invTool" >(感謝您的愛心捐贈，系統將發票開立後，資料將通知各該受捐贈機構，依據法令規定，已捐贈的發票無法索取紙本發票及更改捐贈對象，如有退換貨需求，本公司將會將該發票作廢。)</font>

                    </p>


            </div>
            <!-- 電子發票附加結束 -->    

            <!-- 統編資料 -->
            <div class="col-md-12 col-sm-12 col-xs-12" id="companyArea">
                <span>統編資料 - </span> <a href="javascript:;"><span class="add_inv">如需要請點選</span></a>

                <div class="option_inner inv_info" style="display:none;">

                    <div class="form-group">
                        <label for=""><font color="red"></font>統一編號：</label>
                        <input type="text" name="inv_payee" value="" class="form-control"/>
                    </div>                             

                    <div class="form-group">
                        <label for=""><font color="red"></font>發票抬頭：</label>
                        <input type="text" name="inv_content" value="" class="form-control"/>
                    </div> 

                </div>                
            </div>
            <!-- 統編資料結束 -->

            <!-- 備註 -->
            <div class="col-md-12 col-sm-12 col-xs-12" id="noteArea">
            <span>訂單備註:</span>
                
                <textarea name="postscript" rows="5" id="postscript"  placeholder="可在此留言備註配送事項" class="form-control" ></textarea>            

            </div>
            <!-- 備註結束 -->

            <!-- checkout 小計 -->
            <div class='col-md-12 col-sm-12 col-xs-12 _np' id='check_sub'>
            @if( count($check_sub) )
            <div id='check_sub_in' class="col-md-12 col-sm-12 col-xs-12">
                <table>
                    <tr>
                        <td class='textright'>商品金額 : {{$check_sub['goods_amount']}}</td>
                    </tr>
                    <tr>

                        <td class='textright'>運費 : {{$check_sub['shipping_fee']}}</td>
                    </tr>

                    <tr>
                        <td class='textright'>總價 : {{$check_sub['order_amount']}}</td>
                    </tr>                                 
                </table>
                
                <h4>@if( $check_sub['achieve_percent'] < 100) 目前只要再{{$check_sub['diff_for_free']}}即享免運 @else 已達免運標準 @endif</h4>
                <div class="cart-progress-bar col-md-8 col-sm-12 col-xs-12" percent="{{$check_sub['achieve_percent']}}%">
                    <div class="cart-progress" id="progress" style="width:{{$check_sub['achieve_percent']}}%;" percent="{{$check_sub['achieve_percent']}}%">
                    </div>
                </div>
                @if( count($shipfree_recommends) > 0)
                    <div class='col-md-12 col-sm-12 col-xs-12'></div>
                    @foreach( $shipfree_recommends as $shipfree_recommendk => $shipfree_recommend )
                    <div class='col-md-3 col-sm-4 col-xs-6 show_goods_box'>

                        <div class="thumbnail">
                            
                            <a href="{{url('/show_goods/'.$shipfree_recommend['goods_id'])}}" title="查看商品:{{$shipfree_recommend['goods_name']}}詳細內容">
                                <img lazysrc="https://***REMOVED***.com/***REMOVED***/{{$shipfree_recommend['goods_thumb']}}" data-holder-rendered="true" class="lazyload" alt="{{ $shipfree_recommend['goods_name'] }},貨號:{{ $shipfree_recommend['goods_sn'] }},價格:{{ $shipfree_recommend['shop_price'] }}">
                            </a>
                            
                            <div class="caption">
                                <p class='goods_sn'>貨號:{{ $shipfree_recommend['goods_sn'] }}</p>
                                <a href="{{url('/show_goods/'.$shipfree_recommend['goods_id'])}}" title="查看商品:{{$shipfree_recommend['goods_name']}}詳細內容">
                                    <h4 class="goods_title">{{ $shipfree_recommend['goods_name'] }}</h4>
                                </a>
                    
                                <p class='goods_price'><small>$</small>{{ $shipfree_recommend['shop_price'] }}</p>
                                
                                <p class='goods_add_btn'><a  class="btn colorbtn add_to_cart" role="button" goods_id="{{$shipfree_recommend['goods_id']}}" title="將{{ $shipfree_recommend['goods_name'] }}加入購物車">立即購買</a></p>
                            </div>
                        </div>                
                    </div>                    
                    @endforeach
                @endif
            </div>
            @endif
            </div>
            <!-- /checkout 小計 -->
            
            <div class="col-md-12 col-sm-12 col-xs-12" id="submitArea">                                    
                
                <input type="submit" class="btn bg-maroon btn-flat margin" value="送出" id="checkOutBtn"> 

            </div>    
        </form>
    </div>

</div>




<div class="modal modal-danger fade " tabindex="-1" role="dialog">
    
    <div class="modal-dialog " role="document">
        
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">檢查失敗</h4>
            </div>
      
            <div class="modal-body">
                <p id='formErrTxt'></p>
            </div>
        
        </div><!-- /.modal-content -->
     
    </div><!-- /.modal-dialog -->

</div><!-- /.modal -->


<div id="map_modal" class="modal">
    <div class="modal-content" id='map_content'>
    </div>
</div>
@endsection

@section('selfjs')

<script type="text/javascript">

$(document).ready(function(){
    
    $("#validation_bonus").click(function(){

        var bonusAjax = $.ajax({
            url: "{{url('/validate_bonus')}}",
            method: "POST",
            data: { 
                    _token : "{{ csrf_token() }}",
                    bonus_sn: $("#bonus_sn").val(),
                    type : 1
            },
            dataType: "json"
        });

        bonusAjax.done(function( res ) {
            
            if( res[0] == true ){
                
                alert( res[1] );

            }else{

                alert( res[1]);
            }
        });
 
        bonusAjax.fail(function( jqXHR, textStatus ) {
        
        }); 

    });

    $('.taskTooltip').tooltip({trigger: 'manual'}).tooltip('show');

    /*----------------------------------------------------------------
     | 選取國家動態轉換州的內容
     |----------------------------------------------------------------
     |
     */
    $('body').on('change', '#country', function() {
        
        var area = $(this).val();

        var countryAjax = $.ajax({
            url: "{{url('/areaChange')}}",
            method: "POST",
            data: { 
                    _token : "{{ csrf_token() }}",
                    area : area ,
                    type : 1
            },
            dataType: "json"
        });
 
        countryAjax.done(function( res ) {
            
            if( res[0] == true ){

                location.reload();
            }
        });
 
        countryAjax.fail(function( jqXHR, textStatus ) {
        
        });        
    });




    /*----------------------------------------------------------------
     | 選取州轉換程式內容
     |----------------------------------------------------------------
     |
     */
     $('body').on('change', '#province', function() {
        
        var area = $(this).val();

        var countryAjax = $.ajax({
            url: "{{url('/areaChange')}}",
            method: "POST",
            data: { 
                    _token : "{{ csrf_token() }}",
                    area : area ,
                    type : 2
            },
            dataType: "json"
        });
 
        countryAjax.done(function( res ) {
            
            if( res[0] == true ){

                location.reload();
            }
        });
 
        countryAjax.fail(function( jqXHR, textStatus ) {
        
        });        
    });




    /*----------------------------------------------------------------
     | 城市切換 
     |----------------------------------------------------------------
     |
     */
    $('body').on('change', '#city', function() {     
        
        var area = $(this).val();

        var countryAjax = $.ajax({
            url: "{{url('/areaChange')}}",
            method: "POST",
            data: { 
                    _token : "{{ csrf_token() }}",
                    area : area ,
                    type : 3
            },
            dataType: "json"
        });
 
        countryAjax.done(function( res ) {
            
            // if( res[0] == true ){

            //     location.reload();
            // }
        });
 
        countryAjax.fail(function( jqXHR, textStatus ) {
        
        });        
    });     
    



    /*----------------------------------------------------------------
     | 表單呈現及隱藏切換
     |----------------------------------------------------------------
     |
     */
    $("input[name='shipping']").change(function(){
        
        var ship = $("input[name='shipping']:checked").val();
        // 先記錄選擇了甚麼配送
        var shipAjax = $.ajax({
            url: "{{url('/shipChange')}}",
            method: "POST",
            data: { 
                    _token : "{{ csrf_token() }}",
                    ship : ship ,
            },
            dataType: "json"
        });
 
        shipAjax.done(function( res ) {
            if( $("#checkout_content").length >0)
            {
                var request = $.ajax({
                    url: "{{url('/ajax_shipping_free_recommend')}}",
                    method: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    dataType: "json"
                });
     
                request.done(function( return_data ) {
                            
                    $("#check_sub").empty();
    
                    $("#check_sub").append( return_data );
                });
                request.fail(function( jqXHR, textStatus ) {
    
                });                    
            }             
        });
 
        shipAjax.fail(function( jqXHR, textStatus ) {
        }); 

        var chsShip = $("input[name='shipping']:checked").parent();
        
        // 清空收貨人訊息區塊
        $("#consigneeArea").empty();
        
        // 取消鎖定
        $(".hideInput input").removeAttr("disabled");
        
        var tmpHTML = chsShip.children('.hideInput').html();

        $("#consigneeArea").html( tmpHTML );
        
        // 恢復鎖定
        $(".hideInput input").attr("disabled","disabled");

    })
    


    
    /*
    |--------------------------------------------------------------------------
    | 快速地址功能 
    |--------------------------------------------------------------------------
    |
    |
    */




    /*----------------------------------------------------------------
     | 頁面載入時判斷是否要進行呈現表單
     |----------------------------------------------------------------
     |
     */
    if ($("input[name='shipping']:checked").val()) {
        
        $("input[name='shipping']:checked").trigger('change');

    }




    /*----------------------------------------------------------------
     | 切換付款方式時 , 呈現不同說明
     |----------------------------------------------------------------
     |
     */
    $("input[name='payment']").change(function(){
        
        var chsPayment = $("input[name='payment']:checked");

        $("#payDesBox").empty();
        
        var nowPaymentDes = chsPayment.parent().children(".payIntro").html();

        $("#payDesBox").html( nowPaymentDes );
    });




    /*----------------------------------------------------------------
     | 呈現統一編號資料
     |----------------------------------------------------------------
     |
     */
    $(".add_inv").click(function(){
        
        if( $('.inv_info').is(":visible") ){
              
            $('.inv_info').children('input').val("");
            $('.inv_info').hide();

        }else{

            $('.inv_info').show();
        }
    });
    


    /*----------------------------------------------------------------
     | 表單檢查
     |----------------------------------------------------------------
     |
     */
    $('#checkout_form').submit(function(){ 
        

        // 立即封鎖提交按鈕 , 避免重複提交
        $("#checkOutBtn").prop('disabled', true);

        var nowCountry = $("select[name=country]").val();
        
        var form = $(this); 
        // 確認配送區域

        
        if( $("#country").val() == 0 ){

            $("#formErrTxt").empty();
            $("#formErrTxt").append('請確認配送區域確實填寫。');
            $('.modal').modal();
            $("#checkOutBtn").prop('disabled', false);

            return false;
        }
        
        // 只有台灣要做2.3階層判斷
        if( nowCountry == '1'){
        
            if( $("#province").val() == 0 ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('請確認配送區域確實填寫。');
                $('.modal').modal();
                $("#checkOutBtn").prop('disabled', false);                
                return false;
            }

            if( $("#city").val() == 0 ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('請確認配送區域確實填寫。');
                $('.modal').modal();
                $("#checkOutBtn").prop('disabled', false);                
                return false;
            }
        }  
            
        // 判斷選取配送方式
        if (form.find('input[name="shipping"][type!="hidden"]').length > 0 && form.find('input[name="shipping"]:checked').length < 1) {
            $("#formErrTxt").empty();
            $("#formErrTxt").append('尚未選取配送方式');
            $('.modal').modal();
            $("#checkOutBtn").prop('disabled', false);      
            return false;
        }        
        
        // 取出配送方式  
        var nowShip = form.find('input[name=shipping]:checked').val();

        // 取出發票載具
        var carruer_type = $("#carruer_type").val();          
        
        // 根據不同配送方式使用不同驗證
        if( nowShip == '17' || nowShip == '18' || nowShip == '19' ){
        


            if( !$("input[name='super_name2']").last().val().length  ){
    

                $("#formErrTxt").empty();
                $("#formErrTxt").append('超商店名欄位為必填。');
                $('.modal').modal();                
                $("#checkOutBtn").prop('disabled', false);          
                return false;
            }   
    
            if( !$("input[name='super_addr2']").last().val().length  ){
    

                $("#formErrTxt").empty();
                $("#formErrTxt").append('超商地址欄位為必填。');
                $('.modal').modal();                 
                $("#checkOutBtn").prop('disabled', false);          
                return false;
            }
           
            if( !$("input[name='super_consignee']").last().val().length  ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('收貨人欄位為必填。');
                $('.modal').modal();                            
                $("#checkOutBtn").prop('disabled', false);          
                return false;
            }     
    
            if( !$("input[name='super_mobile']").last().val().length  ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('手機欄位為必填。');
                $('.modal').modal();                
                $("#checkOutBtn").prop('disabled', false);          
                return false;
            }   
    
            regex = /^[09]{2}[0-9]{8}$/;
            if (!regex.test($("input[name='super_mobile']").last().val()) ){
    
                $("#formErrTxt").empty();
                $("#formErrTxt").append('手機格式錯誤,請再次確認。');
                $('.modal').modal();                       
                $("#checkOutBtn").prop('disabled', false);          
                return false;  
            }
    
            if( carruer_type == '2'){
    
                var regexp = /^[A-Z]{2}[0-9]{14}$/;
                
                if( !regexp.test( $("#eiCode").val() ) ){
    
                    $("#formErrTxt").empty();
                    $("#formErrTxt").append('自然人憑證格式錯誤,請再次確認。<br> 自然人憑證格式為:<br> 2碼大寫英文 + 14碼由0 ~ 9數字所組成 <br>範例:AW12556987322213');
                    $('.modal').modal();                         
                    $("#checkOutBtn").prop('disabled', false);          
                    return false;               
                }
                
            }        
            if( carruer_type == '3'){
                
                var regexp = /^\/{1}[0-9A-Z\.\-\+]{7}$/;
    
                if( !regexp.test( $("#eiCode").val() ) ){
    
                    $("#formErrTxt").empty();
                    $("#formErrTxt").append('手機載具格式錯誤,請再次確認。 <br> 手機載具格式為:<br> 以 / 開頭 + 7碼由 0~9、A~Z(大寫)、符號: . + - 所組成<br>範例:/94168A+');
                    $('.modal').modal();                    
                    $("#checkOutBtn").prop('disabled', false);          
                    return false;               
                }       
    
            }        


            /*
            if( !$("input[name='super_email']").last().val().length  ){
    
                cAlert("電子郵件欄位為必填。");
                return false;
            }
            regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if( !regex.test($("input[name='super_email']").last().val()) ){
    
                cAlert("電子郵件格式錯誤,請再次確認。");
                return false; 
            }                 
            */
        
        }else{

            if( !$("input[name='consignee']").last().val().length  ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('收件人欄位為必填。');
                $('.modal').modal();                 
                $("#checkOutBtn").prop('disabled', false);          
                return false;
            }

            if( !$("input[name='address']").last().val().length ){
            
                $("#formErrTxt").empty();
                $("#formErrTxt").append('收件地址欄位為必填。');
                $('.modal').modal();                      
                $("#checkOutBtn").prop('disabled', false);          
                return false;           
            }
        /*
        if( !$("input[name='email']").last().val().length ){
            
            cAlert("電子郵件欄位為必填。");
            return false;           
        }

        regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if( !regex.test($("input[name='email']").last().val()) ){

            cAlert("電子郵件格式錯誤,請再次確認。");
            return false; 
        }
        */
            if( !$("input[name='mobile']").last().val().length){
            
                $("#formErrTxt").empty();
                $("#formErrTxt").append('手機欄位為必填。');
                $('.modal').modal();                   
                $("#checkOutBtn").prop('disabled', false);          
                return false;           
            }

        
            regex = /^[09]{2}[0-9]{8}$/;
            
            if (!regex.test($("input[name='mobile']").last().val()) ){

                $("#formErrTxt").empty();
                $("#formErrTxt").append('手機格式錯誤,請再次確認。');
                $('.modal').modal();                  
                $("#checkOutBtn").prop('disabled', false);          
                return false;  
            }

            if( carruer_type == '2'){

                var regexp = /^[A-Z]{2}[0-9]{14}$/;
            
                if( !regexp.test( $("#eiCode").val() ) ){

                    $("#formErrTxt").empty();
                    $("#formErrTxt").append('自然人憑證格式錯誤,請再次確認。<br> 自然人憑證格式為:<br> 2碼大寫英文 + 14碼由0 ~ 9數字所組成 <br>範例:AW12556987322213');
                    $('.modal').modal();                    
                    $("#checkOutBtn").prop('disabled', false);          
                    return false;               
                }
             
            }        
            
            if( carruer_type == '3'){
            
                var regexp = /^\/{1}[0-9A-Z\.\-\+]{7}$/;

                if( !regexp.test( $("#eiCode").val() ) ){

                    $("#formErrTxt").empty();
                    $("#formErrTxt").append('手機載具格式錯誤,請再次確認。 <br> 手機載具格式為:<br> 以 / 開頭 + 7碼由 0~9、A~Z(大寫)、符號: . + - 所組成<br>範例:/94168A+');
                    $('.modal').modal();                      
                    $("#checkOutBtn").prop('disabled', false);          
                    return false;               
                }       

            }
        }

        if (form.find('input[name="payment"][type!="hidden"]').length > 0 && form.find('input[name="payment"]:checked').length < 1) {
            
            $("#formErrTxt").empty();
            $("#formErrTxt").append('尚未選取付款方式');
            $('.modal').modal();   
            $("#checkOutBtn").prop('disabled', false);      
            return false;
        }
        
    });
    
    $(document).on('change','#address_set',function(){
        
        selectAddress = $(this).val();
    
        if( selectAddress > 0 )
        {
            var menuId = $( "ul.nav" ).first().attr( "id" );
            
            var request = $.ajax({
            
                url: "{{url('/ajax_df_address')}}",
                method: "POST",
                data: { 
                    address_id : selectAddress,
                    _token : "{{ csrf_token() }}",
                },
                dataType: "json"

            });
 
            request.done(function( return_datas ) {
                
                if( return_datas != false )
                {
                    fill_df_address( return_datas );
                }
            });
 
            request.fail(function( jqXHR, textStatus ) {
                
                //alert( "Request failed: " + textStatus );
            
            });
        }

    });
});




/*----------------------------------------------------------------
 | 判斷是否為手機
 |----------------------------------------------------------------
 | 此方法使用是否有觸控事件做為判斷依據 , 如果有的話 , 表示為行動
 | 裝置 , 但是如果是有觸控螢幕的筆電會有被誤判的情形發生
 |
 */
function isMobile() {

  try{ document.createEvent("TouchEvent"); return true; }

  catch(e){ return false;}

}




/*----------------------------------------------------------------
 | 呼叫選取地址
 |----------------------------------------------------------------
 | 由於選取超商地址及編號的介面分為兩種 , 最好優先判斷是要呼叫
 | 手機版還是電腦版 , 避免畫面跑版
 |
 */
if( !isMobile() ){
// 電腦版
var open_select_store = function(type){
    
    window.open("{{url('/storeMap')}}"+"/0/"+type,'_self','');
}

}else{

// 手機版
var open_select_store = function(type){
    
    window.open("{{url('/storeMap')}}"+"/1/"+type,'_self','');
}    
}




/*----------------------------------------------------------------
 | 根據選取的電子發票格式呈現不同欄位
 |----------------------------------------------------------------
 |
 */
function showdiv( ){

    var nowinv = $("#carruer_type").val();
    
    $(".invTool").hide();

    if( nowinv == 2 ){

        $("#invText1").show();
        $("#eiCode").show();
    }

    if( nowinv == 3 ){

        $("#invText2").show();
        $("#eiCode").show();
    }

    if( nowinv == 4 ){

        $("#loveCode").show();
        $("#loveText").show();
    }    

}




/*
|--------------------------------------------------------------------------
| 預設地址填寫
|--------------------------------------------------------------------------
|
*/
function fill_df_address( df_address ){
    
    /***
     * 區域處理
     **/    
    $("#ship_area_select").empty();
    $("#ship_area_select").append( df_address['ajax_area_select']);
    

    /***
     * 資料填寫處理
     **/
    if( $("#consigneeArea select[name='best_time'] ").length )
    { 
        // 回填宅配表格
        $("#consigneeArea input[name='consignee']").val( df_address['consignee'] );
        $("#consigneeArea input[name='address']").val( df_address['address'] );
        $("#consigneeArea input[name='email']").val( df_address['email'] );
        $("#consigneeArea input[name='mobile']").val( df_address['mobile'] );
        $("#consigneeArea input[name='tel']").val( df_address['tel'] );
        //$("#consigneeArea input[name='consignee']").val( df_address['consignee'] );
    }
    else
    {
        // 回填超商表格
    }
     
    
}




/*
|--------------------------------------------------------------------------
| 宅配站所提示功能
|--------------------------------------------------------------------------
|
*/

// 取得宅配站所
function get_cat_stoe(){
    
    $(".fast_cat").remove();

    $("input[name='address']").removeClass("input_half");

    cityVal     = $("#city").val();

    shippingVal = $("input[name='shipping']:checked").val();

    if ( typeof cityVal !== "undefined" && typeof shippingVal !== "undefined" && shippingVal == 20 ) {
        
        var menuId = $( "ul.nav" ).first().attr( "id" );

        var request = $.ajax({
            url: "{{url('/get_cat_store')}}",
            method: "POST",
            data: {  
                _token : "{{ csrf_token() }}", 
                city:$( "#city option:selected" ).text(),
                shipping:shippingVal
            },
            dataType: "JSON"
        });
         
        request.done(function( return_datas ) {
            
            if( return_datas != false )
            {   
                $("input[name='address']").addClass("input_half");
                $(".fast_cat").remove();
                $("input[name='address']").after( decodeURIComponent(return_datas) );
            }
        });
         
        request.fail(function( jqXHR, textStatus ) {
          //alert( "Request failed: " + textStatus );
        });

    }    

}

$('body').on('change', '#city', function() {

    get_cat_stoe();
});
$('body').on('click', "input[name='shipping']", function() {

    get_cat_stoe();
});

function select_cat_store(){
    $(this).after('<button type="button" class="btn btn-primary map_btn"><i class="glyphicon glyphicon-map-marker"></i></button>');
}


$('body').on('change', ".fast_cat", function() {
        
    $(".map_btn").remove();
    
    $(this).css({'padding-bottom':'6px'});

    if( $(this).val() != 0)
    {
        $("input[name=address]").val( $(this).val() );
        $(".cat_open_time").empty();
        $(".cat_open_time").append("站所營業時間:"+$(this).find('option:selected').attr('ot') );
        $(this).after('<span type="button" class="btn btn-primary map_btn"><i class="glyphicon glyphicon-map-marker center"></i></span>');
        $(this).css({'padding-bottom':'3px'});
        $(".map_btn").css({'padding':'6px','height':'34px' , 'float':'right' ,'background-color':'#ec7070' , 'border-color':'#ec7070'});   
        //$(".map_btn a ").css({'display':'inline-table','vertical-align':'middle'});             
    }
    else
    {
        $("input[name=address]").val( '' );
        $(".cat_open_time").empty();
        $(".map_btn").remove();
    }

});

$('body').on('click', ".map_btn", function() {

    if( $(this).prev('select').val() != 0)
    {
        $("#map_content").empty();
            
        $("#map_modal").show();
        //alert( $(this).prev('select').val() );
        $("#map_content").load("{{url('/get_cat_map')}}/"+$(this).prev('select').val());
    }

}); 

$('body').on('click', "#cat_map_close,#map_modal", function() {

    if( $("#map_modal").is(":visible") )
    {
        $("#map_modal").hide();
    }
});
</script>
@endsection