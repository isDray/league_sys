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
        <div class="@if( isset($nowblock) && $nowblock ==1 ) col-md-4 col-sm-4 col-xs-12 @else col-md-12 col-sm-12 col-xs-12 @endif">
            <h2 class='articleName'><a href="/league_article/{{$article['id']}}">{{ $article['title'] }}</a></h2>

            <div class='articleTagBox'>
                @foreach( $article['hashtag'] as $tagk => $tag )
                <input type='checkbox' id="@if( isset($nowblock) && $nowblock ==1 ) block1tagNO{{$tagk}} @else block2tagNO{{$tagk}} @endif" class='twoStepCheckbox'>

                <label for="@if( isset($nowblock) && $nowblock ==1 ) block1tagNO{{$tagk}} @else block2tagNO{{$tagk}} @endif" class='tagSpan_block' tagtext="{{$tag}}">
                    {{$tag}}
                    <a href="{{url('/search')}}/{{$tag}}" title='查商品'><span><i class="fa fa-fw fa-gift"></i></span></a>
                    <a href="{{url('/league_article_list')}}/{{$tag}}" title="查文章"><span><i class="fa fa-fw fa-file-text-o"></i></span></a>
                </label>
                @endforeach
            </div>

        </div>
	@endforeach

	</div>
	<div class="box-footer">
	    <span id="moreArticle" ><a href="{{url('/league_article_list')}}"><b>查看全部文章</b></a></span>
	</div>

</div>
@endif
<style type="text/css">
.articleName{
    height: 80px;
    line-height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;    
}

.articleName a {
    color:#444;
}
.twoStepCheckbox{
    display: none;
}
/* 標籤本體 */
.tagSpan_block{
    overflow: hidden;
    position: relative;
    cursor: pointer;    
    margin-top: 5px;
    display: inline-block;
    border-radius: 20px;
    background-color: #ffbb44;
    padding:0px 20px 0px 20px;
    line-height: 30px;
    min-width: 100px;
}
.tagSpan_block::before
{   
    position: absolute;
    content:"#";
    left:0px;
    top:0px;
    z-index: 11;
    width:20px;
    background-color: #d89314;
    text-align: center;
}
input:checked + .tagSpan_block::before
{
    content:'x';
}
.tagSpan_block::after{
    position: absolute;
    content: attr(tagtext);
    text-align: center;
    width: calc( 100% - 20px );
    height: 100%;
    top:0px;
    left: 20px;
    background-color: orange;
    z-index: 10;
    transition:0.8s;
}
.tagSpan_block a span
{   
    position: absolute;
    top:0px;
    color:#333;
    height: 100%;
    width: calc( 50% - 10px);
    text-align: center;
    background-color: #ffbb44;
}
.tagSpan_block a:nth-child(1) span
{   left: 20px;
    z-index: 5;
}
.tagSpan_block a:nth-child(2) span
{   left: calc(50%);
    z-index: 5;
}

input:checked + .tagSpan_block::after{
    left:-100%;
}
.articleTagBox{
    height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;       
}
</style>