<div class="box box-solid ">

    <!-- /.box-header -->
    <div class="box-body member_left_menu">
        
        <ul>
            <a href="{{url('/member_index')}}"><li class="@if( $now_function == 'member_index' ) active @endif">會員中心入口</li></a>
            <a href="{{url('/member_edit')}}"><li class="@if( $now_function == 'member_edit' ) active @endif">個人資料</li></a>
            <a href="{{url('/member_order')}}"><li class="@if( $now_function == 'member_order' ) active @endif">我的訂單</li></a>
        </ul>

    </div>
    <!-- /.box-body -->
</div>        
<style type="text/css">
.member_left_menu > ul{
    list-style: none;
    padding-left: 0px;
}

.member_left_menu > ul > a > li {
	font-size: 14px;
	color: black;
	border: 1px solid #a7a7a7;
	margin-bottom: 6px;
	padding: 15px;
}
.member_left_menu > ul > a > li.active{
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#dddddd+0,898989+100 */
    background: #dddddd; /* Old browsers */
    background: -moz-linear-gradient(top,  #dddddd 0%, #898989 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(top,  #dddddd 0%,#898989 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to bottom,  #dddddd 0%,#898989 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dddddd', endColorstr='#898989',GradientType=0 ); /* IE6-9 */    
}
</style>