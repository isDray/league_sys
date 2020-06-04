@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/league_check_order.css')}}">
@endsection

@if( isset( $page_header ) )
    @section('page_header'){{$page_header}}@endsection
@endif

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @if( is_array($LeftBlock) )
            @includeIf('block_'. $LeftBlock[0],['id'=>$LeftBlock[1]])
        @else
            @includeIf('block_'. $LeftBlock)
        @endif

    @endforeach

@endsection

@section('content_right')
{!!$Breadcrum!!}
<div class="box box-solid">
    
    <div class="box-header with-border">
        <i class="fa fa-fw fa-search"></i>
        <h1 class="box-title">訂單查詢</h1>
    </div>
    

    <div class="box-body" id="cart_content">
        <ul>請輸入您在本站訂購商品的訂單編號:
            <li>可以清楚查到您訂購商品的明細。</li>
            <li>可以查到該訂單的目前狀態，商品如以出貨則顯示出貨日期。</li>
            <li>不會顯示您個人的資料，只對訂單狀態及商品資料作回應。</li>
        </ul>
        
        <div id='check_formbox'>

            <form action="{{url('/check_order_act')}}" method="POST" id="check_order_form">
                {{ csrf_field() }}
                <input type='text' class='form-control' name='order_sn' id="order_sn"> 
                <button class='btn colorbtn form-control'>查詢</button>
            </form>

        </div>

    </div>

</div>

<div class="box box-solid">
    <div id='check_order_res'>
    </div>
</div>
@endsection

@section('selfjs')
<script type="text/javascript">

    $("#check_order_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();
        
        /* get the action attribute from the <form action=""> element */
        var form = $( this ),
            url = form.attr( 'action' );
        
        var order_sn = $("#order_sn").val();

        var request = $.ajax({
            url: url,
            method: "POST",
            data: { "_token": "{{ csrf_token() }}", 
                    "order_sn" : order_sn,
            },
            dataType: "json"
        });
 
        request.done(function( backdata ) {

            $("#check_order_res").empty();

            $("#check_order_res").append(backdata);
            
        });
 
        request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    });

</script>
@endsection