@extends('league_admin')

@section('selfcss')
<link href="{{url('/toastr-master/build/toastr.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{url('/css/league_module_category.css')}}">
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class='row custom_row'>
    

    <div id='recommend_category_box' class='col-md-12 col-sm-12 col-xs-12'>
	    
	    <div class='box box-primary'>
            
            <div class="box-header with-border">
              
              <h3 class="box-title">類別推薦設定</h3>

            </div>

	        <form role="form" action="{{url('/league_module_recommend_category_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">
            
                <div class='col-md-12 col-sm-6 col-xs-6'>
                    <div class="form-group" style='width:calc(33.3% - 15px)!important'>
                        自訂名稱:
                        <input type='text' class='form-control' name="cate_recommend_name" placeholder="請輸入類別推薦名稱" value='{{$cate_recommend_name}}'>
                    </div>
                </div>

            	@for($i = 0 ; $i<3 ; $i ++)
                @php
                    $tmpp = "p_cate".($i+1);
                    $tmpc = "c_cate".($i+1);
                @endphp
            	<div class='col-md-2 col-sm-6 col-xs-6'>
                    <div class="form-group">
                        <select id='p_cate{{$i}}' name='p_cate{{$i+1}}' class="form-control p_cate" child_select='c_cate{{$i+1}}' >
                            <option value='' >請選擇</option>
                                
                            @foreach($Categorys as $Categoryk => $Category )
                            <option value='{{$Category['cat_id']}}'
                                @if ($$tmpp == $Category['cat_id'] )
                                    selected
                                @endif
                            >{{$Category['cat_name']}}</option>
                            @endforeach
                        </select>     
                    </div>
            	</div>
            	<div class='col-md-2 col-sm-6 col-xs-6'>
            		<div class="form-group">

            			<select  id='c_cate{{$i+1}}' name='c_cate{{$i+1}}' class="form-control">
            				<option value='' >請選擇</option>
                            @if( isset($child_category[$i]) && count($child_category[$i]) > 0 )
                            @foreach($child_category[$i] as $child_categoryk => $child_categoryv )
                            <option value="{{$child_categoryv['cat_id']}}" 
                                 @if($$tmpc == $child_categoryv['cat_id'])
                                    selected
                                @endif
                            >{{$child_categoryv['cat_name']}}</option>
                            @endforeach
                            @endif
            			</select>
            		</div>
            	</div>
            	<div class='col-md-8 col-sm-12 col-xs-12 goods_input_div'>
                    @if( isset($cate_goods[$i]) && count($cate_goods[$i]) > 0)

                        @foreach( $cate_goods[$i] as $cate_goodk => $cate_good)
                        <div class="form-group">
                            <input type='text' class='form-control' name="goods{{$i+1}}[]" placeholder="請輸入商品編號，例:NO.570337" value='{{$cate_good}}'>
                        </div>
                        @endforeach
                    @else
                    <div class="form-group">
                        <input type='text' class='form-control' name="goods{{$i+1}}[]" placeholder="請輸入商品編號，例:NO.570337">
                    </div>
                    @endif 
                    <span type="button" class="btn btn-success add_input add_input{{$i+1}}" >+ 新增</span>  
                    <br><br>

            	</div>

                <div class='col-md-8 col-md-offset-4 col-sm-12 col-xs-12 goods_input_div'>
                    <div class="form-group">
                        <textarea class='form-control' name='cat_des{{$i+1}}' placeholder='請輸入類別描述' >{{$cate_recommend_des[$i]}}</textarea>
                    </div>                    
                </div>

                @endfor
                
                <input type='hidden' name='cate_recommend_id' value="{{$cate_recommend_id}}">

                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <input type='submit' value='確定' class='btn btn-primary'>
                </div>
            </div>
	    </div>

    </div>
</div>
@endsection

@section('selfjs')
<script src="{{url('/toastr-master/build/toastr.min.js')}}"></script>

<script id="hidden-template1" type="text/x-custom-template">
    <div class="form-group">
        <input type='text' class='form-control' name='goods1[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template2" type="text/x-custom-template">
    <div class="form-group">
        <input type='text' class='form-control' name='goods2[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template3" type="text/x-custom-template">
    <div class="form-group">
        <input type='text' class='form-control' name='goods3[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script type="text/javascript">
$(function(){

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "200",
      "hideDuration": "200",
      "timeOut": "1000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    } 

    $(".p_cate").change(function(){
        
        var child_select = $(this).attr('child_select');

        var request = $.ajax({
            url: "{{url('/backtool')}}",
            method: "POST",
            data: { "_token": "{{ csrf_token() }}", cat_id:$(this).val()},
            dataType: "json"
        });
 
        request.done(function( return_res ) {
            
            $("#"+child_select).empty();

            $("#"+child_select).append("<option value=''>請選擇</option>");

            $.each( return_res , function( resk , resv ){

                $("#"+child_select).append("<option value='"+resv['cat_id']+"'>"+resv['cat_name']+"</option>")    

            });
        });
 
        request.fail(function( jqXHR, textStatus ) {
            //console.log( "Request failed: " + textStatus );
        });

    })


    $(".add_input").click(function(){
        
        input_total = $(this).parent().children('.form-group').length
        
        if( input_total + 1 > 6){

            toastr.info('各類別推薦商品最多只能設定6個');
        }
        else{
            
            if( $(this).hasClass('add_input1') )
            {
                var appendhtml = $('#hidden-template1').html(); 
            }
            else if( $(this).hasClass('add_input2') )
            {
                var appendhtml = $('#hidden-template2').html();
            }
            else if( $(this).hasClass('add_input3') )
            {
                var appendhtml = $('#hidden-template3').html();
            }            
            
            $(this).parent().children(".form-group").last().after( appendhtml );

        }
    });
})
</script>
@endsection