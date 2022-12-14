@extends("web1")

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/article.css')}}">
<link rel="stylesheet" href="{{url('/suggestags/css/amsify.suggestags.css')}}">
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

<!-- εηΎεε‘ -->
@if( $formSearch )
<style type="text/css">
.folder{
    position: relative;
    width: 40px;
    height: 30px;
    border-radius:2px 2px 0px 0px;
    background-color: orange;
    transform-origin:bottom;
    transform:skewX(-3deg);    
}
.folder::after{
    position: absolute;
    display: inline-block;
    content: "";
    width: calc(100% - 1px);
    height: 70%;
    z-index: 64;
    background-color: orange;
    left:0px;
    bottom: 0px;
    box-shadow: 1px -1px 3px rgba(0,0,0,.6);
    transform-origin:bottom;
    transform:skewX(10deg);
    transition: 1.2s;
    transition-timing-function: ease;
}
input:checked+.folder::after
{
    transform-origin:bottom;
    height: 30%;
    transform:skewX(60deg);
    box-shadow: 1px 0px 3px rgba(0,0,0,.6);
}
.folder > span{
    position: absolute;
    display: inline-block;
    width: 100%;
    height: 80%;
    background-color: white;
    border:1px solid #d4d4d4;
    /*bottom: attr()*/
    right: 0px;
    transform-origin:bottom;
    transform:skewX(-2deg);
    transition: 1.2s;
    transition-timing-function: ease;    
}

input:checked+.folder > span
{   
    transform-origin:bottom;
    transform:skewX(2deg);
    width: 120%;
    height: 120%;
    right: -140%;
}
</style>
<div class="box box-solid">
   <!-- 
    <div class='box-body'>
        
        <div class='col-md-12 col-sm-12 col-xs-12'>
            <input type='checkbox' id='folder'> 
                
                <label class='folder' for='folder' >
                    <span style='bottom:0px;z-index:63'></span>
                    <span style='bottom:2px;z-index:62'></span>
                    <span style='bottom:4px;z-index:61'></span>
                </label>
        </div>        
    </div>
    -->
    <div class="box-body">
        <form action="{{url('/league_article_list')}}" method="post">
            {{ csrf_field() }}
            <input type='hidden' name='act' value='search'>

            <div class="row">
            
                <div class="col-md-4 col-sm-6 col-xs-12">
                        <label for="keyword"> ιι΅ε­: </label>
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="" value="@if( isset($filter['keyword'])){{$filter['keyword']}}@endif" >
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12">
                        <label for="hashtags"> ζ¨η±€: </label>
                        <input type="text" class="form-control" id="hashtags" name='hashtags' placeholder="" value="@if( isset($filter['hashtags'])){{$filter['hashtags']}}@endif">
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                        <label>&nbsp;</label>
                        <div class="col-xs-12">
                            <input type='submit' value='ζε°'>
                        </div>
                </div>

            </div>

        </form>

    </div>
</div>
@endif

<div class="box box-solid">
    
    <div class="box-header with-border">

    </div>
    
    <!-- /.box-header -->
    <div class="box-body article_content">
    @foreach( $articles as $articlek => $article )
        <div class='col-md-4 col-sm-6 col-xs-12 articleBox'>
            <h2 class='articleName'><a href="/league_article/{{$article['id']}}">{{ $article['title'] }}</a></h2>
            <div class='articleTagBox'>
                @foreach( $article['hashtag'] as $tag)
                <span class='tagSpan' value="{{$tag}}">#{{$tag}}</span>
                @endforeach
            </div>

            <!-- <div class='actMask text-center'> -->
                <!-- <span><a href="">ι±θ?</a></span> -->
            <!-- </div> -->
        </div>
        
    @endforeach
    </div>
    <!-- /.box-body -->

</div>
<!-- /εηΎεε‘ -->
<style type="text/css">
.articleBox{
    border:1px solid #d4d4d4;
    /*box-shadow: 2px 2px 5px black;*/
}
.articleName{
    height: 80px;
    line-height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;    
}
.tagSpan{
    cursor: pointer;
    margin-top: 5px;
    display: inline-block;
    border-radius: 20px;
    background-color: orange;
    padding:0px 5px 0px 5px;
    line-height: 30px;
}
.articleTagBox{
    height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;       
}
/*.actMask{
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-color: rgba(236,112,112,0.8);
    top:0px;
    left: 0px;
    display:table;   
    margin-left: 100%; 
    transition:all 1.2s ; 
}
.actMask span{
    
    display:table-cell;
    vertical-align:middle; 
    line-height: 40px;
    font-size: 30px;
}
.actMask span a {
    color:white;
}
.articleBox:hover .actMask{
    margin-left: 0px;
    transition:all 1.2s ;
}*/
</style>
@endsection

@section('selfjs')
<script src="{{url('/suggestags/js/jquery.amsify.suggestags.js')}}"></script>
<script type="text/javascript">
$(function()
{   

    amsifySuggestags = new AmsifySuggestags($('input[name="hashtags"]'));
 
    amsifySuggestags._settings({

        suggestionsAction : {
        timeout: -1,
        minChars: 1,
        minChange: -1,
        delay: 500,
        type: 'GET',

        url: "{{url('/league_article_tag')}}",
        beforeSend : function() {
                        console.info('beforeSend');
                    },
                    success: function(data) {
                       
                        //console.log( data );

                        /*suggestions  =  ["four", "five", "six"]; 
                      
                        $('input[name="hashtags"]').amsifySuggestags(suggestions, 'refresh');*/

                        //console.log( data );
                        //console.info('success');
                        //suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'];
                        //suggestions : JSON.parse(data);

                        //console.log( data );

                        /*
                        newData = JSON.parse( data );
                        consoloe.log( newData );
                        */
                        //$('input[name="hashtags"]').amsifySuggestags(), 'refresh');
                        //amsifySuggestags.refresh();
                    },
                    error: function() {
                        console.info('error');
                    },
                    complete: function(datas) {
                         //suggestions : JSON.parse(data);
                        //console.info('complete');
             //suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'];
                    }
                }
            
    });
    
    amsifySuggestags._init(); 

    $(".tagSpan").click(function(){
    
        var tmpTag = $(this).text().replace('#', '');

        amsifySuggestags.addTag( tmpTag );

    });
})
</script>
@endsection

