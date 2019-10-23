@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/msg_page.css')}}">
@endsection

@section('content_right')
<div class="box box-solid">

<div class="box box-default msg_box">
    
    <div class="box-header with-border">
        <h3 class="box-title msg_title">
        @if( $res === true)
            執行成功
        @else
            執行失敗
        @endif
        </h3>
    </div>

    <div class="box-body msg_des">
        <p>
        {{$msg}}
        </p>
    </div>
    
    <div class="box-footer">
        <a href="{{url('/')}}" class='btn btn-default'>回首頁</a>
    </div>

</div>

</div>
@endsection
