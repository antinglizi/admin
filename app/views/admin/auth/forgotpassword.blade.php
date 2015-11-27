@extends('layouts.admin.auth')

@section('title') @parent 忘记密码 @stop

@section('container')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>文蚁商家管理系统——找回密码</h1>    
            </div>
        </div>
        <div class="row">
            <div id="forgot_password_step" class="center-block forgot-password-step">
                <ol>
                    <li class="step-active">
                        <div class="step-normal">1</div>
                        <span>验证身份</span>
                    </li>
                    <li>
                        <div class="step-normal">2</div>
                        <span>重置密码</span>
                    </li>
                    <li>
                        <div class="step-end">&nbsp;</div>
                        <span>完成</span>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div id="forgot_password" class="center-block forgot-password">
                @include ('admin.auth.forgotpassword_authuser')
            </div>
        </div>
    </div>

@stop

@section('afterScript')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {            
            App.initForgotPassword();
        });
    </script>
@stop
