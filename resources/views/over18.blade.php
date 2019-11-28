<html lang="zh-Hant-TW">
<head>
    <title>@if( !empty($title) ){{$title}}-@endif{{$LeagueData['store_name']}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes" name="viewport">
    @if( isset($description) )
    <meta name="description" content="{{$description}}">
    @endif

    @if( isset($keywords) )
    <meta name="keywords" content="{{$keywords}}">
    @endif      
</head>
<body>
<h1 class='h1_18'>@if( isset($page_header) ){{$page_header}}@endif</h1>
<div id='w18'>
    <img src="{{url('over18_pic/0/wc.jpg')}}" width='768' height='1080' usemap="#Map" alt"確認您已滿18歲,即可開始探索在{{$LeagueData['store_name']}}中種類豐富的情趣用品">
    
    <map name="Map">
        <area shape="rect" coords="130,370,370,470" href="{{url('/index')}}">
        <area shape="rect" coords="400,370,640,470" href="https://tw.yahoo.com/" target="_blank">
    </map>        

</div>

<div id='m18'>
    <a href="{{url('/index')}}">
    <img src="{{url('over18_pic/0/ml.png')}}" alt="未滿18歲,離開本站">
    </a>

    <a href="https://tw.yahoo.com/">
    <img src="{{url('over18_pic/0/mr.png')}}" alt="已滿18歲開始探索情趣用品">
    </a>
</div>

</div>
</body>
</html>

<style type="text/css">

#w18{
    text-align: center;
}
#m18{
    display: none;
}
.h1_18{
    display: none;
}
@media(max-width: 768px) {
    #w18{
        display: none!important;
    }
    #m18{
        display: block!important;
    }
    #m18 a img{
        width: 50%;
        float: left;
    }
}

</style>