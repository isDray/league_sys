@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{url('/css/monthpicker.css')}}">

@endsection
<!-- <link rel="stylesheet" href="{{url('/css/login.css')}}"> -->

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
                
                <label>查詢月份:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="datepicker" name='start' autocomplete="off" value="{{$start}}">
                </div>
            </div>
            <!-- /開始日期 -->           
            
            <!-- 獎金狀態 -->
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                
                <div class="form-group">
                    
                    <label>獎金狀態:</label>
                    
                    <select class="form-control" name='commission_status'>
                        <option value='0' @if($commission_status == 0) SELECTED @endif>全部</option>
                        <option value='1' @if($commission_status == 1) SELECTED @endif>未提領</option>
                        <option value='2' @if($commission_status == 2) SELECTED @endif>已提領</option>
                  </select>
                </div>            
            </div>
            <!-- /獎金狀態 -->
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <input type='submit' class='btn btn-primary'>
            </div>

        </div>
        </form>

    </div>   
    </div> 
    <!-- /搜尋條件 -->
    
    <!-- 清單呈現 -->
    <div class="col-xs-12 col-sm-12 col-xs-12">
        <div class="box box-primary">
            
            <div class="box-header">
                <h3 class="box-title">獎金清單</h3>

                <div class="box-tools">
                    
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    
                <tbody>
                    <tr>
                        <th>訂單編號</th>
                        <th>領取狀態</th>
                        <th>訂單金額</th>
                        <th>獎金</th>
                    </tr>
                    @foreach( $Orders as $Order )
                    <tr>
                        <td>{{ $Order['order_sn'] }}</td>
                        <td >@if( $Order['league_pay'] == 0 )<small class="label pull-left bg-green">未領取</small> @else <small class="label pull-left bg-gray">已領取</small> @endif</td>
                        <td>{{ ROUND($Order['total_fee']) }}</td>
                        <td>{{ $Order['commission'] }}</td>
                    </tr>
                    @endforeach
                </tbody>

                </table>

            </div>
        </div>
    </div>    
    <!-- 清單呈現結束 -->

    <!-- 報表呈現 -->
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">獎金成長曲線</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="commission_chart"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div> 

    <div class="col-md-6 col-sm-6 col-xs-12">
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">每日獎金圖</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="perday_commission"></canvas>
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
<script src="{{url('/js/jquery.mtz.monthpicker.js')}}"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
monthpicker.min.js
<script type="text/javascript">
$(function(){

// 時間選擇器
// $('.datepicker').datepicker({
//     autoclose: true,
//     language: 'zh-TW',
//     format:'yyyy-mm-dd',
// });

$('.datepicker').monthpicker({
    pattern:'yyyy-mm',
    monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
});



/*
|--------------------------------------------------------------------------
| 獎金成長曲線
|--------------------------------------------------------------------------
|
*/
var commission_chart = document.getElementById('commission_chart').getContext('2d');
var commission_grow_chart = new Chart(commission_chart, {
    type: 'line',
    
    data: {
        labels:{{ $DateX}} ,
        datasets:[{
            label:'獎金',
            data: {{$DayCommissions}} ,
            backgroundColor: 'rgb(255, 99, 132)',
            pointBackgroundColor:'rgb(188, 19, 55)',
            lineTension:0,
            
        }],
    },
    options: {
    scales: {
        yAxes: [{
            display: true,
            ticks: {
                suggestedMin: 0,
                beginAtZero: true   
            }
        }]
    }        
    }
});




/*
|--------------------------------------------------------------------------
| 本月訂單圖
|--------------------------------------------------------------------------
|
*/
var ctx = document.getElementById('perday_commission').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: {{ $DateX}} ,
        datasets: [{
            label: '獎金',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: @php echo str_replace( "&quot;", "'" ,$PerDayCommissions) @endphp,
        }],
                    
    },

    // Configuration options go here
    options: {}
});
})
</script>
@endsection