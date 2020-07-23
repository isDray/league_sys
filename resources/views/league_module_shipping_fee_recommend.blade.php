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
              
              <h3 class="box-title">{{$PageTitle}}</h3>

            </div>

	        <form role="form" action="{{url('/league_module_recommend_shipping_free_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">

                @for( $i=1;$i<11;$i++ )
                @php
                    $tmpname = "goods{$i}";
                @endphp

                <div class='col-md-12 col-sm-6 col-xs-6 recommend_box'>
                    差額 {{$i}}00 推薦
                    @if( count( $$tmpname ) > 0)
                    @foreach( $$tmpname as $tmpnamek => $tmpnamev )
                    <div class="form-group" style='width:calc(33.3% - 15px)!important'>
        
                        <input type='text' class='form-control' name="goods{{$i}}[]" placeholder="請輸入商品編號，例:NO.570337" value='{{$tmpnamev}}'>
            
                    </div>

                     @endforeach
                    @else
                    <div class="form-group" style='width:calc(33.3% - 15px)!important'>

                        <input type='text' class='form-control' name="goods{{$i}}[]" placeholder="請輸入商品編號，例:NO.570337" value=''>

                    </div>   
                    @endif
                    <span type="button" class="btn btn-success add_input add_input{{$i}}" >+ 新增</span>  
                    <br>                    
                </div>
                <br><br>
                @endfor

    
                
                <input type='hidden' name='cate_recommend_id' value="">

                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <input type='submit' value='確定' class='btn btn-primary'>
                </div>
            </div>
	    </div>

    </div>
</div>
<style type="text/css">
.recommend_box{
    margin-bottom: 20px;
}
</style>
@endsection

@section('selfjs')
<script src="{{url('/toastr-master/build/toastr.min.js')}}"></script>

<script id="hidden-template1" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods1[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template2" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods2[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template3" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods3[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template4" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods4[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template5" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods5[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template6" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods6[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template7" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods7[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template8" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods8[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template9" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods9[]' placeholder="請輸入商品編號，例:NO.570337">
    </div>
</script>
<script id="hidden-template10" type="text/x-custom-template">
    <div class="form-group" style="width:calc(33.3% - 15px)!important">
        <input type='text' class='form-control' name='goods10[]' placeholder="請輸入商品編號，例:NO.570337">
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
        
        if( input_total + 1 > 4)
        {

            toastr.info('各類別推薦商品最多只能設定4個');
        }
        else
        {
            
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
            else if( $(this).hasClass('add_input4') )
            {
                var appendhtml = $('#hidden-template4').html();
            } 
            else if( $(this).hasClass('add_input5') )
            {
                var appendhtml = $('#hidden-template5').html();
            }             
            else if( $(this).hasClass('add_input6') )
            {
                var appendhtml = $('#hidden-template6').html();
            } 
            else if( $(this).hasClass('add_input7') )
            {
                var appendhtml = $('#hidden-template7').html();
            } 
            else if( $(this).hasClass('add_input8') )
            {
                var appendhtml = $('#hidden-template8').html();
            } 
            else if( $(this).hasClass('add_input9') )
            {
                var appendhtml = $('#hidden-template9').html();
            } 
            else if( $(this).hasClass('add_input10') )
            {
                var appendhtml = $('#hidden-template10').html();
            }                                                             

            $(this).parent().children(".form-group").last().after( appendhtml );

        }
    });
})
</script>
@endsection