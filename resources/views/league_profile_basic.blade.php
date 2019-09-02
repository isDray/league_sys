@extends('league_admin')

@section('selfcss')
@endsection

@section('content')
<div class='row custom_row'>
    
    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">基本資料表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

  
            <form role="form" action="{{url('/league_profile_basic_act')}}" method="post" >
            
            {{ csrf_field() }}
            <input type="hidden" value="{{\Session::get('user_id')}}" name='user_id'> 
            <div class="box-body ">
                
                <div class="form-group">
                    <label for="name">姓名</label>
                    <input type="text" class="form-control custom_form_control" id="name" placeholder="請輸入姓名" name="name" value="@if(old('name')){{old('name')}}@else{{$LeagueData['name']}}@endif">
                    @if ($errors->has('name'))
                    <label id="name-error" class="form_invalid" for="name">{{ $errors->first('name') }}</label>
                    @endif                    
                </div>

                <div class="form-group">
                    <label for="phone">手機</label>
                    <input type="text" class="form-control custom_form_control" id="phone" placeholder="請輸入手機" name="phone" value="@if(old('phone')){{old('phone')}}@else{{$LeagueData['mobile_phone']}}@endif">
                    @if ($errors->has('phone'))
                    <label id="phone-error" class="form_invalid" for="phone">{{ $errors->first('phone') }}</label>
                    @endif                     
                </div>

                <div class="form-group">
                    <label for="tel">電話</label>
                    <input type="text" class="form-control custom_form_control" id="tel" placeholder="請輸入電話" name="tel" value="@if(old('tel')){{old('tel')}}@else{{$LeagueData['home_phone']}}@endif">
                    @if ($errors->has('tel'))
                    <label id="tel-error" class="form_invalid" for="tel">{{ $errors->first('tel') }}</label>
                    @endif                           
                </div>

                <div class="form-group">
                    <label for="email">信箱</label>
                    <input type="email" class="form-control custom_form_control" id="email" placeholder="請輸入e-mail" name="email" value="@if(old('email')){{old('email')}}@else{{$LeagueData['email']}}@endif">
                    @if ($errors->has('email'))
                    <label id="email-error" class="form_invalid" for="email">{{ $errors->first('email') }}</label>
                    @endif                      
                </div>

                <div class="form-group">
                    <label for="bank">匯款銀行</label>
                    <input type="text" class="form-control custom_form_control" id="bank" placeholder="請輸入匯款銀行代碼" name="bank" value="@if(old('bank')){{old('bank')}}@else{{$LeagueData['bank_sn']}}@endif">
                    @if ($errors->has('bank'))
                    <label id="bank-error" class="form_invalid" for="bank">{{ $errors->first('bank') }}</label>
                    @endif                       
                </div>
                
                <div>

                <div class="form-group">
                    <label for="banksub">分行名稱</label>
                    <input type="text" class="form-control custom_form_control" id="banksub" placeholder="分行名稱" name="banksub" value="@if(old('banksub')){{old('banksub')}}@else{{$LeagueData['sub_name']}}@endif">
                    @if ($errors->has('banksub'))
                    <label id="banksub-error" class="form_invalid" for="banksub">{{ $errors->first('banksub') }}</label>
                    @endif                      
                </div>

                <div class="form-group">
                    <label for="bankaccount">匯款帳號</label>
                    <input type="text" class="form-control custom_form_control" id="bankaccount" placeholder="分行名稱" name="bankaccount" value="@if(old('bankaccount')){{old('bankaccount')}}@else{{$LeagueData['bank_account']}}@endif">
                    @if ($errors->has('bankaccount'))
                    <label id="bankaccount-error" class="form_invalid" for="bankaccount">{{ $errors->first('bankaccount') }}</label>
                    @endif                     
                </div>

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
@endsection