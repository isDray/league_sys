@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{url('/css/league_sort_center.css')}}">
@endsection

@section('content')
<div class='row custom_row'>

<div class='col-md-2 col-md-offset-4 col-sm-6 col-xs-6' id='bloack_on' subject="呈現區塊" >
    <ul id="sortable1" class="connectedSortable">
        <li class="ui-state-highlight pin" blocknum='1' >banner</li>
    	@foreach( $OnModules as $OnModulek => $OnModule)
    	@if( $OnModulek != 1 )
        <li class="ui-state-highlight" blocknum='{{$OnModulek}}' >

            @if( $OnModule['edit_route_name'] != '')
            <div class="box-tools">
                  <a href="{{URL( $OnModule['edit_route_name'])}}@if(!empty(explode('_',$OnModulek)[1]))/{{explode('_',$OnModulek)[1]}}@endif" target='_blank'>
                      <button type="button" class="btn btn-primary btn-sm">
                      <i class="fa fa-fw fa-edit"></i></button>
                  </a>
            </div>
            @endif

            {{$OnModule['block_name']}}
        </li>
        @endif
    	@endforeach
    </ul>
</div> 

<div class='col-md-2 col-md-offset- col-sm-6 col-xs-6' id='block_off' subject="待用區塊" >
    <ul id="sortable2" class="connectedSortable">
    	@foreach( $OffModules as $OffModulek => $OffModule)
        @if( $OffModulek != 1 )
        <li class="ui-state-highlight" blocknum='{{$OffModulek}}'>
            @if( $OffModule['edit_route_name'] != '')
            <div class="box-tools">
                  <a href="{{URL( $OffModule['edit_route_name'])}}@if(!empty(explode('_',$OffModulek)[1]))/{{explode('_',$OffModulek)[1]}}@endif" target='_blank'>
                      <button type="button" class="btn btn-primary btn-sm">
                      <i class="fa fa-fw fa-edit"></i></button>
                  </a>
            </div>
            @endif
            {{$OffModule['block_name']}}
        </li>
        @endif
    	@endforeach
    </ul>
</div>

</div>

<div class='row custom_row'>
	<div class='col-md-12 col-sm-12 col-xs-12 text-center'>

    <form  action="{{url('/league_sort_center_act')}}" method="post" onsubmit="return get_sort();" id='sortform'>
    	{{ csrf_field() }}
    	<input type='submit' value='送出' class='btn btn-primary'>
    </form>

    </div>
</div>
@endsection

@section('selfjs')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{url('/js/jquery.ui.touch-punch.js')}}"></script>

<script>

function get_sort(){
    
    $("#sortform .blockinput").remove();

    $.each( $("#sortable1  li") , function( index, value ) {
        //alert( index + ": " + value.val() );
        $("#sortform").append("<input class='blockinput' type='hidden' name='blocksort[]' value='"+$(this).attr('blocknum')+"'>");
    });    
    return true;
}

$( function() {
    
    $( "#sortable1, #sortable2" ).sortable({
        items: '> li:not(.pin)',
        connectWith: ".connectedSortable"
    
    }).disableSelection();

} );

</script>
@endsection