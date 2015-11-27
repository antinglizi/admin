@extends('layouts.admin.auth')

@section('title') @parent 登陆 @stop

@section('container')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>欢迎登陆文蚁商家管理系统</h1>    
            </div>
        </div>

        <div class="center-block login">
            {{ Form::open(array('method' => 'POST', 'route' => 'login', 'name' => 'login', 'id' =>'login', 'class' => 'form-horizontal')) }}
                @include ('admin.template.alert')
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                        <input type="text" name="login_user" id="login_user" class="form-control" placeholder="手机号/用户名" value="{{ empty(Input::old('login_user')) ? Cookie::get('login_user') : Input::old('login_user') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                        <input type="password" name="login_pwd" id="login_pwd" class="form-control"  placeholder="登陆密码" required>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        @if ( Cookie::has('login_user') )
                        <input type="checkbox" name="login_remeber_me" id="login_remeber_me" value="true" checked> 记住用户名
                        @else
                        <input type="checkbox" name="login_remeber_me" id="login_remeber_me" value="true"> 记住用户名
                        @endif
                    </label>
                </div>
                <div>
                    <input type="submit" class="btn btn-success center-block bg-green w150" value="登 陆">
                </div>
                <div class="form-group mt-15">
                    <div class="entries">
                        <a href="{{ route('register') }}">免费注册</a>
                        <a class="ml-10" href="{{ route('forgotpassword') }}">忘记密码？</a>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>

@stop

@section('afterScript')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {            
            App.initLogin();
        });
    </script>
@stop
