@extends('league_admin')

@section('selfcss')
<style type="text/css">
.colorsetbox>div>input[name=colorset]{
    opacity: 0;
}
.colorsetbox>div>label{
    background-color: #eeeeee;
    display: block;
    height: 40px;
    text-align: center;
    border:2px solid #eeeeee;
    padding-left: 0px;
}
.colorsetbox>div> input[name=colorset]:checked + label{
    border:2px solid #3c8dbc;
}
.colorsetbox>div>label{
    line-height: 36px;  
}
</style>
@endsection

@section('content')
<div class='row custom_row'>
    
    @if( $errors->any() )
    <div  class='col-md-12 col-sm-12 col-xs-12'>
        <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i>錯誤</h4>

        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        
    </div>
    </div>
    @endif


    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">會員列表</h3>
            </div>

            <div class="box-body ">

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>姓名</th>
                            <th>信箱</th>
                            <th>手機</th> 
                            <th>電話</th>
                            <th>最後登入時間</th>
                            <th>操作</th>
                        </tr>
                        @foreach( $LeagueMembers as $LeagueMemberk => $LeagueMember)
                        <tr>
                            <td>{{ $LeagueMember['name'] }}</td>
                            <td>{{ $LeagueMember['email'] }}</td>
                            <td>{{ $LeagueMember['phone'] }}</td>
                            <td>{{ $LeagueMember['tel'] }}</td>
                            <td>{{ $LeagueMember['login_time'] }}</td>
                            <td> 
                                <a href="{{url('/league_member_show/'.$LeagueMember['id'])}}" title='編輯'><i class='fa fa-fw fa-edit'></i></a>
                            </td>
                        </tr>                        
                        @endforeach

                
                    </tbody>

                </table>

            </div>

            <div class='box-footer'>
            {!! $pages !!}
            </div>

        </div>

    </div>

</div>
@endsection

@section('selfjs')
@endsection