<div class="row">
	<div class="col-lg-offset-1 col-lg-4">
		<strong>修改登录密码</strong>
	</div>
</div>
<form class="form-horizontal">
	<div class="form-group">
		<label for="user_old_pwd" class="col-lg-4 control-label"><span class="required">*</span>旧的密码</label>
		<div class="col-lg-5">
		    <input type="password" name="user_old_pwd" id="user_old_pwd" class="form-control" placeholder="旧的密码" required>
		</div>
	</div>
	<div class="form-group">
		<label for="user_pwd" class="col-lg-4 control-label"><span class="required">*</span>新的密码</label>
		<div class="col-lg-5">
		    <input type="password" name="user_pwd" id="user_pwd" class="form-control" placeholder="新的密码" required>
		</div>
	</div>
	<div class="form-group">
		<label for="user_pwd_confirmation" class="col-lg-4 control-label"><span class="required">*</span>确认新的密码</label>
		<div class="col-lg-5">
		    <input type="password" name="user_pwd_confirmation" id="user_pwd_confirmation" class="form-control" placeholder="确认新的密码" required>
		</div>
	</div>
	<div class="form-group">
        <button type="button" id="change_pwd" class="btn btn-success center-block bg-green w150">修 改</button>
    </div>
</form>