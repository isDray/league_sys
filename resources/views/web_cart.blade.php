@extends("web2")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/web_cart.css')}}">
@endsection

    @section('content_right')
    {!!$Breadcrum!!}
    
    @foreach( $CenterBlocks as $CenterBlockk => $CenterBlock)
    
        @if( is_array($CenterBlock) )
            @includeIf('block_'.$CenterBlock[0],['id'=>$CenterBlock[1]])
        @else
            @includeIf('block_'.$CenterBlock)
        @endif
    
    @endforeach

@endsection

@section('selfjs')
<script type="text/javascript">

/*
|--------------------------------------------------------------------------
| 修改商品數量
|--------------------------------------------------------------------------
|
*/

$(function(){
    
    $('body').on('change', '.cart_goods_num', function() {
        
        var change_id  = $(this).attr('goods_id');

        var change_num = $(this).val();
        
        var changerequest = $.ajax({
            
            url: "{{url('/change_goods_num')}}",
            method: "POST",
            data: { goods_id : change_id ,
                    _token: "{{ csrf_token() }}",
                    wantNum  :change_num
            },
            dataType: "json"
        
        });
 
        changerequest.done(function( res ) {
        
        toastr.success('成功修改數量');

        // 如果順利加入購物車 , 就重整購物車內容
        location.reload();
    });
 
    changerequest.fail(function( jqXHR, textStatus ) {
        //alert( "Request failed: " + textStatus );
    });
    });

});




/*
|--------------------------------------------------------------------------
| 自購物車移除( 由於需要重整頁面所以故意分開)
|--------------------------------------------------------------------------
|
*/
$('body').on('click', '.rmbtn_cart', function() {
    
    var rm_id = $(this).attr('goods_id');
    
    var rmrequest = $.ajax({
        url: "{{url('/rm_from_cart')}}",
        method: "POST",
        data: { goods_id : rm_id ,
                _token: "{{ csrf_token() }}",
        },
        dataType: "json"
    });
 
    rmrequest.done(function( res ) {
        
        toastr.success('成功移除商品');

        location.reload();
    });
 
    rmrequest.fail(function( jqXHR, textStatus ) {
        //alert( "Request failed: " + textStatus );
    });

});
</script>
@endsection