<div id="content-left">
    <div class="navbar navbar-default sidebar-menu" role="navigation">
        <ul class="nav" id="sidebar-menu">
            <li class="sidebar-menu-first">
                @if ( isset($headerShop) )
                <a href="{{ route('admin.index', array("shopID" => $headerShop->wy_shop_id)) }}"><span class="fa fa-home span-margin-right"></span>首页</a>
                @else
                <a href="{{ route('admin.index') }}"><span class="fa fa-home span-margin-right"></span>首页</a>    
                @endif
            </li>
            <li class="sidebar-menu-first" id="freshbiz">
                <a href="javascript:void(0);"><span class="fa fa-building span-margin-right"></span>生鲜业务<span class="fa arrow fa-lg"></span></a>
                <ul class="nav sidebar-menu-second">
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('order.list', array("shopID" => $headerShop->wy_shop_id)) }}">订单管理</a>
                        @else
                        <a href="{{ route('order.list') }}">订单管理</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('goods.add', array("shopID" => $headerShop->wy_shop_id)) }}">发布菜品</a>
                        @else
                        <a href="{{ route('goods.add') }}">发布菜品</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('goods.list.selling', array("shopID" => $headerShop->wy_shop_id)) }}">出售中菜品</a>
                        @else
                        <a href="{{ route('goods.list.selling') }}">出售中菜品</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('goods.list.unsell', array("shopID" => $headerShop->wy_shop_id)) }}">已下架菜品</a>
                        @else
                        <a href="{{ route('goods.list.unsell') }}">已下架菜品</a>
                        @endif
                    </li>
                </ul>
            </li>
            <li class="sidebar-menu-first" id="market">
                <a href="javascript:void(0);"><span class="fa fa-gift span-margin-right"></span>营销中心<span class="fa arrow fa-lg"></span></a>
                <ul class="nav sidebar-menu-second">
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('activity.list', array("shopID" => $headerShop->wy_shop_id)) }}">活动报名</a>
                        @else
                        <a href="{{ route('activity.list') }}">活动报名</a>
                        @endif
                    </li>
                   {{--  <li>
                        <a href="javascript:void(0);">外卖兑换券</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">外卖满就送</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">外卖限时卖</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">外卖满就返</a>
                    </li> --}}
                </ul>
            </li>
            <li class="sidebar-menu-first" id="report">
                <a href="javascript:void(0);"><span class="fa fa-bar-chart span-margin-right"></span>统计报表<span class="fa arrow fa-lg"></span></a>
                <ul class="nav sidebar-menu-second">
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('trade.list', array("shopID" => $headerShop->wy_shop_id)) }}">交易报表</a>
                        @else
                        <a href="{{ route('trade.list') }}">交易报表</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('finance.list', array("shopID" => $headerShop->wy_shop_id)) }}">财务报表</a>
                        @else
                        <a href="{{ route('finance.list') }}">财务报表</a>
                        @endif
                    </li>
                </ul>
            </li>
            <li class="sidebar-menu-first" id="manage">
                <a href="javascript:void(0);"><span class="fa fa-medium span-margin-right"></span>管理<span class="fa arrow fa-lg"></span></a>
                <ul class="nav sidebar-menu-second">
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('shop.list', array("shopID" => $headerShop->wy_shop_id)) }}">店铺管理</a>
                        @else
                        <a href="{{ route('shop.list') }}">店铺管理</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('rate.list', array("shopID" => $headerShop->wy_shop_id)) }}">评价管理</a>
                        @else
                        <a href="{{ route('rate.list') }}">评价管理</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('user.list', array("shopID" => $headerShop->wy_shop_id)) }}">账号管理</a>
                        @else
                        <a href="{{ route('user.list') }}">账号管理</a>
                        @endif
                    </li>
                    <li>
                        @if ( isset($headerShop) )
                        <a href="{{ route('protocol.list', array("shopID" => $headerShop->wy_shop_id)) }}">协议签署</a>
                        @else
                        <a href="{{ route('protocol.list') }}">协议签署</a>
                        @endif
                    </li>
                </ul>
            </li>
           {{--  <li class="sidebar-menu-first" id="setting">
                <a href="javascript:void(0);"><span class="fa fa-cogs span-margin-right"></span>设置<span class="fa arrow fa-lg"></span></a>
                <ul class="nav sidebar-menu-second">
                    <li>
                        <a href="javascript:void(0);">打印设置</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">接单方式</a>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </div>
</div>