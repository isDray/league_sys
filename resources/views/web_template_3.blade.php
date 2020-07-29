<!--
    享愛加盟版型3
-->

<!-- rwd 購物車滑動區塊 -->
<div class='rwd_cart_slide '>
    
    <ul id='cart_list'>                    
        
        <li class="cart_list_area">
            @if( isset( $Carts) )
                @php
                    $cartTotal = 0;
                @endphp
                
                @foreach( $Carts as $Cartk => $Cart)
                    @php
                        $cartTotal += $Cart['subTotal'];
                    @endphp
                    
                    <table class='cart_table' width='100%'>
                        <tr>
                            <td>
                            <table>
                                <tr >
                                    <td class='tableimg cart_img_box' colspan='2'>
                                        <img src="https://***REMOVED***.com/***REMOVED***/{{$Cart['thumbnail']}}">
                                        <span>{{$Cart['name']}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td width='30%'>${{$Cart['goodsPrice']}}×{{$Cart['num']}}={{$Cart['subTotal']}}</td>
                                    <td width='30%' align='right'><span class='btn bg-maroon btn-flat margin rmbtn' goods_id="{{$Cart['id']}}">刪除</span></td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>
                @endforeach
                <p>小計:{{$cartTotal}}</p>
            @endif
        </li>
            

    </ul>
    
    <div class="cart_btn_area">
        <a href="{{url('/cart')}}" class='btn colorbtn btn-flat margin to_checkout'>去結帳</a>
    </div>

</div>
<!-- /rwd 購物車滑動區塊 -->

<!-- rwd menu滑動區塊 -->
<div class='rwd_menu_slide m_only'>

    <div class='rwd_menu_slide_head'>
    </div>    

@foreach( $categorys as $categoryk => $category)
    <li class='rwd_menu_li'>
        
        @if($category['child'])<span class='rwd_menu_li_more fa fa-fw fa-angle-down' toggole_num="{{$category['rcat']}}"></span>@else <span  class='rwd_menu_li_more fa fa-fw fa-minus' ></span> @endif <a class='rwd_nav_tree_name rwd_child_tree rwd_menua' href="{{url('/category/'.$category['rcat'])}}">{{ $category['rcat_name'] }}</a>
        
        @if($category['child'])
            <ul class="rwd_menu_ul rwd_menu_ul{{$category['rcat']}}">
            @foreach( $category['child'] as $childk => $childv )
                <a href="{{url('/category/'.$childv['ccat'])}}"><li><i class='fa fa-fw fa-angle-right'></i>{{ $childv['ccat_name'] }}</li></a>
            @endforeach
            </ul>
        @endif
    </li>
@endforeach 
</div>    
<!-- /rwd menu滑動區塊 -->

<div class='container-fluid' id='main_content'>
    <!-- 電腦版menu -->
    <div class='row' id='top_box'>
        
        <!-- 漢堡按鈕 -->
        <div class="col-md-0 col-sm-4 col-xs-2 text-align-center m_only">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <!-- /漢堡按鈕 -->
        
        <!-- logo -->
        <div id="logo_box" class="col-md-2 col-md-offset-5 col-sm-4 col-xs-8 text-align-center">
        @if( !empty($LeagueData['logo']) )
            <a href="{{url('/')}}">
                <img src="{{url('/league_logo/'.$LeagueData['logo'])}}" class='menulogo only_m' alt="{{$LeagueData['store_name']}}logo">
            </a>
        @endif
        </div>
        <!-- /logo -->
        
        <!-- 手機版購物車按鈕 -->
        <div class="col-md-0 col-sm-4 col-xs-2 text-align-center m_only">
            <div class="cart_box cart_toggle">
                <div class='num_in_cart'>{{$num_in_cart}}</div>
            	<a><i class='fa fa-fw fa-shopping-cart'></i></a>
            </div>
        </div>
        <!-- /手機板購物車按鈕 -->

        <div id='tool_box' class='col-md-5 col-sm-12 col-xs-12' >
            
            <div class='col-md-6 col-sm-12 col-xs-12' id='search_box'>
            <form action="{{url('/search')}}" method="POST" >

                {{ csrf_field() }}
                <div class="input-group margin" >
                    
                    <input type="text" class="form-control" placeholder="找商品..." name='keyword'>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-fw fa-search"></i></button>
                    </span>
                </div>

            </form>
            </div>

            <div id='member_box' class='col-md-6 col-sm-12 col-xs-12'>
            	<a class='btn btn-flat margin tool_btn'>
                <i class="fa fa-fw fa-user-plus"></i>加入會員
                </a>
            	<a class='btn btn-flat margin tool_btn'>
                <i class="fa fa-fw fa-sign-in"></i>會員登入
                </a>        	
<!-- num_in_cart -->
                <a class='btn btn-flat margin tool_btn cart_btn cart_toggle'>
                    <i class="fa fa-fw fa-shopping-cart"></i>購物車

                    <div class='num_in_cart'>{{$num_in_cart}}</div>
                </a>                 

            </div>
        </div>
        
        <div id='menu_box' class='col-md-12 col-sm-12 col-xs-12'>
        @foreach( $categorys as $categoryk => $category)
            <li class='menu_li'>
                <a class='web_nav_tree_name child_tree menua' href="{{url('/category/'.$category['rcat'])}}">{{ $category['rcat_name'] }}</a>
                @if($category['child'])
                <ul class="menu_ul">
                @foreach( $category['child'] as $childk => $childv )
                    <a href="{{url('/category/'.$childv['ccat'])}}"><li>{{ $childv['ccat_name'] }}</li></a>
                @endforeach
                </ul>
                @endif
            </li>
        @endforeach 
        </div>


    </div>
    <!-- /電腦版 menu -->
    @if( $centent_type == 1 )
    <div class='col-md-2 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-12 over_m' id='content_left'>
        @yield('content_left')
    </div>
    @endif

    <div class='col-md-8 @if($centent_type == 2) col-md-offset-2 @else col-md-offset-0 @endif col-sm-12 col-xs-12' id="content_right">
        @yield('content_right')

        @if( isset($viewed_goods) && count($viewed_goods) > 0)

            @include('viewed_goods')
    
        @endif
    </div>
  

    
    <!-- footer -->
    <div class='row' id='footer_box'>
        <div class='col-md-5 col-md-offset-1 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_left'>
            
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_left_center">

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>如何購買</h4>
                        <li> <a href="{{url('/article/10')}}">購買流程</a> </li>
                        <li> <a href="{{url('/article/15')}}">配送說明</a></li>
                        <li> <a href="{{url('/article/17')}}">支付方式說明</a></li>
                        <li> <a href="{{url('/article/21')}}">退換貨原則</a></li>
                    </ul>
                </div>

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <ul class='footer_list'><h4>常見問題</h4>
                        <li> <a href="{{url('/article/9')}}">發票說明</a></li>
                        <li> <a href="{{url('/article/33')}}">情趣用品清洗及收納注意事項</a></li>
                        <li> <a href="{{url('/article/48')}}">常見Q&A</a></li>
                        <li> <a href="{{url('/article/51')}}">產品保固說明</a></li>
                    </ul>
                </div>                

                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <!--<ul class='footer_list'><h4>會員中心</h4>
                        <li> <a href="{{url('/article/47')}}">批發合作說明</a></li>
                    </ul> -->
                </div>
            </div>

        </div>

        <div class='col-md-5 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-12' id='footer_right'>
            <div class='col-md-12 col-sm-12 col-xs-12' id="footer_right_center">
                <div class="col-md-12 col-sm-12 col-xs-12"> 
                    <img lazysrc="https://***REMOVED***.com/***REMOVED***/ecs_static/img/18.png" style='float:right' class="lazyload" alt="禁止未滿18歲進行購買">                    
                    <p id='footer_des'>
                        {{$LeagueData['store_name']}}採全站情趣用品購物滿千免運，「包裝隱密」保護您的購物隱私，本購物網站支援－貨到付款－超商取貨－宅配到府，讓您購物輕鬆無負擔！本站提供數千種情趣用品批發、零售， 並有滿額贈品選擇，歡迎參觀選購
                    </p>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" id='contact_box'>
                    <p><i class="fa fa-fw fa-headphones"></i>客服專線：(04)874-0413</p>
                    <p><i class="fa fa-fw fa-mobile-phone"></i>客服手機：0915-588-683</p>
                    <p><i class="fa fa-fw fa-group"></i>客服Line ID： @***REMOVED***</p>
                    <p><i class="fa fa-fw fa-envelope-o"></i>聯絡信箱：mykk97956@yahoo.com.tw</p>
                </div>

            </div>
        </div>

        <div class='col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 text-center' id='footer_bottom' >
            <img lazysrc="https://***REMOVED***.com/***REMOVED***/ecs_static/img/165_1160.png" style="max-width:100%;" class="lazyload" alt="反詐騙宣導,接到要求轉帳分期等相關操作的電話皆為詐騙 , 歡迎來電(04)874-0413求證">
        </div>

    </div>
    <!-- /footer -->

    <!-- 手機用 bottom tool -->
    <div id='rwd_tool_bar' class='m_only '>
        <div class='col-md-3 col-sm-3 col-xs-3'>
            <a href="{{url('/')}}">
            <i class="fa fa-fw fa-home"></i>
            <br>
            首頁
            </a>
        </div>
        <div class='col-md-3 col-sm-3 col-xs-3 rwd_search_btn'>
            <a>
            <i class="fa fa-fw fa-search"></i>
            <br>
            搜尋
            </a>        
        </div>
        <div class='col-md-3 col-sm-3 col-xs-3'>
            <a href="{{url('/join_member')}}">
            <i class="fa fa-fw fa-user-plus"></i>
            <br>
            加入會員
            </a>          
        </div>
        <div class='col-md-3 col-sm-3 col-xs-3'>
            @if( session()->has('member_login') && session('member_id') == true && session()->has('member_id') )
            <a href="{{url('/member_index')}}">
            <i class="fa fa-fw fa-dashboard"></i>
            <br>
            會員中心
            </a>        
            @else
            <a href="{{url('/member_login')}}">
            <i class="fa fa-fw fa-sign-in"></i>
            <br>
            登入
            </a>
            @endif    
        </div>            
    </div>    
    <div class="rwd_search_box m_only">
        <form action="{{url('/search')}}" method="POST" >

            {{ csrf_field() }}
            <div class="input-group margin" >
                    
                <input type="text" class="form-control" placeholder="找商品..." name='keyword'>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-fw fa-search"></i></button>
                </span>
            </div>

        </form>   
    </div>

</div>


