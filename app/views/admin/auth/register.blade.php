@extends('layouts.admin.auth')

@section('title') @parent 注册 @stop

@section('container')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>欢迎注册文蚁商家管理系统</h1>    
            </div>
        </div>

        <div class="center-block register">
            <div id="alert_msg"></div>
            @include ('admin.template.alert')
            @if ( Session::has('success') )
            <div class="auto-jump center-block">
                <a href="{{ route('login') }}" class="btn btn-success center-block bg-green"><span class="badge">5</span><span>秒后自动跳转到登陆界面或者</span>直接单击进行登陆</a>
            </div>
            @endif
            {{ Form::open(array('method' => 'POST', 'route' => 'register', 'name' => 'register', 'id' =>'register', 'class' => 'form-horizontal')) }}
                <div class="form-group">
                    <label for="user_name" class="col-lg-2 control-label"><span class="required">*</span>用户名</label>
                    <div class="col-lg-9">
                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="用户名" value="{{ Input::old('user_name')}}" autofocus required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_phone" class="col-lg-2 control-label"><span class="required">*</span>手机号</label>
                    <div class="col-lg-9">
                        <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="手机号" value="{{ Input::old('user_phone')}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_auth_code" class="col-lg-2 control-label"><span class="required">*</span>验证码</label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <input type="text" name="user_auth_code" id="user_auth_code" class="form-control" placeholder="验证码" value="{{ Input::old('user_auth_code')}}" required>
                            <span class="input-group-btn">
                                <button type="button" id="get_auth_code" class="btn btn-success bg-green">获取验证码<span class="badge ml-10 hidden">180</span><span class="hidden">秒</span></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_pwd" class="col-lg-2 control-label"><span class="required">*</span>密码</label>
                    <div class="col-lg-9">
                        <input type="password" name="user_pwd" id="user_pwd" class="form-control" placeholder="密码" value="{{ Input::old('user_pwd')}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_pwd_confirmation" class="col-lg-2 control-label"><span class="required">*</span>确认密码</label>
                    <div class="col-lg-9">
                        <input type="password" name="user_pwd_confirmation" id="user_pwd_confirmation" class="form-control" placeholder="确认密码" value="{{ Input::old('user_pwd_confirmation')}}" required>
                    </div>
                </div>
                <div class="checkbox text-center">
                    <label>
                        <input type="checkbox" name="agree_protocol" id="agree_protocol" value="yes" checked>我已阅读并同意
                        <a data-toggle="modal" href="#register_protocol_modal">&lt;&lt;文蚁用户注册协议&gt;&gt;</a>
                    </label>
                </div>
                <ul class="mt-15">
                    <li>
                        <input type="submit" class="btn btn-success bg-green w150" value="注 册">
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="btn btn-success bg-green w150">登 陆</a>
                    </li>
                </ul>
            {{ Form::close() }}
        </div>
        
        @include ('admin.auth.registerprotocol')
    </div>

@stop

@section('afterScript')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {            
            App.initRegister();
            @if ( Session::has('success') )
                App.initAutoJump();
            @endif
        });
    </script>
@stop
