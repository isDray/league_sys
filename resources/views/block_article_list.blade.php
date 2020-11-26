@php

  use App\Cus_lib\Lib_block;
  
  use Illuminate\Http\Request;
  
  $articles = Lib_block::get_article();
            
@endphp

     
@if( $articles )
<div class="box box-solid">

	<div class="box-header with-border">
	    <h2 class="box-title recommend_title">文章列表</h2>			
	    <h3 class="page_more_desc">情趣經驗討論/新品上市開箱/情趣商品購買攻略，最完整的情趣文章讓你快速上手成為情趣大師</h3>
	</div>

	<div class="box-body">
	@foreach( $articles as $articlek => $article )
        <div class="col-md-4 col-sm-4 col-xs-12">
            <h2><a href="/league_article/{{$article['id']}}">{{ $article['title'] }}</a></h2>

            <div>
                @foreach( $article['hashtag'] as $tag)
                <span class='tagSpan'>#{{$tag}}</span>
                @endforeach
            </div>
        </div>
	@endforeach

	</div>

	<div class="box-footer">
	    <span id="moreArticle" ><a href=""><b>查看全部文章</b></a></span>
	</div>

</div>

@endif

<style type="text/css">
.tagSpan{
    display: inline-block;
    border-radius: 20px;
    background-color: orange;
    padding:0px 5px 0px 5px;
}
</style>