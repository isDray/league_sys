@php

  use App\Cus_lib\Lib_block;
  
  use Illuminate\Http\Request;
  
  $hotTags = Lib_block::get_hot_tags();
  
  /*var_dump( $hotTags );*/
@endphp

@if( $hotTags )
<div class="box box-solid">
	<div class="box-header with-border">
	    <h2 class="box-title recommend_title">熱門標籤</h2>			
	    <h3 class="page_more_desc">網友熱搜情趣用品標籤，現在就利用熱門標籤找到最適合你的情趣用品</h3>
	</div>

	<div class="box-body">

		@foreach( $hotTags as $hotTagk => $hotTag )
        <input type='checkbox' id="@if( isset($nowblock) && $nowblock ==1 ) block1hotTag{{$hotTagk}} @else block2hotTag{{$hotTagk}} @endif" class='twoStepCheckbox'>

        <label for="@if( isset($nowblock) && $nowblock ==1 ) block1hotTag{{$hotTagk}} @else block2hotTag{{$hotTagk}} @endif" class='tagSpan_block' tagtext="{{$hotTag['hashtag']}}">
            {{$hotTag['hashtag']}}
            <a href="{{url('/search')}}/{{$hotTag['hashtag']}}" title='查商品'><span><i class="fa fa-fw fa-gift"></i></span></a>
            <a href="{{url('/league_article_list')}}/{{$hotTag['hashtag']}}" title="查文章"><span><i class="fa fa-fw fa-file-text-o"></i></span></a>
        </label>
        @endforeach
       
	</div>
</div>
@endif
<style type="text/css">
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

/*************************************/
.borderAnim{
	position: relative;
	width: 60px;
	height: 40px;
	background-color:#c4d4d4;
	overflow: hidden;
}
.borderAnim::before{
    position: absolute;
    content: "";
    display: inline-block;  
    width: calc(100% - 2px);
    height: calc( 100% - 2px );
    top:1px;
    left: 1px;
    z-index: 2;
    background-color: #c4c4c4;
}
.borderAnim::after{
    position: absolute;
    content: "";
    display: inline-block;
    width: 60px;
    height: 60px;
    background-image: linear-gradient(to left, #008b8b, #ffff00);
    border:1px solid #dddddd;
    top:calc( 50% - 1px );
    left:calc( 50% - 31px);
    z-index: 1;
    /*animation-name: example;*/
     /*animation-timing-function: ease;*/
    animation-timing-function: ease-in;
    animation:example 6s infinite;    
    transform-origin:top center;
}

@keyframes example {
  from {
    -ms-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  to {
    -ms-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -webkit-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }	

}

</style>