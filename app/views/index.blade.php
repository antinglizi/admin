@extends('layouts.base')

@section('title') @parent 文蚁后台管理系统 @stop

@section('beforeStyle')
    @parent
    {{ HTML::style('assets/lib/bootstrap-3.3.2/css/bootstrap.min.css') }}
    {{ HTML::style('assets/css/vendor/font-awesome.min.css') }}
    {{ HTML::style('assets/css/main.css') }}
@stop

@section('body')

    <div>
        欢迎登陆文蚁后台管理系统：
        <a href="{{ route('login') }}" class="btn btn-primary">登陆管理系统</a>
    </div>


@stop

@section('afterScript')
    @parent
    {{ HTML::script('assets/js/plugins.js') }}
    {{ HTML::script('assets/js/vendor/jquery-1.11.2.min.js') }}
    {{ HTML::script('assets/lib/bootstrap-3.3.2/js/bootstrap.min.js') }}
    <script type="text/javascript">
        $(document).ready(function() {            
        });
    </script>
@stop
