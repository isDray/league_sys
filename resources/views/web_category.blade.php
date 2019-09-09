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
<!-- 設定區塊 -->
<div class="box box-solid">
    
    <div class="box-header with-border">
        
        <i class="fa fa-text-width"></i>

        <h3 class="box-title">Text Emphasis</h3>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        進階
    </div>
    <!-- /.box-body -->

</div>
<!-- /設定區塊 -->

<!-- 呈現區塊 -->
<div class="box box-solid">
    
    <div class="box-header with-border">
        
        <i class="fa fa-text-width"></i>

        <h3 class="box-title">Text Emphasis</h3>
    </div>
    
    <!-- /.box-header -->
    <div class="box-body">
        {!! $Pages !!}
    </div>
    <!-- /.box-body -->

</div>
<!-- /呈現區塊 -->
@endsection

@section('selfjs')

@endsection