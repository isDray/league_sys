@extends("web1")

@section('selfcss')

<link rel="stylesheet" href="{{url('/css/member_default.css')}}">

@endsection


@section('content_left')
    
    @includeIf( 'block_member_menu' , ['now_function'=>$now_function] )

@endsection

@section('content_right')



<div class='row custom_row'>

<div class='box box-solid member_menu'>
@includeIf( 'block_member_menu' , ['now_function'=>$now_function] )
</div>

<div class="box box-solid">

<div class="box box-default member_default">
    
    <div class="box-header with-border">
        <h3 class="box-title member_default_title">
        我的訂單
        </h3>   
    </div>

    <div class="box-body">
    
        <table class="table table-bordered">
            
            <tbody>
            	
            	<tr>
                    <th>訂單編號</th>
                    <th>下單時間</th>
                    <th>總金額</th>
                    <th>狀態</th>
                </tr>
                
                @foreach( $orders as $orderk => $order )
                <tr>
                    
                    <td>{{ $order['order_sn'] }}</td>
                    <td>{{ date('y-m-d H:i:s', $order['add_time']+28800 )}}</td>
                    <td>
                        ${{ intval( $order['total_fee'] ) }}
                    </td>
                    <td> {{$order['os']}} / {{$order['ss']}} / {{$order['ps']}}</td>
                </tr>                
                @endforeach

              </tbody>

          </table>

    </div>
    
    <div class="box-footer">
    {!!$pages!!}
    </div>

</div>

</div>    
</div>

@endsection
@section('selfjs')

@endsection