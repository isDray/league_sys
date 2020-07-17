@extends('league_admin')

@section('selfcss')
<link href="{{url('/toastr-master/build/toastr.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{url('/css/league_module_stack.css')}}">

<style type="text/css">
.color_label{
    width: 20px;
    height: 20px;
    border: 1px solid #4c4c4c;
    margin-bottom: 0px;
    border-radius: 4px;
}
</style>
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
    
    <div id='recommend_stack_box' class='col-md-12 col-sm-12 col-xs-12'>
	    
	    <div class='box box-primary'>
            
            <div class="box-header with-border">
              
              <h3 class="box-title">@if( empty($id))新增@else編輯@endif推薦卡片</h3>

            </div>

	        <form role="form" action="{{url('/league_module_recommend_custom_ad_edit_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">
                
                <div class='col-md-4 col-sm-12 col-xs-12'>
                        

                        <div class="form-group">
                            標題:<input type='text' class='form-control' name="title" placeholder="請輸入標題，EX:網友激推 - 妳一定要試試看" value="@if($act == 'edit'){{old('title', $datas['title'])}}@else{{old('title')}}@endif">
                        </div>

                        <div class="form-group">
                            簡述:            
                            <input type='text' class='form-control' name="descript" placeholder="請輸入簡述,EX: 酥麻高潮神器，滾珠按摩棒，漸增可調震度直達酥麻高潮" value="@if($act == 'edit'){{old('descript', $datas['descript'])}}@else{{old('descript')}}@endif">
                        </div>

                        <div class="form-group">
                            連結:            
                            <input type='text' class='form-control' name="link" placeholder="請輸入要導向之連結" value="@if($act == 'edit'){{old('link', $datas['link'])}}@else{{old('link')}}@endif">
                        </div> 

                        <div class="form-group">
                            代表商品編號:            
                            <input type='text' class='form-control' name="goods_sn" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn', $datas['goods_sn'])}}@else{{old('goods_sn')}}@endif">
                        </div>

                        <div class="form-group">
                            區塊背景色:<br>
                            
                            <label class="color_label" for="bgcolor1" style="background-color:white;"></label>
                            <input type="radio" name="bgcolor" id="bgcolor1" value="1" @if($act == 'edit')@if(old('bgcolor', $datas['background-color']) != null) @if(old('bgcolor')==1 || $datas['background-color'] ==1) checked @endif @endif @else @endif>
                            
                            <label class="color_label" for="bgcolor2" style="background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);"></label>
                            <input type="radio" name="bgcolor" id="bgcolor2" value="2" @if($act == 'edit')@if(old('bgcolor', $datas['background-color']) != null) @if(old('bgcolor')==2 || $datas['background-color'] ==2) checked @endif @endif @else @endif>    

                            <label class="color_label" for="bgcolor3" style="background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);"></label>
                            <input type="radio" name="bgcolor" id="bgcolor3" value="3" @if($act=='edit')@if(old('bgcolor', $datas['background-color']) != null)  @if(old('bgcolor')==3 || $datas['background-color'] ==3) checked @endif @endif @else @endif>    

                            <label class="color_label" for="bgcolor4" style="background-image: linear-gradient(-60deg, #ff5858 0%, #f09819 100%);"></label>
                            <input type="radio" name="bgcolor" id="bgcolor4" value="4" @if($act == 'edit')@if(old('bgcolor', $datas['background-color']) != null) @if(old('bgcolor')==4 || $datas['background-color'] ==4) checked @endif @endif @else @endif>    

                            <label class="color_label" for="bgcolor5" style="background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);"></label>
                            <input type="radio" name="bgcolor" id="bgcolor5" value="5" @if($act == 'edit')@if(old('bgcolor', $datas['background-color']) != null) @if(old('bgcolor')==5 || $datas['background-color'] ==5) checked @endif @endif @else @endif>    

                            <label class="color_label" for="bgcolor6" style="background-image: linear-gradient(-225deg, #D4FFEC 0%, #57F2CC 48%, #4596FB 100%);"></label>
                            <input type="radio" name="bgcolor" id="bgcolor6" value="6" @if($act == 'edit')@if(old('bgcolor', $datas['background-color']) != null) @if(old('bgcolor')==6 || $datas['background-color'] ==6) checked @endif @endif @else @endif>                                                                                                                                        
                            
                        </div>

                        <div class="form-group">
                            動畫類型:<br>
                            
                            <label for="animate1">漣漪</label>
                            <input type="radio" name="animate" id="animate1" value="1" @if($act == 'edit')@if(old('animate', $datas['animate']) != null) @if(old('animate')==1 || $datas['animate'] ==1) checked @endif @endif @else @endif>
                            <label for="animate2">波紋</label>
                            <input type="radio" name="animate" id="animate2" value="2" @if($act == 'edit')@if(old('animate', $datas['animate']) != null) @if(old('animate')==2 || $datas['animate'] ==2) checked @endif @endif @else @endif>
                                                                                                                                                                                             
                        </div>        

                        <div class="form-group">
                            圖形位置:<br>
                            
                            <label for="rorl1">左</label>
                            <input type="radio" name="rorl" id="rorl1" value="1" @if($act == 'edit')@if(old('rorl', $datas['rorl']) != null) @if(old('rorl')==1 || $datas['rorl'] ==1) checked @endif @endif @else @endif>
                            
                            <label for="rorl2">右</label>
                            <input type="radio" name="rorl" id="rorl2" value="2" @if($act == 'edit')@if(old('rorl', $datas['rorl']) != null) @if(old('rorl')==2 || $datas['rorl'] ==2) checked @endif @endif @else @endif>                                                                                                                                                                 
                        </div>                                         
                                                                                               
                </div>
            
                <div class='col-md-12 col-sm-12 col-xs-12'>

                    @if( !empty($id) )
                    <input type='hidden' value='{{$id}}' name='id'>
                    @endif
                    <input type='submit' value='確定' class='btn btn-primary'>
                </div>
            </div>

            </form>

	    </div>

    </div>
</div>
@endsection

@section('selfjs')
<script type="text/javascript">

</script>
@endsection