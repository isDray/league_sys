@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
/*  #sortable1, #sortable2 {
    border: 1px solid #eee;
    width: 142px;
    min-height: 20px;
    list-style-type: none;
    margin: 0;
    padding: 5px 0 0 0;
    float: left;
    margin-right: 10px;
  }*/
  #sortable1 , #sortable2{
  	padding: 0px;
  }
  #sortable1 li, #sortable2 li {
  	list-style-type: none;
    margin: 0 5px 5px 5px;
    padding: 5px;
    font-size: 1.2em;
    /*width: 120px;*/
  }
  </style>
@endsection

@section('content')
<div class='row'>
<div class='col-md-2 col-md-offset-4 col-sm-6 col-xs-6' style='border:2px solid green;'>
<ul id="sortable1" class="connectedSortable">
</ul>
</div> 
<div class='col-md-2 col-md-offset- col-sm-6 col-xs-6' style='border:2px solid green;'>
<ul id="sortable2" class="connectedSortable">
  <li class="ui-state-highlight">banner</li>
  <li class="ui-state-highlight">熱銷商品</li>
  <li class="ui-state-highlight">推薦商品</li>
  <li class="ui-state-highlight">新品上市</li>
</ul>
</div>
</div>
@endsection

@section('selfjs')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{url('/js/jquery.ui.touch-punch.js')}}"></script>
  <script>
  $( function() {
    $( "#sortable1, #sortable2" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  } );
  </script>
@endsection