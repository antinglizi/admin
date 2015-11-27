/*
 * 系统账号管理
 */

var Account = function() {

	var initVariable = function() {
		Account.success = 0;
		Account.authCodeTimer = 0;
		Account.authCodeTime = 180;
		Account.timer = 1000;
	};

	var showAlertMessage = function(msg) {
		$('div#alert_msg').after('<div class="alert alert-danger alert-dismissible" role="alert"> \
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
			<span aria-hidden="true">&times;</span></button>'+msg+'</div>');
	};

	var handleUserInfoTab = function() {
		$('#user_info').on('shown.bs.tab', function(event){
			console.log(event);
			var userInfoType = $(event.target).data('index');
			Cookies.set('user_info_type', userInfoType, { expires: 1, path: ''});
		});

		var userInfoType = Cookies.get('user_info_type');
		if ( undefined === userInfoType ) {
			$('#user_info a:first').tab('show');
		} else {
			$('#user_info li:eq('+userInfoType+') a').tab('show');
		}
	}

	var handleChangePassword = function() {
		$('#user_change_password').on('click', function(event){
			event.preventDefault();
			$('#user').load('/admin/manage/user/change/password', function(responseText,textStatus,jqXHR){
				if ( textStatus == "error" ) {
					showAlertMessage("加载失败，请进行刷新操作");
				}
			});
		});

		$('#user').on('click', 'button#change_pwd', function(event){
			event.preventDefault();
			var userOldPwd = $('#user_old_pwd').val();
			var userPwd = $('#user_pwd').val();
			var userPwdConfirmation = $('#user_pwd_confirmation').val();
			if ( "" === userOldPwd || "" === userPwd || "" === userPwdConfirmation ) {
				showAlertMessage("密码不能为空");
				return;
			}
			if ( userPwd != userPwdConfirmation ) {
				showAlertMessage("两次输入的密码不一致");
				return;
			}
			if ( userOldPwd == userPwd || userOldPwd == userPwdConfirmation ) {
				showAlertMessage("新旧密码不能相同");
				return;
			}
			$.post('/admin/manage/user/change/password', {user_old_pwd : userOldPwd, user_pwd : userPwd, user_pwd_confirmation : userPwdConfirmation}, function(data,textStatus,jqXHR){
				if ( 'object' == typeof(data) && data.constructor == Object ) {
					showAlertMessage(data.msg);
				} else {
					$('#user').html(data);
					App.initAutoJump();
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				showAlertMessage(errorThrown.message);
			});
		});
	}

	var handleChangePhone = function() {
		$('#user_change_phone').on('click', function(event){
			event.preventDefault();
			$('#user').load('/admin/manage/user/auth/phone', function(responseText,textStatus,jqXHR){
				if ( textStatus == "error" ) {
					showAlertMessage("加载失败，请进行刷新操作");
				}
			});
		});

		$('#user').on('click', 'button#auth_user', function(event){
			event.preventDefault();
			var userPhone = $('#user_phone').val();
			var userAuthCode = $('#user_auth_code').val();
			if ( "" == userPhone ) {
				showAlertMessage("手机号不能为空");
				return;
			}
			if ( "" == userAuthCode ) {
				showAlertMessage("验证码不能为空");
				return;
			}

			$.get('/admin/manage/user/change/phone', {user_phone : userPhone, user_auth_code : userAuthCode}, function(data,textStatus,jqXHR){
				if ( 'object' == typeof(data) && data.constructor == Object ) {
					showAlertMessage(data.msg);
				} else {
					$('#user').html(data);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				showAlertMessage(errorThrown.message);
			});

		});

		$('#user').on('click', 'button#change_phone', function(event){
			event.preventDefault();
			var userPhone = $('#user_phone').val();
			var userAuthCode = $('#user_auth_code').val();
			if ( "" == userPhone ) {
				showAlertMessage("手机号不能为空");
				return;
			}
			if ( "" == userAuthCode ) {
				showAlertMessage("验证码不能为空");
				return;
			}

			$.post('/admin/manage/user/change/phone', {user_phone : userPhone, user_auth_code : userAuthCode}, function(data,textStatus,jqXHR){
				if ( 'object' == typeof(data) && data.constructor == Object ) {
					showAlertMessage(data.msg);
				} else {
					$('#user').html(data);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				showAlertMessage(errorThrown.message);
			});

		});
	}

	var handleAuthCode = function() {
		$('#user').on('click', 'button#get_auth_code', function(event){
			event.preventDefault();
	   		if ( 0 == Account.authCodeTimer ) {
    			var span = $(this).find('span.badge');
	    		span.text(Account.authCodeTime);
	    		$(this).find('span').removeClass('hidden');
	    		Account.authCodeTimer = setInterval(handleAuthCodeTimer, Account.timer);
	    		$(this).attr('disabled','true');
	    		//发送ajax获取验证码
	    		var userPhone = $('#user_phone').val();
	    		$.getJSON('/authcode', {user_phone : userPhone}, function(data,textStatus,jqXHR){
	    			if ( Account.success != data.ret_code ) {
	    				if ( 0 != Account.authCodeTimer ) {
				    		var button = $('#get_auth_code');
					    	var span = button.find('span.badge');
				    		clearInterval(Account.authCodeTimer);
				    		Account.authCodeTimer = 0;
				    		button.find('span').addClass('hidden');
				    		button.removeAttr('disabled');
				    	}
				    	showAlertMessage(data.msg);
	    			}
	    		})
	    		.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus);
					console.log(errorThrown.message);
					showAlertMessage(errorThrown.message);
				});
    		}
		});
	}

	var handleAuthCodeTimer = function() {
    	if ( 0 != Account.authCodeTimer ) {
    		var button = $('#get_auth_code');
	    	var span = button.find('span.badge');
	    	var time = parseInt(span.text());
	    	if ( 0 == time ) {
	    		clearInterval(Account.authCodeTimer);
	    		Account.authCodeTimer = 0;
	    		button.find('span').addClass('hidden');
	    		button.removeAttr('disabled');
	    	} else {
	    		span.text(time - 1);
	    	}
    	}
    }

	return {
		initUser: function() {

			initVariable();

			handleUserInfoTab();

			handleChangePassword();

			handleChangePhone();

			handleAuthCode();
		},
	}
}();