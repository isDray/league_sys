@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/login.css')}}">
@endsection

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @includeIf('block_'. $LeftBlock)

    @endforeach

@endsection

@section('content_right')
    
    @foreach( $CenterBlocks as $CenterBlockk => $CenterBlock)
        
        @includeIf('block_'. $CenterBlock)

    @endforeach

@endsection

@section('selfjs')

@endsection