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
    
    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">網站設定表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

  
            <form role="form" action="{{url('/league_webset_act')}}" method="post" enctype="multipart/form-data">
            
            {{ csrf_field() }}
            <div class="box-body ">
                
                <div class="form-group">
                    <label for="webname">網站名稱</label>
                    <input type="text" class="form-control" id="webname" name="webname" placeholder="請填入網站名稱" value="{{$WebData['store_name']}}">
                    @if ($errors->has('webname'))
                    <label id="webname-error" class="form_invalid" for="webname">{{ $errors->first('webname') }}</label>
                    @endif                  
                </div>

                <div class="form-group">
                    <label for="webback">網站背景色</label>
                    <input type="color" class="form-control" id="webback" name="webback" placeholder="請選擇網站背景色" value="{{$WebData['back_color']}}">
                    @if ($errors->has('webback'))
                    <label id="webback-error" class="form_invalid" for="webback">{{ $errors->first('webback') }}</label>
                    @endif                     
                </div>

                <div class="form-group">
                    <label for="webback">網站配色</label>
                    
                    
                    <div class="checkbox colorsetbox col-md-12 col-sm-12 col-xs-12">
                        <div class='col-md-2 col-sm-4 col-xs-2'>
                        <input type="radio" id="color_1" name="colorset" value='1' @if($WebData['colorset']==1) checked @endif>
                        <label for="color_1" class="color_radio">
                            版型1
                        </label>
                        </div>

                        <div class='col-md-2 col-sm-4 col-xs-2'>
                        <input type="radio" id="color_2" name="colorset" value='2' @if($WebData['colorset']==2) checked @endif>
                        <label for="color_2" class="color_radio">
                            版型2
                        </label>
                        </div>
                   
                    </div>
                </div> 

                <div class="form-group">                        
                    <label for="logo">logo圖檔( 建議為 60 * 180 )</label>
                    <input type="file" id="logo" name="logo" onchange="readURL(this);">
                    @if ($errors->has('logo'))
                    <label id="logo-error" class="form_invalid" for="logo">{{ $errors->first('logo') }}</label>
                    @endif  
                    @if( !empty($WebData['logo']) )
                    <img src="{{url('/league_logo/'.$WebData['logo'])}}" id='blah' style='width:180px;max-width:100%'>    
                    @endif
                </div>                               

            </div>
            
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">確定</button>
            </div>

            </form>
        </div>

    </div>

</div>
@endsection

@section('selfjs')
<script>
function readURL(input) { 

    $("#blah").remove();

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {

            $("#logo").after( "<img src='' id='blah'>" );

            $('#blah')
            .attr('src', e.target.result)
       
            .height(60);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection