@extends('league_admin')

@section('selfcss')
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

  
            <form role="form" action="{{url('/league_webset_act')}}" method="post" >
            
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
@endsection