@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- <link rel="stylesheet" href="{{url('/css/login.css')}}"> -->
@endsection

@section('content')
<div class='row custom_row'>
    <div class='col-md-12 col-sm-12 col-xs-12'>
    
    @if( count($NoteMsgs) > 0) 
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> 注意!</h4>
        @foreach($NoteMsgs as $NoteMsg)
        <p>{{$NoteMsg}}</p>
        @endforeach
    </div>    
    @endif
    <!-- 搜尋條件 -->
    <div class="box box-primary">
        
        <div class="box-header">
            <h3 class="box-title">報表條件</h3>
        </div>
        
        <form action="" method="POST">
        {{ csrf_field() }}
        <div class="box-body">
            
            <!-- 開始日期 -->
            <div class="form-group col-md-3 col-sm-4 col-xs-2">
                
                <label>開始日期:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="datepicker" name='start' autocomplete="off">
                </div>
            </div>
            <!-- /開始日期 -->

            <!-- 開始日期 -->
            <div class="form-group col-md-3 col-sm-4 col-xs-2">
                
                <label>結束日期:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="datepicker" name='end' autocomplete="off">
                </div>
            </div>
            <!-- /開始日期 -->            
            
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <input type='submit' class='btn btn-primary'>
            </div>

        </div>
        </form>

    </div>   
    </div> 
    <!-- /搜尋條件 -->
 
    <!-- 報表呈現 -->
    <div class="col-md-6 col-sm-6 col-xs-12">
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">訂單</h3>

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
    <!-- /報表呈現 -->

</div>
@endsection

@section('selfjs')

<script src="{{url('/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{url('/AdminLTE/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.zh-TW.min.js')}}"></script>
<script type="text/javascript">
$(function(){

// 時間選擇器
$('.datepicker').datepicker({
    autoclose: true,
    language: 'zh-TW',
    format:'yyyy-mm-dd'
})


/*
|--------------------------------------------------------------------------
| 時間內訂單
|--------------------------------------------------------------------------
|
*/
var ctx = document.getElementById('month_order').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {

        labels: {{ $PerDayLabels}} ,
        datasets: [{
            label: '訂單數',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(54, 99, 132)',
            data: {{ $PerDayOrderNums }},

        }],
                    
    },

    // Configuration options go here
    options: {
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    suggestedMin: 0,
                    // OR //
                    beginAtZero: true   
                }
            }]
        }
    }
});    


})
</script>
@endsection