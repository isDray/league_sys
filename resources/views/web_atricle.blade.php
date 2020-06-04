@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/article.css')}}">
@endsection

@section('content_left')

    @foreach( $LeftBlocks as $LeftBlockk => $LeftBlock)
        
        @if( is_array($LeftBlock) )
            @includeIf('block_'. $LeftBlock[0],['id'=>$LeftBlock[1]])
        @else
            @includeIf('block_'. $LeftBlock)
        @endif

    @endforeach

@endsection

@section('content_right')

<!-- 呈現區塊 -->
<div class="box box-solid">
    
    <div class="box-header with-border">
        <!-- <i class="fa fa-text-width"></i> -->

        <h1 class="box-title">{{$article_title}}</h1>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body article_content">
   	{!!$article!!}
    </div>
    <!-- /.box-body -->

</div>
<!-- /呈現區塊 -->
@endsection

@section('selfjs')

@endsection