@php
use App\Cus_lib\Lib_block;

$mytt = Lib_block::banner();
@endphp
<div class='block_banner'>
    
    <!-- /.box-header -->
    <div class="box-body">
        
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
            </ol>
            
            <div class="carousel-inner">
                
                <div class="item active">
                    <img src="http://placehold.it/900x500/39CCCC/ffffff&amp;text=I+Love+Bootstrap" alt="First slide">

                    <div class="carousel-caption">
                      {{$mytt}}
                    </div>
                </div>


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