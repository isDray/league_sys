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
            <h3 class="box-title">搜尋器</h3>
        </div>
        
        <form action="" method="POST">
        {{ csrf_field() }}
        <div class="box-body">
            
            <!-- 開始日期 -->
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                
                <label>開始日期:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="datepicker" name='start' autocomplete="off" value="{{$start}}">
                </div>
            </div>
            <!-- /開始日期 -->

            <!-- 開始日期 -->
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                
                <label>結束日期:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="datepicker" name='end' autocomplete="off" value="{{$end}}">
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
              <h3 class="box-title">每日訂單圖</h3>

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
              <h3 class="box-title">訂單成長曲線</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="order_grow"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div> 


    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">本月訂單完成比例</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="done_percent"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div>    

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">本月重點銷售類別</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="catradar_chart"></canvas>
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
            data: {{ $PerDayOrderNums }},

        },
        {
            label: '完成訂單數',
            backgroundColor: 'rgb(51, 122, 183)',
            data: {{ $PerDayDoneOrderNums }},

        }],
                    
    },

    options: {
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    stepSize:1,
                    suggestedMin: 0,
                    // OR //
                    beginAtZero: true,

                }
            }]
        }
    }
});    




/*
|--------------------------------------------------------------------------
| 訂單成長曲線圖
|--------------------------------------------------------------------------
|
*/
var order_grow = document.getElementById('order_grow').getContext('2d');
var order_grow_chart = new Chart(order_grow, {
    type: 'line',
    
    data: {
        labels:{{ $PerDayLabels}} ,
        datasets:[
        {
            label:'所有訂單',
            data: {{$OrderGrow}} ,
            backgroundColor: 'rgb(255, 99, 132)',
            pointBackgroundColor:'rgb(188, 19, 55)',
            lineTension:0,
            fill:false,
            borderWidth:4,
            borderColor: 'rgb(255, 99, 132)',
            
        },
        {
            label:'完成訂單',
            data: {{$OrderDoneGrow}} ,
            backgroundColor: 'rgb(51, 122, 183)',
            pointBackgroundColor:'rgb(40, 94, 141)',
            lineTension:0,
            
        }],
    },
    options: {
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    stepSize:1,
                    suggestedMin: 0,
                    // OR //
                    beginAtZero: true,

                }
            }]
        }        
    }
});




/*
|--------------------------------------------------------------------------
| 完成訂單比
|--------------------------------------------------------------------------
|
*/
var done_percent = document.getElementById('done_percent').getContext('2d');
var doneChart = new Chart(done_percent, {
    
    type: 'pie',

    data: {
        
        datasets: [{
            data: {{$PercnetStatus}},
            backgroundColor:['rgb(255, 99, 132)' , 'rgb(51, 122, 183)'],
        }],
        
        labels: [
            '未完成訂單',
            '已完成訂單',
        ]
    },

    options: {
        cutoutPercentage:0,
        
        tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
                
                label: function(tooltipItem, data) {

                    var statuspercent = ( data.datasets[0].data[tooltipItem.index] / (data.datasets[0].data[0] + data.datasets[0].data[1]) ) *100 ;
                    var allData = data.datasets[tooltipItem.datasetIndex].data;
                    var tooltipLabel = data.labels[tooltipItem.index];
                    var tooltipData = allData[tooltipItem.index];
                    return tooltipLabel + ": " + tooltipData +'筆 , '+statuspercent.toFixed(2)+ "%";
                }
            }
        }
    }
});




/*
|--------------------------------------------------------------------------
| 本月重點銷售類別
|--------------------------------------------------------------------------
|
*/
var catradar_chart = document.getElementById('catradar_chart').getContext('2d');
var catradar_main_chart = new Chart(catradar_chart, {
    type: 'radar',
    data: {
        labels: @php echo str_replace( "&quot;", "'" ,$RadarCatNames) @endphp,
        datasets: [{
            label:'銷售分類',
            data:@php echo str_replace( "&quot;", "'" ,$RadarCatNums) @endphp,
            backgroundColor:'rgb(51, 122, 183,0.2)',
            borderColor:'rgb(51, 122, 183)',
            fill:true,
        }]
    },
    options: {
        scale: {
            ticks: {
                min:0,
                stepSize:1,
                beginAtZero: true,
            }
        },
        tooltips: {
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                }
            }
        }        
    },

});

})
</script>
@endsection