@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$recommends = Lib_block::get_recommend('best');

@endphp
<div class="box box-solid">
    
    <div class="box-header with-border">

        <h3 class="box-title recommend_title ">推薦商品</h3>

    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        @foreach( $recommends as $recommendk => $recommend)
        <a href="https://***REMOVED***.com/***REMOVED***/goods.php?id={{$recommend['goods_id']}}">
        <div class='col-md-3 col-sm-4 col-xs-6'>

            <div class="thumbnail">
                
                <img src="https://***REMOVED***.com/***REMOVED***/{{$recommend['goods_thumb']}}">
                
                <div class="caption">
                    <h4 class="goods_title">{{ $recommend['goods_name'] }}</h4>
                    <p>...</p>
                    <p><span class="btn btn-primary" role="button">立即購買</span></p>
                </div>
            </div>                
            
        </div>
        </a>
        @endforeach

    </div>
    <!-- /.box-body -->

</div> 