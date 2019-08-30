@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/league_module_banner.css')}}">
@endsection

@section('content')
<div class='row custom_row'>
    
    <div class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            <div class="box-header with-border">     
                <h3 class="box-title">banner列表</h3>

                <div class="box_btn_group">
                    <a href="{{url('/league_module_banner_new')}}"><span class='btn-sm btn-primary'>新增banner</span></a>
                </div>
            </div>

            <div class="box-body">

                <ul id="banner_sortable">
                    @foreach( $banners as $bannerk => $banner )
                    <li class="ui-state-default banner_itm" banner_id="{{$banner['id']}}" ><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                        <div class='banner_div'>
                        <img src="{{url('/banner/'.Session::get('user_id').'/'.$banner['banner'])}}">
                            
                            <div class='btn_grp'>
                                <a class="btn btn-social-icon btn-primary" href="{{url('/league_module_banner_edit/'.$banner['id'])}}"><i class="fa fa-fw fa-edit"></i></a>
                                <a class="btn btn-social-icon btn-primary" href="{{url('/league_module_banner_del_act/'.$banner['id'])}}" onclick="return confirm('即將刪除此banner , 確定要刪除嗎?')" ><i class="fa fa-fw fa-remove"></i></a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                
                <form action="{{url('/league_module_banner_sort_act')}}" method="post" onsubmit="return get_sort();" id="sortform">
                    
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-primary">確定</button>

                </form>
                
            </div>

        </div>		
	  </div>
</div>
@endsection

@section('selfjs')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{url('/js/jquery.ui.touch-punch.js')}}"></script>

<script>

function get_sort(){
    
    $("#sortform .blockinput").remove();

    $.each( $("#banner_sortable  li") , function( index, value ) {
        //alert( index + ": " + value.val() );
        $("#sortform").append("<input class='blockinput' type='hidden' name='blocksort[]' value='"+$(this).attr('banner_id')+"'>");
    });    
    return true;
}

$( function() {
    $( "#banner_sortable" ).sortable();
    $( "#banner_sortable" ).disableSelection();
} );

</script>
@endsection