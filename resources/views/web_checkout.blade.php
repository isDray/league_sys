@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_checkout.css')}}">
@endsection

@section('content_right')
<div class="box box-solid">
    
    <div class="box-header with-border">
        <i class="fa fa-fw fa-shopping-cart"></i>
        <h3 class="box-title">結帳頁面</h3>
    </div>
    
    
    <div class="box-body" id="cart_content">
        
        <!-- 配送區域 -->
        <div class="col-md-12 col-sm-12 col-xs-12">
        
            <span>配送區域:</span>

            <div class="form-group">
            
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
                <div class='col-md-3 col-sm-3 col-xs-4 shipBox'>
                    <input type="radio" name="shipping" id="shipbox{{$shipping_listk}}" value="{{$shipping_listv['shipping_id']}}" @if( session()->has('chsShip') && session()->get('chsShip') == $shipping_listv['shipping_id']) checked @endif>
                    <label class='shipLabel' for="shipbox{{$shipping_listk}}" >
                        {{$shipping_listv['shipping_name']}}
                        <br>
                        {{$shipping_listv['shipping_fee']}}
                        <br>
                        消費滿{{$shipping_listv['shipping_fee_free']}}免運
                    </label>

                    <span class='hideInput'>
                        
                        <table class="striped">
                            <!-- 針對全家增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get' )  
                            <tr>
                                <th>超商店名：</th>
                                <td>
                                    <input type="text" class="super_name2 form-control custom_form_control " name="super_name2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled"/>
                                    <span id="super_get_btn" onclick="open_select_store('FAMI')" style="margin-bottom:10px">選擇全家門市</span>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>超商地址：</th>
                                <td>
                                    
                                    <input type="text" class="super_addr2 form-control custom_form_control" name="super_addr2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSAddress']}}@endif"  readonly disabled="disabled"/>
                                    

                                    
                                    <input type="hidden" class="super_no2 form-control custom_form_control" name="super_no2" value="@if(session()->has('FAMI')){{session()->get('FAMI')['CVSStoreID']}}@endif" disabled="disabled" />
                                    

                                    
                                    <input type="hidden" value="" class="now_shipping_code form-control custom_form_control" name="now_shipping_code" disabled="disabled" />
                                    

                                    
                                    <input type="hidden" class="super_type form-control custom_form_control" name="super_type" value="FAMI" disabled="disabled" />                                    
                                    
                                </td>
                            </tr>                                    

                            @endif
                            <!-- 針對全家增設獨立表格結束 -->

                            <!-- 針對7-11增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get2' )   
                            <tr>
                                <th>超商店名：</th>
                                <td>
                                    <input type="text" class="super_name2" name="super_name2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled" />
                                    <span id="super_get_btn" onclick="open_select_store('UNIMART')" style="margin-bottom:10px" >選擇7-11門市</span><br>                                    
                                </td>
                            </tr>
                            <tr>
                                <th>超商地址：</th>
                                <td>
                                    <input type="text" class="super_addr2" name="super_addr2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSAddress']}}@endif" readonly disabled="disabled" />
                            
                                    <input type="hidden" class="super_no2" name="super_no2" value="@if(session()->has('UNIMART')){{session()->get('UNIMART')['CVSStoreID']}}@endif" disabled="disabled" />
                                
                                    <input type="hidden" value="" class="now_shipping_code" name="now_shipping_code" disabled="disabled" />
                                    
                                    <input type="hidden" class="super_type" name="super_type" value="UNIMART" disabled="disabled" />  
                                </td>
                            </tr>                            
                          
                            @endif
                            <!-- 針對7-11增設獨立表格結束 -->

                            <!-- 針對萊爾富增設獨立表格 -->
                            @if( $shipping_listv['shipping_code'] == 'super_get3' ) 
                            <tr>
                                <th>超商店名：</th>
                                <td>
                                    <input type="text" class="super_name2" name="super_name2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSStoreName']}}@endif" style="width:180px" readonly disabled="disabled"/>
                                    <span id="super_get_btn" onclick="open_select_store('HILIFE')" style="margin-bottom:10px">選擇萊爾富門市</span>                            
                                </td>
                            </tr>  
                            <tr>
                                <th>超商地址：</th>
                                <td>
                                    <input type="text" class="super_addr2" name="super_addr2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSAddress']}}@endif" readonly disabled="disabled"/>
                                    <input type="hidden" class="super_no2" name="super_no2" value="@if(session()->has('HILIFE')){{session()->get('HILIFE')['CVSStoreID']}}@endif" disabled="disabled"/>
                                    <input type="hidden" value="" class="now_shipping_code" name="now_shipping_code" disabled="disabled"/>
                                    <input type="hidden" class="super_type" name="super_type" value="HILIFE" disabled="disabled"/>                                      
                                </td>
                            </tr>                                                        
                        
                            @endif
                            <!-- 針對萊爾富增設獨立表格結束 -->
                                   
                            @if( $shipping_listv['shipping_code'] == 'super_get' || $shipping_listv['shipping_code'] == 'super_get2' || $shipping_listv['shipping_code'] == 'super_get3')
                            <tr>
                                <th><font color="red">*</font>收貨人：</th>
                                <td><input type="text" class="super_consignee" name="super_consignee" value="" style="width:150px" disabled="disabled" /></td>
                            </tr>
                            <tr>
                                <th><font color="red">*</font>手機：</th>
                                <td><input type="text" class="super_mobile" name="super_mobile" value="" style="width:150px" placeholder="格式:0912345678" disabled="disabled" /></td>
                            </tr>
                            <tr>
                                <th>電子郵件：</th>
                                <td><input type="text" class="super_email" name="super_email" value="" style="width:200px" disabled="disabled" />(訂單收信用)</td>
                            </tr>                                                    
                        
                        
                    
                            @else
                            <tr>
                                <th><font color="red">*</font>收貨人</th>
                                <td><input type="text" name="consignee" value="" class="" disabled="disabled"/>
                            </tr>
  
                            <tr class="odd">
                                <th width="80px"><font color="red">*</font>收貨地址</th>
                                <td><input type="text" name="address" value="" class=""/></td>
                            </tr>
                                
                            <tr>
                                <th>郵遞區號</th>
                                <td class="last"><input type="text" name="zipcode" value="" class=""/></td>
                            </tr>  

                            <tr>
                              <th><font color="red"></font>電子郵件</th>
                              <td class="last"><input name="email" type="text" value="" class=""/>(訂單收信用)
                              </td>
                            </tr>

                            <tr>
                              <th><font color="red">*</font>手機</th>
                              <td class="last"><input name="mobile" type="text" value="" class="" placeholder="格式:0912345678"/>
                              </td>
                            </tr>     
                            <tr>
                              <th>電話</th>
                              <td><input type="text" name="tel" value="" class=""/></td>
                            </tr>


                            <tr class="odd last">
                                <th>送貨時間</th>
                                
                                <td class="last">
                                    <select name="best_time">
                                        <option value="" >請選擇</option>
                                        <option value="13點前" >13點前</option>
                                        <option value="13~18點前" >13~18點前</option>
                                        <option value="不指定" >不指定</option>
                                    </select>
                                    
                                    <br>
                                    <span style="color:#ff4899">*(使用宅配寄送用戶可選擇)</span>
                                </td>
                            
                            </tr>
                            
                            <tr>
                                
                                <th>收貨備註</th>
                                <td class="last">
                                    <select name="sign_building">
                                        
                                        <option value="本人親收">
          
                                            本人親收
          
                                        </option>
                                
                                        <option value="管理員(警衛)代收" >管理員(警衛)代收</option>
                                
                                        <option value="親友代收" >親友代收</option>
                                    
                                    </select>
                                    
                                    <br>
                                    
                                    <span style="color:#ff4899">*(使用宅配寄送用戶可選擇)</span>
                                </td>
                            </tr>  
                            @endif                                                

                        </table>                        

                    </span>
                </div>
                @endforeach
            </div>
            <!-- 配送方式結束 --> 

            <div class="col-md-12 col-sm-12 col-xs-12" id='consigneeArea'>

            </div>                   
    </div>

</div>
@endsection

@section('selfjs')
<script type="text/javascript">
$(function(){
    /*----------------------------------------------------------------
     | 選取國家動態轉換州的內容
     |----------------------------------------------------------------
     |
     */
    $("#country").change(function(){
        
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
    $("#province").change(function(){
        
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
    $("#city").change(function(){
        
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
    



    /*----------------------------------------------------------------
     | 頁面載入時判斷是否要進行呈現表單
     |----------------------------------------------------------------
     |
     */
    if ($("input[name='shipping']:checked").val()) {
        
        $("input[name='shipping']").trigger('change');

    }    
});
</script>
@endsection