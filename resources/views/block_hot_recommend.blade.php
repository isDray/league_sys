@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$hots = Lib_block::get_recommend('hot');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <i class="fa fa-text-width"></i>

        <h3 class="box-title">熱銷商品</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $hots as $hotk => $hot)
        <div class='col-md-3 col-sm-4 col-xs-6'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$hot['goods_thumb']}}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $hot['goods_name'] }}</h4>
                    <p>...</p>
                    <p><a href="#" class="btn btn-primary" role="button">立即購買</a></p>
                </div>
            </div>                
            
        </div>
        @endforeach

    </div>
    <!-- /.box-body -->

</div>        