@extends('layouts.base')

@section('title') @parent 文蚁商家管理系统— @stop

@section('beforeStyle')
	@parent
	{{ HTML::style('assets/lib/bootstrap-3.3.2/css/bootstrap.min.css') }}
	{{ HTML::style('assets/lib/jquery-timepicker-1.6.11/css/jquery.timepicker.css') }}
	{{ HTML::style('assets/css/vendor/font-awesome.min.css') }}
@stop

@section('style')
	@parent
	{{ HTML::style('assets/css/main.css') }}
@stop

@section('body')

	{{-- 网站头的样式 --}}
	@include('navbars.headbar', compact('headerShop','disableChange'))

	<div id="content" class="clearfix">
		{{-- 网站侧边栏样式 --}}
		@include('navbars.sidebar', compact('headerShop'))
		@yield('container')
    </div>

    {{-- 网站页脚的样式 --}}
    @include('layouts.footer')

@stop

@section('script')
	@parent
	{{ HTML::script('assets/js/plugins.js') }}
	{{ HTML::script('assets/js/vendor/jquery-1.11.2.min.js') }}
	{{ HTML::script('assets/lib/bootstrap-3.3.2/js/bootstrap.min.js') }}
	{{ HTML::script('assets/lib/jquery-timepicker-1.6.11/js/jquery.timepicker.min.js') }}
	{{ HTML::script('assets/lib/metisMenu-1.1.3/metisMenu.min.js') }}
	{{ HTML::script('assets/js/vendor/bootstrap-hover-dropdown.min.js') }}
    {{ HTML::script('assets/js/vendor/js.cookie-1.5.1.js') }}
    {{ HTML::script('assets/js/app.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            App.init();
        });
    </script>
@stop
