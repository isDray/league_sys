<html>
<head>
    <title></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body>
<div id='w18'>
    <img src="{{url('over18_pic/0/wc.jpg')}}" width='768' height='1080' usemap="#Map">
    
    <map name="Map">
        <area shape="rect" coords="130,370,370,470" href="{{url('/')}}">
        <area shape="rect" coords="400,370,640,470" href="https://tw.yahoo.com/" target="_blank">
    </map>        

</div>

<div id='m18'>
    <a href="{{url('/')}}">
    <img src="{{url('over18_pic/0/ml.png')}}">
    </a>

    <a href="https://tw.yahoo.com/">
    <img src="{{url('over18_pic/0/mr.png')}}">
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