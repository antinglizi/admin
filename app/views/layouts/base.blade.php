<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="zh"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>
		{{--页面标题--}}
		@section('title')
		@show
	</title>

	{{-- 页面描述 --}}
	<meta name="description" content="@yield('description', '文蚁主站')">
    {{-- 页面关键词 --}}
    <meta name="keywords" content="@yield('keywords', '文蚁主站')" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 图标文件 --}}
    <link rel="icon" href="{{ asset("assets/img/favicon.ico") }}"/>
    {{-- 监测脚本 --}}
    {{ HTML::script('assets/js/vendor/modernizr-2.8.3.min.js') }}


	{{-- 页面内联样式之前 一些库样式 --}}
	@section('beforeStyle')
    @show

	{{-- 累加的页面内联样式 网站主样式 --}}
    @section('style')
    @show

    {{-- 页面内联样式之后 每个页面的个性样式 --}}
    @section('afterStyle')
    @show

    {{-- 页面之前的脚步 --}}
    @section('beforeScript')
    @show

</head>
<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

	@yield('body')

    {{-- 页面主体 --}}
    @section('script')
    @show

    {{-- 页面主体之后 --}}
    @section('afterScript')
    @show

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<!--        <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X','auto');ga('send','pageview');
    </script>-->

</body>
</html>

