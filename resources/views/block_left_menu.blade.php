<div class="box box-solid ">

    <!-- /.box-header -->
    <div class="box-body">
        
        <div class="box-group" id="accordion">
            @foreach($categorys as $categoryk => $category)
            
            <div class="panel box left_menu_item_root">
            
                <div class="box-header with-border left_menu_item_div">
                    <h4 class="box-title">
                    	<a href="{{url('/category/'.$category['rcat'])}}">{{ $category['rcat_name'] }}</a>
                    </h4>
                    
                    @if( count($category['child'] ) > 0)
<!--                     <a data-toggle="collapse" data-parent="#accordion" href="#accordion_{{$category['rcat']}}" aria-expanded="false" class="collapsed left_menu_item">
                        <i class='fa fa-fw fa-plus-square left_menu_item_i' ></i>
                    </a> -->
                    @endif

                    @if( count($category['child'] ) > 0)
                    <div id="accordion_{{$category['rcat']}}" class="block_leftmenu_childmenu" aria-expanded="false" style="height: 0px;">
                        <div class="box-body">
                            @foreach( $category['child'] as $leftchildk => $leftchild )
                                <li class='left_menu_child_item'><a href="{{url('/category/'.$leftchild['ccat'])}}"><i class="fa fa-fw fa-angle-right"></i> {{$leftchild['ccat_name']}}</a></li>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
                

            
            </div>            
            @endforeach
        </div>

    </div>
    <!-- /.box-body -->
    
</div>        

<style type="text/css">
.left_menu_item>i{
	color:#eeeeee;
}
.left_menu_item>i:before{
	content:"\f146";
}

.left_menu_item.collapsed>i{
	color:#eeeeee;
}

.left_menu_item.collapsed>i:before{
	content:"\f0fe";
}
.left_menu_item_div{
    height: 40px;
}
.left_menu_item_div >h4 {
	font-weight: 900;
	color: #eeeeee;
	font-family: "微軟正黑體";
}
.left_menu_item_div >h4>a{
	color:#eeeeee;
}
.left_menu_item_i{
	float:right;
	line-height: 20px;
}
.left_menu_item_root{
	border-top: 0px;
	border-bottom: 0px;
}
.left_menu_item_root > div > .box-body{
	list-style: none;
}
.left_menu_child_item > a {
	color:#eeeeee;
	font-family: "微軟正黑體";
	font-weight: 900;
}

.block_leftmenu_childmenu .box-body{
    background-color: rgba(44,44,44,1);
    position: absolute;
    right: calc( -100% + 60px );
    top:0px;
    width: 260px;
    z-index: 800;
    display: none;
    border-left: 1px solid black;
}
.block_leftmenu_childmenu .box-body{
    list-style: none;
    padding: 0px;
}
.block_leftmenu_childmenu .box-body li:hover{
    background-color: #b1097f;
}
.left_menu_item_div:hover > .block_leftmenu_childmenu .box-body{
    display: inline-block;
}
.left_menu_item_div  h4{
    font-size: 14px!important;
    font-weight: 900!important;
}
</style>