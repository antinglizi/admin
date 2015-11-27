@extends('layouts.base')

@section('title') @parent 文蚁商家管理系统— @stop

@section('beforeStyle')
	@parent
	{{ HTML::style('assets/lib/bootstrap-3.3.2/css/bootstrap.min.css') }}
	{{ HTML::style('assets/css/vendor/font-awesome.min.css') }}
@stop

@section('style')
	@parent
	{{ HTML::style('assets/css/main.css') }}
@stop

@section('body')

	{{-- 网站头的样式 --}}
	{{-- @include('navbars.headbar') --}}

	<div id="content" class="clearfix">
		{{-- 网站侧边栏样式 --}}
		{{-- @include('navbars.sidebar') --}}
		@yield('container')
    </div>

    {{-- 网站页脚的样式 --}}
    {{-- @include('layouts.footer') --}}

@stop

@section('script')
	@parent
	{{ HTML::script('assets/js/plugins.js') }}
	{{ HTML::script('assets/js/vendor/jquery-1.11.2.min.js') }}
	{{ HTML::script('assets/lib/bootstrap-3.3.2/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/vendor/js.cookie-1.5.1.js') }}
    {{ HTML::script('assets/lib/jquery-validation-1.13.1/jquery.validate.min.js') }}
    {{ HTML::script('assets/js/app.js') }}
@stop
