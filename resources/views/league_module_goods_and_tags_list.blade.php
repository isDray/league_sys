@extends('league_admin')

@section('selfcss')
<style type="text/css">
.articleBox{
    border:1px solid #d4d4d4;
    /*box-shadow: 2px 2px 5px black;*/
}
.articleName{
    height: 80px;
    line-height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;    
}
.tagSpan{
    cursor: pointer;
    margin-top: 5px;
    display: inline-block;
    border-radius: 20px;
    background-color: orange;
    padding:0px 5px 0px 5px;
    line-height: 30px;
    font-size: 18px;
    font-weight: 900;
}
.articleTagBox{
    height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;       
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

    <div id='goodsAndTagsBox' class='col-md-12 col-sm-12 col-xs-12'>

    	<div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">商品對應標籤管理</h3>
                
                <div class='box-tools'>
                <a href="{{url('/league_module_goods_and_tags_edit')}}">

                    <span class='btn btn-success text-right'><i class='fa fa-fw fa-plus'></i>新增</span>
                </a>
                </div>

            </div>
            
            <div class="box-body ">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>商品編號</th>
                            <th>標籤</th>
                            <th>操作</th>
                        </tr>
                        
                        @foreach ($goodsTags as $goodsTag)
                        @php
                            $tmpTagItems = explode( ',' , $goodsTag['goodsTags']  );
                        @endphp
                        <tr>
                            <td>{{ $goodsTag['goods_sn'] }}</td>
                            <td>
                                @foreach( $tmpTagItems as $tmpTagItemk => $tmpTagItem )
                                    <span class='tagSpan' value="">#{{$tmpTagItem}}</span>
                                @endforeach
                            </td>
                            <td> 
                                <a href="{{url('/league_module_goods_and_tags_edit/').'/'}}{{$goodsTag['goods_id']}}" title='編輯'><i class='fa fa-fw fa-edit'></i></a>
                                <a title='刪除' onclick="deleteGoodsTag(this)" value="{{$goodsTag['goods_id']}}" ><i class='fa fa-fw fa-trash'></i></a>
                            </td>
                        </tr>                        
                        @endforeach

                
                    </tbody>

                </table>                
                @foreach ($goodsTags as $goodsTag)
                
                @endforeach            	
            </div>

            <div class='box-footer'>
            {!! $pages !!}
            </div>           

    	</div>
    </div>

</div>
@endsection    


@section('selfjs')
<script type="text/javascript">

function deleteGoodsTag( goodstag_id ){

    var goodstag_id = goodstag_id.getAttribute('value');

    if( !goodstag_id )
    {
        alert("移除過程有誤，請重新整理後再嘗試");
        return false;
    }

    if (confirm("項目刪除後,將無法恢復,確定要刪除此項目?")) 
    {

        var delajax = $.ajax({ 
            url: "{{url('/league_module_goods_and_tags_del')}}",
            method: "POST",
            data: { 
                      "_token": "{{ csrf_token() }}" ,
                      "goodstag_id": goodstag_id
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
</script>
@endsection