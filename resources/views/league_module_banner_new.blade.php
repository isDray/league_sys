@extends('league_admin')

@section('selfcss')
@endsection

@section('content')
<div class='row custom_row'>
    
    <div class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            
            <div class="box-header with-border">
                <h3 class="box-title">新增banner表單</h3>
            </div>
            
            <!-- form start -->
            <form role="form" action="{{url('/league_module_banner_new_do')}}" method="post"  enctype="multipart/form-data" >

                <div class="box-body">

                    {{ csrf_field() }}
                    <div class="form-group">                        
                        <label for="banner">banner圖檔(1280*720)</label>
                        <input type="file" id="banner" name="banner" onchange="readURL(this);">
                        @if ($errors->has('banner'))
                        <label id="banner-error" class="form_invalid" for="banner">{{ $errors->first('banner') }}</label>
                        @endif                        
                    </div>

                    <div class="form-group">                        
                        <label for="sort">排序</label>
                        <input class="form-control custom_form_control" type="number" name='sort' id='sort' min="0" value='0' />
                    </div>                    

                </div>
              

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">新增</button>
                </div>

            </form>

        </div>

	  </div>

</div>
@endsection

@section('selfjs')
<script>
function readURL(input) {

    if (input.files && input.files[0]) {
        
        var reader = new FileReader();

        reader.onload = function (e) {

            $("#banner").after( "<img src='' id='blah'>" );

            $('#blah')
            .attr('src', e.target.result)
            .width(640)
            .height(360);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection