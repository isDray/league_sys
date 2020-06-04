@extends('league_admin')

@section('selfcss')
<link href="{{url('/toastr-master/build/toastr.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{url('/css/league_module_stack.css')}}">
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
              
              <h3 class="box-title">@if( empty($stackId))新增@else編輯@endif堆疊商品輪播</h3>

            </div>

	        <form role="form" action="{{url('/league_module_recommend_stack_edit_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">
                
                <div class='col-md-4 col-sm-12 col-xs-12'>
                        

                        <div class="form-group">
                            堆疊標題:<input type='text' class='form-control' name="title" placeholder="請輸入堆疊輪播標題，EX:飛機杯-5月份新貨" value="@if($act == 'edit'){{old('title', $stack_datas['title'])}}@else{{old('title')}}@endif">
                        </div>

                        <div class="form-group">
                            商品編號:            
                            <input type='text' class='form-control' name="goods_sn[]" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn.0', $stack_datas['goods'][0])}}@else{{old('goods_sn.0')}}@endif">
                        </div>
                        <div class="form-group">
                            <input type='text' class='form-control' name="goods_sn[]" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn.1', $stack_datas['goods'][1])}}@else{{old('goods_sn.1')}}@endif">
                        </div>
                        <div class="form-group">
                            <input type='text' class='form-control' name="goods_sn[]" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn.2', $stack_datas['goods'][2])}}@else{{old('goods_sn.2')}}@endif">
                        </div>
                        <div class="form-group">
                            <input type='text' class='form-control' name="goods_sn[]" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn.3', $stack_datas['goods'][3])}}@else{{old('goods_sn.3')}}@endif">
                        </div>
                        <div class="form-group">
                            <input type='text' class='form-control' name="goods_sn[]" placeholder="請輸入商品編號，例:NO.570337" value="@if($act == 'edit'){{old('goods_sn.4', $stack_datas['goods'][4])}}@else{{old('goods_sn.4')}}@endif">
                        </div>                                                                                                
                </div>
            
                <div class='col-md-12 col-sm-12 col-xs-12'>

                    @if( !empty($stack_id) )
                    <input type='hidden' value='{{$stack_id}}' name='stack_id'>
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