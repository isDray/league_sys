@extends("web1")

@section('selfcss')
    
    <link rel="stylesheet" href="{{url('/css/login.css')}}">
    
    @foreach($center_css as $center_cssk => $center_cssv)
    <link rel="stylesheet" href="{{url('/css/'.$center_cssv)}}">
    @endforeach

@endsection



@if( isset( $page_header ) )
    @section('page_header')
        {{$page_header}}{{$LeagueData['store_name']}}
    @endsection
@endif

@section('content_left')
@parent
    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @if( is_array($LeftBlock) )
            @includeIf('block_'. $LeftBlock[0],['id'=>$LeftBlock[1]])
        @else
            @includeIf('block_'. $LeftBlock)
        @endif
            

    @endforeach

@endsection

@section('content_right')
      
    @foreach( $CenterBlocks as $CenterBlockk => $CenterBlock)

        @if( is_array($CenterBlock) )
            @includeIf('block_'.$CenterBlock[0],['id'=>$CenterBlock[1]])
        @else
            @includeIf('block_'.$CenterBlock)
        @endif

    @endforeach
    

@endsection



@section('selfjs')

@endsection