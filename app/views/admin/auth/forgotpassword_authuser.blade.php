<div id="alert_msg"></div>
<form class="form-horizontal">
    <div class="form-group">
        <label for="user_phone" class="col-lg-2 control-label"><span class="required">*</span>手机号</label>
        <div class="col-lg-9">
            <input type="text" name="user_phone" id="user_phone" class="form-control" placeholder="手机号" required>
        </div>
    </div>
    <div class="form-group">
        <label for="user_auth_code" class="col-lg-2 control-label"><span class="required">*</span>验证码</label>
        <div class="col-lg-9">
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