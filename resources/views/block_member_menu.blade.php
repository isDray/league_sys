<div class="box box-solid ">

    <!-- /.box-header -->
    <div class="box-body member_left_menu">
        
        <ul>
            <a href="{{url('/member_order')}}"><li class="@if( $now_function == 'member_order' ) active @endif">我的訂單{{$now_function}}</li></a>
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
	background-color: orange!important;
}
</style>