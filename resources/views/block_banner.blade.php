@php
use App\Cus_lib\Lib_block;
use Illuminate\Http\Request;

$bannsers = Lib_block::banner();

@endphp
<div class='block_banner box box-solid'>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            
            <ol class="carousel-indicators">
                @foreach( $bannsers as $bannserk => $bannser)
                <li data-target="#carousel-example-generic" data-slide-to="{$bannserk}}" class="@if( $bannserk == 0) active @endif"></li>              
                @endforeach                
            </ol>
            
            <div class="carousel-inner">
                
                @foreach( $bannsers as $bannserk => $bannser)
                <div class="item @if( $bannserk == 0) active @endif">
                    <img src="{{url('/banner/'.$bannser['user_id'].'/'.$bannser['banner'])}}" alt="">

                    <div class="carousel-caption">
                    
                    </div>
                </div>                
                @endforeach

            </div>
            
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- 輪播banner -->          