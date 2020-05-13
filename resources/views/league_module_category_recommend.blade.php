@extends('league_admin')

@section('selfcss')
<!-- <link rel="stylesheet" href=""> -->
@endsection

@section('content')
<div class='row custom_row'>
    

    <div id='recommend_category_box' class='col-md-12 col-sm-12 col-xs-12'>
	    
	    <div class='box box-primary'>
            
            <div class="box-header with-border">
              
              <h3 class="box-title">類別推薦設定</h3>

            </div>

	        <form role="form" action="{{url('/league_module_recommend_hot_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">
            	@for($i = 0 ; $i<3 ; $i ++)

            	<div class='col-md-2 col-sm-6 col-xs-6'>
                    <div class="form-group">
                        <select id='p_cate{{$i}}' name='p_cate{{$i}}' class="form-control p_cate" child_select='c_cate{{$i}}' >
                            <option value='0' >請選擇</option>
                                
                            @foreach($Categorys as $Categoryk => $Category )
                            <option value='{{$Category['cat_id']}}'>{{$Category['cat_name']}}</option>
                            @endforeach
                        </select>     
                    </div>
            	</div>
            	<div class='col-md-2 col-sm-6 col-xs-6'>
            		<div class="form-group">
            			<select  id='c_cate{{$i}}' class="form-control">
            				<option value='0' >請選擇</option>
            			</select>
            		</div>
            	</div>
            	<div class='col-md-8 col-sm-12 col-xs-12'>
                    <div class="form-group">
                    	<textarea class="form-control"></textarea>
                    </div>
            	</div>
                @endfor
            </div>
	    </div>

    </div>
</div>
@endsection

@section('selfjs')
<script type="text/javascript">
$(function(){
    
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

            $("#"+child_select).append("<option value='0'>請選擇</option>");

            $.each( return_res , function( resk , resv ){

                $("#"+child_select).append("<option value='"+resv['cat_id']+"'>"+resv['cat_name']+"</option>")    

            });
        });
 
        request.fail(function( jqXHR, textStatus ) {
            //console.log( "Request failed: " + textStatus );
        });

    })
})
</script>
@endsection