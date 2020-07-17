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
                <h3 class="box-title">堆疊商品輪播列表</h3>
                
                <div class='box-tools'>
                <a href="{{url('/league_module_recommend_custom_ad_edit')}}">

                    <span class='btn btn-success text-right'><i class='fa fa-fw fa-plus'></i>新增</span>
                </a>
                </div>

            </div>

            <div class="box-body ">

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>堆疊商品輪播標題</th>
                            <th>最後編修時間</th>
                            <th>操作</th>
                        </tr>
                        
                        @foreach( $stacks as $stackk => $stack)
                        <tr>
                            <td>{{ $stack['title'] }}</td>
                            <td>{{ date('Y-m-d H:s:i' , $stack['edit_time'] + date('Z')) }}</td>
                            <td> 
                                <a href="{{url('/league_module_recommend_custom_ad_edit/').'/'.$stack['id']}}" title='編輯'><i class='fa fa-fw fa-edit'></i></a>

                                <a title='刪除' onclick="deleteStackRecommend(this)" value="{{$stack['id']}}" ><i class='fa fa-fw fa-trash'></i></a>
                            </td>
                        </tr>                        
                        @endforeach

                
                    </tbody>

                </table>

            </div>

            <div class='box-footer'>
            {{--!! $pages !!--}}
            </div>

        </div>

    </div>

</div>
@endsection

@section('selfjs')
<script type="text/javascript">

function deleteStackRecommend( cancel_recommend ){
    var cancel_recommend = cancel_recommend.getAttribute('value');

    if( !cancel_recommend )
    {
        alert("移除過程有誤，請重新整理後再嘗試");
        return false;
    }

    if (confirm("項目刪除後,將無法恢復,確定要刪除此項目?")) 
    {

        var delajax = $.ajax({
            url: "{{url('/league_module_recommend_custom_gad_del')}}",
            method: "POST",
            data: { 
                      "_token": "{{ csrf_token() }}" ,
                      "custom_ad_id":cancel_recommend
                  },
            dataType: "json"
        });
 
        delajax.done(function( data ) {
            
            // 如果成功就呈現刪除成功
            if($.isEmptyObject(data.error))
            {
                alert(data.success);
                location.reload();
            }
            // 刪除失敗呈現錯誤訊息
            else
            {
                printErrorMsg(data.error);
            }
        });
 
        delajax.fail(function( jqXHR, textStatus ) {
            //console.log( "Request failed: " + textStatus );
        });
    }
    else
    {
        
        return false;
    }
    
}


function printErrorMsg (msg) {
    
    var err_msg = '';
    $.each( msg, function( key, value ) 
    {
       err_msg += value+'\n\r';
            
    });

    alert(err_msg);
}
</script>
</script>
@endsection