@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/league_message.css')}}">
@endsection

@section('content')
<div class='row custom_row'>
    <div class='col-md-6 col-md-offset-3 col-sm-12 col-xs-12'>
        
        <div class="box @if( $MessageType ) box-success @else box-danger 執行失敗 @endif  box-solid">
            
            <div class="box-header with-border">
                
                <h3 class="box-title">@if( $MessageType ) 執行成功 @else 執行失敗 @endif</h3>
                

            <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body league_msg_box">
            	<p>
                {{ $MessageText }}
            	</p>

            	<ul>請選擇要前往之頁面 , 如不選擇則將在{{ $MessageSec }}秒後自動轉跳頁面 。<br>
                @foreach( $MessageList as $MessageListk => $MessageListv )
                    <a href="{{url('/') }}{{$MessageListv['operate_path']}} ">
                    <li>{{ $MessageListv['operate_text'] }}</li>
                    </a>
                @endforeach
            	</ul>
            </div>
            <!-- /.box-body -->
        
        </div>

    </div>
</div>
@endsection

@section('selfjs')
<script type="text/javascript">
$(function(){
var delay = 1000 * {{ $MessageSec }}; 
setTimeout(function(){ window.location = "{{url('/')}}{{$MessageList[0]['operate_path']}}" }, delay);	
})

</script>
@endsection