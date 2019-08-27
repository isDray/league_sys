@extends('league_front')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/register_result.css')}}">
@endsection

@section('content')
    <div class='col-md-6 col-md-offset-3 col-sm-12 col-xs-12' id='register_result'>
    
        <div class="box box-solid">
            
            <div class="box-header with-border @if( $result == 1)bg-green-active @else bg-red-active @endif color-palette">
              <h3 class="box-title" id='register_result_title'>
              @if( $result == 1)
                  註冊成功
              @else
                  註冊失敗
              @endif
              </h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                @if( $result == 1)
                    恭喜您已完成註冊 , 加盟商會員帳號審核開通需2~3個工作天 , 請您耐心等待 ,
                    並且留意信箱中之信件 , 審核結果我們將以信件通知您 , 感謝您的等候。
                @else
                    註冊過程中發生錯誤 , 請稍待片刻後再嘗試註冊 , 有任何問題歡迎來電洽詢 , 謝謝 。
                @endif
            </div>
            <!-- /.box-body -->
        </div>        

    </div>
@endsection

@section('selfjs')

@endsection