@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_payed.css')}}">
@endsection

@section('content_right')
<div class="box box-solid">
    
    <div class="box-header with-border">
        <!-- <i class="fa fa-fw fa-shopping-cart"></i> -->
        <h3 class="box-title payed_title">@if( $res == true) 付款成功 @else 付款失敗 @endif</h3>
    </div>
    
    <div class="box-body" id="payed_content">
        @if( $res == true)
        <p> 付款已完成 , 訂單編號: {{$order_sn}} , 商品已進入出貨流程 , 將盡快將商品送達指定地址 , 如有任何問題請洽: (04)874-0413 , 將有專人為您服務 , 謝謝。 </p>
        @else
        <p> 交易過程出現錯誤 , 請稍後再嘗試下單付款 , 如有任何問題請洽: (04)874-0413 , 將有專人為您服務 , 謝謝。</p>
        @endif
    </div>

</div>
@endsection

@section('selfjs')

@endsection