var App = function() {
	// 1. The definition of variables
	
	var initVariable = function() {
		App.authCodeTimer = 0;
		App.authCodeTime = 180;
		App.autoJumpTimer = 0;
		App.autoJumpURL = "http://www.10000times.com/login";
		App.timer = 1000;
		App.success = 0;
	}
	
	// 2. The definition of functions
	
	// 2.1 To initialize main settings
	var handleInit = function() {

		// 2.1.1 To set the default active style of per page
		var url = window.location.href;
		var currentPos = url.indexOf('?');
		var currentURL = "";
		if ( -1 != currentPos ) {
			currentURL = url.substr(0, currentPos);
		} else {
			currentURL = url;
		}
		currentPos = currentURL.lastIndexOf('/info');
		if ( -1 != currentPos ) {
			currentURL = currentURL.substr(0, currentPos);
		}
	    var element = $('#content-left ul.nav a').filter(function() {
	        var menuPos = this.href.indexOf('?');
	        var menuURL = "";
	        if ( -1 != menuPos ) {
	        	menuURL = this.href.substr(0, menuPos);
	        } else {
	        	menuURL = this.href;
	        }
	        if ( $(this).find('span').hasClass('fa-home') ) {
	        	return menuURL == currentURL;
	        } else {
	        	return menuURL == currentURL;
	        	// return menuURL == currentURL || currentURL.indexOf(menuURL) != -1;
	        }
	    }).addClass('active').parent().parent();
	    if (element.hasClass('sidebar-menu-second')) {
	    	element.addClass('in').parent().addClass('active');
	    }

		// 2.1.2 To initialize the sidebar menu
		$('#sidebar-menu').metisMenu({
			// doubleTapToGo: true
			toggle: false
		});

		// compute the size of window
		computeWindowSize();
	}

	// 2.2 To handle full screen mode toggle
	var handleFullScreenMode = function() {
		// 2.2.1 To toggle full screen
		function toggleFullScreen() {
		    if (!document.fullscreenElement && !document.msFullscreenElement &&
		          !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
		        $('#admin_fullscreen').find('span.span-margin-left').text('关闭全屏');
		        if (document.documentElement.requestFullscreen) {
		        	document.documentElement.requestFullscreen();
		        } else if (document.documentElement.msRequestFullscreen) {
		        	document.documentElement.msRequestFullscreen();
		        } else if (document.documentElement.mozRequestFullScreen) {
		        	document.documentElement.mozRequestFullScreen();
		        } else if (document.documentElement.webkitRequestFullscreen) {
		        	document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
		        }
		    } else {
		    	$('#admin_fullscreen').find('span.span-margin-left').text('开启全屏');
	            if (document.cancelFullScreen) {
	            	document.cancelFullScreen();
	            } else if (document.msCancelFullScreen) {
	            	document.msCancelFullScreen();
	            } else if (document.mozCancelFullScreen) {
	            	document.mozCancelFullScreen();
	            } else if (document.webkitCancelFullScreen) {
	            	document.webkitCancelFullScreen();
	            }
	        }
        }

        //2.2.2 To bind the click event 
        $('#admin_fullscreen').on('click', function(event){
        	toggleFullScreen();
        })
	}

	// 2.3 To handle the size of the window
	var handleResizeWindow = function() {

		$(window).bind("load resize", function() {
			computeWindowSize();
		});

		// console.log(document.documentElement.clientWidth);
		// console.log(document.body.offsetWidth);
		// console.log(window.innerWidth);
		// console.log(document.body.scrollWidth);
		// console.log(window.screen.height);
		// console.log(window.screen.width);
		// console.log($(window).width());
		// console.log($(document).width());
		// console.log($(document.body).width());
		// console.log($(document.body).outerWidth(true));
		// console.log($('body').width());
		// console.log($('body').height());
		// console.log($(document).height());
	}

    //计算窗口大小
    var computeWindowSize = function() {
    	// 2.3.2 To set the height of the content left
		var viewPort = getViewPort();
		var documentLeftHeight = viewPort.height - $('#footer').outerHeight(true) - $('#header').outerHeight(true);
		// var documentLeftHeight = $('#footer').position().top - $('#header').outerHeight(true);
		var contentHeight = $('#content').outerHeight(true);
		var contentLeftHeight = Math.max(documentLeftHeight, contentHeight);
		$('#content-left .navbar').css('minHeight', contentLeftHeight + 'px');
		// 2.3.1 To set the width of the content right
		var documentWidth = viewPort.width;
		var contentLeftWidth = $('#content-left').outerWidth(true);
		var contentRightWidth = documentWidth - contentLeftWidth - 40;
		$('#content-right').css('width', contentRightWidth + 'px');
    }

    //获取视图窗口
	var getViewPort = function() {
	    a = 'client';
	    e = document.documentElement || document.body;
	    return {
	        width: e[a + 'Width'],
	        height: e[a + 'Height']
	    }
    }

    var handleShopsDropdown = function() {
    	$('#header_shops_dropdown').on('show.bs.dropdown', function(event){
    		console.log(window.location);
    		console.log(event);
    		$.getJSON('/admin/manage/shop/list', function(data,textStatus,jqXHR){
    			var url = window.location.href;
    			var pos = url.lastIndexOf('shopID=');
    			var currentUrl = "";
    			if ( -1 != pos ) {
    				currentUrl = url.substr(0, pos) + "shopID=";
    			} else {
    				currentUrl = url + "?shopID=";
    			}
    			if ( App.success == data.ret_code ) {
    				var successContent = "";
    				$.each(data.data, function(index, item) {
    					var href = currentUrl + encodeURIComponent(item.wy_shop_id);
						successContent += '<li><a href="'+href+'" data-shopname="'+item.wy_shop_name+'" data-shopid="'+item.wy_shop_id+'">'+item.wy_shop_name+'</a></li>';
						successContent += '<li class="divider"></li>';
					});
					$('#header_shops_dropdown ul').html(successContent);
    			} else {
    				var failContent = '<li>'+data.msg+'</li>';
    				$('#header_shops_dropdown ul').html(failContent);
    			}
    		})
    		.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				alert(errorThrown.message);
			});
    	});
    }

    // To handle the login
    var handleLogin = function() {
    	if ( "" != $('#login_user').val() ) {
    		$('#login_pwd').focus();
    	} else {
    		$('#login_user').focus();
    	}

    	$('#login').on('submit', function(event){
    		$(':submit').attr('disabled','disabled');
    		$(':submit').val('正在登陆中...');
    	});
    }

    var handleRegister = function() {
    	$('#register_protocol_modal').on('hidden.bs.modal', function(event){
    		event.preventDefault();
    		document.getElementById('agree_protocol').checked = true;
    	});

    	$('#register').on('submit', function(event){
    		$(':submit').attr('disabled','disabled');
    		$(':submit').val('正在登陆中...');
    	});
    }

    var initRegisterProtocolModal = function() {
    	$('#register_protocol_modal').on('hidden.bs.modal', function(event){
    		event.preventDefault();
    		document.getElementById('agree_protocol').checked = true;
    	});
    }

    // To handle the register
    var handleAuthCode = function() {
    	$('#get_auth_code').on('click', function(event){
    		event.preventDefault();
    		if ( 0 == App.authCodeTimer ) {
    			var span = $(this).find('span.badge');
	    		span.text(App.authCodeTime);
	    		$(this).find('span').removeClass('hidden');
	    		App.authCodeTimer = setInterval(handleAuthCodeTimer, App.timer);
	    		$(this).attr('disabled','true');
	    		//发送ajax获取验证码
	    		var userPhone = $('#user_phone').val();
	    		$.getJSON('/authcode', {user_phone : userPhone}, function(data,textStatus,jqXHR){
	    			if ( App.success != data.ret_code ) {
	    				if ( 0 != App.authCodeTimer ) {
				    		var button = $('#get_auth_code');
					    	var span = button.find('span.badge');
				    		clearInterval(App.authCodeTimer);
				    		App.authCodeTimer = 0;
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
    	if ( 0 != App.authCodeTimer ) {
    		var button = $('#get_auth_code');
	    	var span = button.find('span.badge');
	    	var time = parseInt(span.text());
	    	if ( 0 == time ) {
	    		clearInterval(App.authCodeTimer);
	    		App.authCodeTimer = 0;
	    		button.find('span').addClass('hidden');
	    		button.removeAttr('disabled');
	    	} else {
	    		span.text(time - 1);
	    	}
    	}
    }

    var handleAutoJump = function() {
    	if ( 0 == App.autoJumpTimer ) {
    		App.autoJumpTimer = setInterval(handleAutoJumpTimer, App.timer);
    	}
    }

    var handleAutoJumpTimer = function() {
    	var span = $('div.auto-jump a').find('span.badge');
    	var time = parseInt(span.text());
    	if ( 0 == time ) {
    		window.location = App.autoJumpURL;
    	} else {
    		span.text(time - 1);
    	}
    }

    var showAlertMessage = function(msg) {
		$('div#alert_msg').after('<div class="alert alert-danger alert-dismissible" role="alert"> \
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
			<span aria-hidden="true">&times;</span></button>'+msg+'</div>');
	}

	var handleForgotPassword = function() {
		$('div.forgot-password').on('click', 'button#auth_user', function(event){
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
			$.get('/forgotpassword', {user_phone : userPhone, user_auth_code : userAuthCode}, function(data,textStatus,jqXHR){
				if ( 'object' == typeof(data) && data.constructor == Object ) {
					showAlertMessage(data.msg);
				} else {
					$('#forgot_password').html(data);
					var li = $('#forgot_password_step ol').find('li:eq(0)');
					li.removeClass('step-active');
					li.addClass('step-done');
					li = $('#forgot_password_step ol').find('li:eq(1)');
					li.addClass('step-active');
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				showAlertMessage(errorThrown.message);
			});
		});

		$('div.forgot-password').on('click', 'button#reset_pwd', function(event){
			event.preventDefault();
			var userPwd = $('#user_pwd').val();
			var userPwdConfirmation = $('#user_pwd_confirmation').val();
			if ( "" == userPwd || "" == userPwdConfirmation ) {
				showAlertMessage("密码不能为空");
				return;
			}
			if ( userPwd != userPwdConfirmation ) {
				showAlertMessage("两次输入的密码不一致");
				return;
			}
			$.post('/forgotpassword', {user_pwd : userPwd, user_pwd_confirmation : userPwdConfirmation}, function(data,textStatus,jqXHR){
				if ( 'object' == typeof(data) && data.constructor == Object ) {
					showAlertMessage(data.msg);
				} else {
					$('#forgot_password').html(data);
					var li = $('#forgot_password_step ol').find('li:eq(1)');
					li.removeClass('step-active');
					li.addClass('step-done');
					li = $('#forgot_password_step ol').find('li:eq(2)');
					li.addClass('step-active');
					handleAutoJump();
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown.message);
				showAlertMessage(errorThrown.message);
			});
		});
	}

	var handleLoginValidate = function() {
		$('#login').validate({
			rules: {
				login_user: {
					required: true,
				},
				login_pwd: {
					required: true,
					minlength: 8,
					maxlength: 16
				}
			},
		});
	}

	// 3. The definition of return values
	
	return {
		// 3.1 The main function to initiate the theme
		init: function() {
			
			initVariable();
			
			// 3.1.1 Core handlers
			handleInit();
			// 3.1.2 The ui component handlers
			handleFullScreenMode();
			// 3.1.3 The size handlers
			handleResizeWindow();
			// 处理店铺切换
			handleShopsDropdown();
		},

		initTimePicker: function() {
			$('#shop_open_begin').timepicker({
				show2400 : true,
				timeFormat : 'H:i',
				className : 'form-control',
				step : 15,
				useSelect : true,
			});

			$('#shop_open_end').timepicker({
				show2400 : true,
				timeFormat : 'H:i',
				className : 'form-control',
				step : 15,
				useSelect : true,
			});

			$('#shop_delivery_begin').timepicker({
				show2400 : true,
				timeFormat : 'H:i',
				className : 'form-control',
				step : 15,
				useSelect : true,
			});

			$('#shop_delivery_end').timepicker({
				show2400 : true,
				timeFormat : 'H:i',
				className : 'form-control',
				step : 15,
				useSelect : true,
			});
		},

		initLogin: function() {
			handleLogin();
			// handleLoginValidate();
		},

		initRegister: function() {
			initVariable();
			handleRegister();
			handleAuthCode();
		},

		initAutoJump: function() {
			initVariable();
			handleAutoJump();
		},

		initForgotPassword: function() {
			initVariable();
			handleAuthCode();
			handleForgotPassword();
		},

		showTipMessage: function(msg) {
			$('#info_modal p').text(msg);
			$('#info_modal').modal();
		},
	}
}();