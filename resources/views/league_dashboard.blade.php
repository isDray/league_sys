@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@section('content')
<div class='row custom_row'>
    
    <div class='col-md-12 col-sm-12 col-xs-12'>
    
        
        <!-- 本月訂單 -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            
            <div class="info-box">
                <span class="info-box-icon bg-light-blue"><i class="fa fa-fw fa-cart-arrow-down"></i></span>

                <div class="info-box-content">
                    
                    <span class="info-box-text">本月訂單數</span>
                    <span class="info-box-number">{{$OrderNum}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

        </div>
        <!-- /本月訂單 -->

        <!-- 本月完成訂單 -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            
            <div class="info-box">
                <span class="info-box-icon bg-light-blue"><i class="fa fa-fw fa-star-o"></i></span>

                <div class="info-box-content">
                    
                    <span class="info-box-text">本月完成訂單</span>
                    <span class="info-box-number">{{$DoneOrders}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

        </div>
        <!-- /本月完成訂單 -->  

        <!-- 本月完成訂單 -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            
            <div class="info-box">
                <span class="info-box-icon bg-light-blue"><i class="fa fa-fw fa-dollar"></i></span>

                <div class="info-box-content">
                    
                    <span class="info-box-text">累積獎金</span>
                    <span class="info-box-number">{{$Acumulation}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

        </div>
        <!-- /本月完成訂單 -->              

    </div>

    <div class='col-md-12 col-sm-12 col-xs-12'>
 
    	<div class="col-md-6 col-sm-6 col-xs-12">
    	    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">本月訂單</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="month_order"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
        </div>

    	<div class="col-md-6 col-sm-6 col-xs-12">
    	    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Donut Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChart" style="height: 393px; width: 787px;" height="393" width="787"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
        </div>

    </div>

</div>
@endsection

@section('selfjs')

<script type="text/javascript">
$(function(){
/*
|--------------------------------------------------------------------------
| 本月訂單圖
|--------------------------------------------------------------------------
|
*/
var ctx = document.getElementById('month_order').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: {{ $MonthDays}} ,
        datasets: [{
            label: '下單數',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: {{ $MonthDayOrders }},
        },{
            label: '完成訂單數',
            backgroundColor: 'rgb(255, 199, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: {{ $MonthDayOrders2 }},
        }],
                    
    },

    // Configuration options go here
    options: {}
});    
})

</script>
@endsection