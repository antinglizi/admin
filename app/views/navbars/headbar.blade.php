<!-- Fixed Navbar -->
<div id="header">
    <div class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('index') }}">
                    <img src="{{ asset("assets/img/logo.png") }}" alt="主页">
                </a>
                <p class="navbar-text"><strong>猪乐乐--后台管理系统</strong></p>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <div id="header-current-shop" class="form-control navbar-btn w200" data-shopname="" data-shopid="">{{ isset($headerShop) ? $headerShop->wy_shop_name : '' }}</div>
                </li>
                <li id="header_shops_dropdown" class="dropdown mr-100">
                    @if ( isset($disableChange) )
                    <button id="header_get_shops" type="button" class="dropdown-toggle btn btn-success navbar-btn bg-green" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>切换店铺<span class="caret"></span></button>
                    @else
                    <button id="header_get_shops" type="button" class="dropdown-toggle btn btn-success navbar-btn bg-green" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">切换店铺<span class="caret"></span></button>
                    @endif
                    <ul class="dropdown-menu scroll" role="menu">
                    </ul>
                </li>
                <li class="dropdown">
                    @if ( is_null(Auth::user()) )
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" role="button" aria-expanded="false">用户名
                    <span class="fa fa-angle-down"></span>
                    </a>
                    @elseif ( !empty(Auth::user()->wy_nick_name) )
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" role="button" aria-expanded="false">{{ Auth::user()->wy_nick_name }}
                    <span class="fa fa-angle-down"></span>
                    </a>
                    @else
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" role="button" aria-expanded="false">{{ Auth::user()->wy_user_name }}
                    <span class="fa fa-angle-down"></span>
                    </a>
                    @endif
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('lock') }}">
                            <span class="fa fa-lock span-margin-right"></span>锁屏
                            </a>
                        </li>
                        <li><a href="{{ route('logout') }}">
                            <span class="fa fa-power-off span-margin-right"></span>退出
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a id="admin_fullscreen" href="javascript:void(0);"><span class="fa fa-arrows-alt"></span><span class="span-margin-left">开启全屏</span></a></li>
            </ul>
        </div>
    </div>
</div>
<!-- End Fixed Navbar -->