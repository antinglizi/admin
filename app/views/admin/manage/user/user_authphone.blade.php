<div class="row">
	<div class="col-lg-offset-1 col-lg-4">
		<strong>修改已验证手机</strong>
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
                <span>修改已验证手机</span>
            </li>
            <li>
                <div class="step-end">&nbsp;</div>
                <span>完成</span>
            </li>
        </ol>
    </div>
</div>
<form class="form-horizontal">
    <div class="form-group">
        <label for="user_phone" class="col-lg-4 control-label">手机号</label>
        <div class="col-lg-5">
            <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="手机号" value="{{ $user->wy_phone }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="user_auth_code" class="col-lg-4 control-label"><span class="required">*</span>验证码</label>
        <div class="col-lg-5">
            <div class="input-group">
                <input type="text" name="user_auth_code" id="user_auth_code" class="form-control" placeholder="验证码" required>
                <span class="input-group-btn">
                    <button type="button" id="get_auth_code" class="btn btn-success bg-green">获取验证码<span class="badge ml-10 hidden">180</span><span class="hidden">秒</span></button>
                </span>
            </div>
        </div>
    </div>
    <div>
        <button type="button" id="auth_user" class="btn btn-success center-block bg-green w150">确 定</button>
    </div>
</form>