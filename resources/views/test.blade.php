@extends('league_admin')

@section('content')
<textarea name='article-ckeditor' id='article-ckeditor' class='article-ckeditor'></textarea>
@endsection

@section('selfjs')

<script src="{{url('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
<script>
    $(function(){
    	CKEDITOR.replace( 'article-ckeditor' );
    })
    
</script>
@endsection