@extends("web1")

@section('selfcss')

<link rel="stylesheet" href="{{url('/css/member_default.css')}}">

@endsection


@section('content_left')
    
    @includeIf( 'block_member_menu' , ['now_function'=>$now_function] )

@endsection

@section('content_right')



<div class='row custom_row'>

<div class='member_menu box box-solid'>
@includeIf( 'block_member_menu' , ['now_function'=>$now_function] )
</div>

<div class="box box-solid">

<div class="box box-default  member_default">
    
    <div class="box-header with-border">
        <h3 class="box-title member_default_title">
        {{ $page_title }}
        </h3>   
    </div>

    <div class="box-body">
        
        <p style='font-size:16px;font-weight:900;'> 您好 , {{$member['name']}} <small style='color:#a7a7a7;'>您最近一次登入時間為:{{$member['login_time']}}</small> </p>

        <p style='font-weight:900;'>您的帳戶:</p>

        <p>
            <i class='fa fa-fw fa-file-text-o'></i>最近30天內的訂單共{{$order_sum}}筆
        </p>

    </div>
    
    <div class="box-footer">

    </div>

</div>

</div>    
</div>

@endsection
@section('selfjs')

@endsection