@extends('league_admin')

@section('selfcss')
@endsection

@section('content')
<div class='row custom_row'>
    
    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">密碼表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

  
            <form role="form" action="{{url('/league_profile_password_act')}}" method="post" onsubmit="return confirm('為確保帳號安全 , 在密碼變更成功後 , 系統將自動執行登出 ');">
            
            {{ csrf_field() }}
            <input type="hidden" value="{{\Session::get('user_id')}}" name='user_id'> 
            <div class="box-body ">
                
                <div class="form-group">

                    <label for="oldpassword">原密碼</label>
                    <input type="password" class="form-control" id="oldpassword" placeholder="請輸入原密碼" name="oldpassword">
                    @if ($errors->has('oldpassword'))
                    <label id="oldpassword-error" class="form_invalid" for="oldpassword">{{ $errors->first('oldpassword') }}</label>
                    @endif    

                </div>

                <div class="form-group">
                    <label for="newpassword">新密碼</label>
                    <input type="password" class="form-control" id="newpassword" placeholder="請輸入新密碼" name="newpassword">
                    @if ($errors->has('newpassword'))
                    <label id="newpassword-error" class="form_invalid" for="newpassword">{{ $errors->first('newpassword') }}</label>
                    @endif
                </div>

                <div class="form-group">
                    <label for="newpasswordconfirm">密碼確認</label>
                    <input type="password" class="form-control" id="newpasswordconfirm" placeholder="請重複輸入新密碼 , 確認密碼無誤" name="newpasswordconfirm">
                    @if ($errors->has('newpasswordconfirm'))
                    <label id="newpasswordconfirm-error" class="form_invalid" for="newpasswordconfirm">{{ $errors->first('newpasswordconfirm') }}</label>
                    @endif                    
                </div>                                

            </div>
            
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">確定</button>
            </div>

            </form>
        </div>

    </div>

</div>
@endsection

@section('selfjs')
<script type="text/javascript">
function repass(){

}
</script>
@endsection