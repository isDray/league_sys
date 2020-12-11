@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/suggestags/css/amsify.suggestags.css')}}">
@endsection

@section('content')

@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
<div class='row custom_row'>    
    <div class='col-md-12 col-sm-12 col-xs-12'>        
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">商品對應標籤編輯</h3>
            </div>
            <!-- /.box-header -->
 
            <div class="box-body">

                @if( $act == 'new' )
                <div class='row'>
                    <div class="col-md-4 col-sm-8 col-xs-12">
                        <textarea class="form-control" name="goodsSn" id="goodsSn" placeholder="請輸入商品編號，開始調整該商品標籤"></textarea>
                    </div>
                    <div class="col-md-8 col-sm-4 col-xs-12">
                        <button type="button" class="btn btn-success btn-flat" id="addGoodsItem">將商品加入編輯</button>
                    </div>
                </div>

                <hr style='border-top:1px dashed #bbb;'>

                <div class='row'>
                    <div class="col-md-4 col-sm-8 col-xs-12">
                        <input type="text" class="form-control" id='tagForAll'>
                    </div>
                    <div class="col-md-8 col-sm-4 col-xs-12">
                        <button type="button" class="btn btn-success btn-flat" id='addTagToAll'>新增標籤至全部商品</button>
                    </div>
                </div>
                
                <hr style='border-top:0px dashed #bbb;'>
                @endif

                <div class='row'>
                    <div class='col-md-4 col-sm-6 col-xs-12'>
                        
                        <form action="{{url('/league_module_goods_and_tags_edit_act')}}" method='post'>
                            <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                            <table id='realForm' class="table table-bordered" border='1'>
                            @if( $act == 'edit' )
                            <tr>
                                <td> <input type='hidden' value="{{ $goodsTags['goods_sn'] }}" name='goodsSns[]' >{{ $goodsTags['goods_sn'] }}</td>
                                <td> <input type='text' class='form-control tagInput' name='tagInput[]' value="{{ $goodsTags['goodsTags'] }}"/></td>
                            </tr>
                            <tr><td colspan='2'><input type='submit' value='確定' class='btn btn-primary' ></td></tr>
                            @endif
                            </table>
                        </form>                        
                    </div>   
                </div>

                <!-- <input type="text" class="form-control" id="goodsSn" name="goodsSn" placeholder="請輸入想編輯的商品編號" value=""> -->
            </div>
            <!-- /.box-body -->

<!--             <div class="box-footer">
                <button type="submit" class="btn btn-primary">確定</button>
            </div> -->
            
        </div>
    </div>
</div>
@endsection

@section('selfjs')
<script src="{{url('/suggestags/js/jquery.amsify.suggestags.js')}}"></script>
<script type="text/javascript">
@if( $act == 'new' )
$(function(){
    
    var amsifyArray = [];

    $("#addGoodsItem").click(function(){
       
        var goodsSn = $( "#goodsSn" ).val();
        
        var request = $.ajax({
            url: "{{url('/leagueGetTagsByGoodsSn')}}",
            method: "POST",
            data: 
            { 
                goodsSn : goodsSn, 
                _token  : "{{ csrf_token() }}",
            },
            dataType: "json"
        });
 
        request.done(function( returnDatas ) {
            
            if( !returnDatas['res'] )
            {
                alert( returnDatas['msg'] );
            }
            else  
            {   
                amsifyArray = [];
                $("#realForm").empty();
                
                var addTableHead = true;
                
                appendTags = '';
                
                $.each( returnDatas['datas'], function( key, value ) {
                    
                    console.log( addTableHead );
                    if( addTableHead == true )    
                    {
                        appendTags += '<tr><th>商品編號</th><th>標籤</th></tr>';
                        
                        addTableHead = false;
                    }
                    appendTags += '<tr>';
                    appendTags += "<td><input type='hidden' value='"+key+"' name='goodsSns[]' >"+key+"</td>";
                    appendTags += "<td><input type='text' class='form-control tagInput' name='tagInput[]' value='"+value+"'/></td>";
                    appendTags += '</tr>';
                           
                });
                
                appendTags += "<tr><td colspan='2'><input type='submit' value='確定' class='btn btn-primary' ></td></tr>";

                $("#realForm").append(appendTags);

                $('.tagInput').each(function(){

                    amsifyArray.push( new AmsifySuggestags($(this)) );

                    amsifyArray[ amsifyArray.length - 1 ]._settings({

                        suggestionsAction : 
                        {
                            timeout: -1,
                            minChars: 1,
                            minChange: -1,
                            delay: 500,
                            type: 'GET',
                            url: "{{url('/league_article_tag')}}",

                        }
            
                    });

                    amsifyArray[ amsifyArray.length - 1 ]._init();

                });



            }

        });
         


 
        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

    });
    

    $("#aa").click(function(){
        
        $.each( amsifyArray , function( _eachk, _each ) {
            /*_each.addTag('Purple');*/

            _each.destroy();
        });
    });




    /*
    |--------------------------------------------------------------------------
    | 新增標籤至全部商品input多選標籤
    |--------------------------------------------------------------------------
    |
    */
    var tagForAll = new AmsifySuggestags( $("#tagForAll") );

    tagForAll._settings({
        selectOnHover: false,
        selectOnHover: 3,
        suggestionsAction : 
        {
            timeout: -1,
            minChars: 1,
            minChange: -1,
            delay: 500,
            type: 'GET',
            url: "{{url('/league_article_tag')}}",
        }        
    });

    tagForAll._init();   




    /*
    |--------------------------------------------------------------------------
    | 將標籤寫入所有指定商品中
    |--------------------------------------------------------------------------
    |
    */ 
    $("#addTagToAll").click(function(){
        
        // 取得標籤資料
        //console.log( $("#tagForAll").val() );
        allTags = $("#tagForAll").val().split(",");
        
        // 確認已經產生多選欄位
        if( amsifyArray.length > 0 )
        {
            // 迴圈新增標籤
            $.each( allTags , function( _allTagk, _allTag ) {
                
                $.each( amsifyArray , function( _eachk, _each ) {
                    
                    _each.addTag( _allTag );

                });
            });
        }
        else
        {
            alert("目前尚未加入任何編輯商品，請先加入商品");
        }


    });
});
@else
$(function(){

    var amsifyArray = [];

    $('.tagInput').each(function(){

        amsifyArray.push( new AmsifySuggestags($(this)) );

        amsifyArray[ amsifyArray.length - 1 ]._settings({

            suggestionsAction : 
            {
                timeout: -1,
                minChars: 1,
                minChange: -1,
                delay: 500,
                type: 'GET',
                url: "{{url('/league_article_tag')}}",
            }
            
        });

        amsifyArray[ amsifyArray.length - 1 ]._init();

    });
});
@endif
</script>
@endsection