@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 账号管理  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
		    <div class="col-lg-12">
		        <h1>账号管理</h1>
		        <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>管理</li>
					<li class="active">账号管理</li>
				</ol>
		    </div>
		</div>
		
		@include ('admin.template.alert')

		<div class="row">
		    <div id="user" class="col-lg-12">
	            <ul id="user_info" class="nav nav-pills" role="tablist">
		    		<li class="active" role="presentation"><a href="#user_basic_info" aria-controls="user_basic_info" role="tab" data-toggle="pill" data-index="0">基本信息</a></li>
		    		<li role="presentation"><a href="#user_security" aria-controls="user_security" role="tab" data-toggle="pill" data-index="1">账号安全</a></li>
		    	</ul>
		    	<div class="tab-content">
					<div class="tab-pane fade panel panel-default active in" id="user_basic_info">
						{{ Form::open(array('method' => 'POST', 'route' => 'user.modify', 'name' => 'user_basic_info_modify', 'id' =>'user_basic_info_modify', 'class' => 'form-horizontal' )) }}
							<input type="hidden" name="user_info_type" id="user_info_type" value="0">
							<div class="panel-body">
								<div class="form-group">
									<label for="user_name" class="col-lg-3 control-label">用户名</label>
									<div class="col-lg-5">
										<input type="text" name="user_name" id="user_name" class="form-control" value="{{ $user->wy_user_name }}" placeholder="用户名" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="user_nickname" class="col-lg-3 control-label"></span>用户昵称</label>
									<div class="col-lg-5">
										<input name="user_nickname" id="user_nickname" class="form-control" value="{{ $user->wy_nick_name }}" placeholder="用户昵称（最多为64个字符）"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="user_email" class="col-lg-3 control-label"></span>用户邮箱</label>
									<div class="col-lg-5">
										<input name="user_email" id="user_email" class="form-control" value="{{ $user->wy_email }}" placeholder="用户邮箱"></input>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<input type="submit" class="btn btn-success bg-green center-block w150" value="修 改">
							</div>
						{{ Form::close() }}
					</div>
					<div class="tab-pane fade panel panel-default" id="user_security">
						<div class="panel-body">
							<ul class="list-group user-security">
								<li class="list-group-item">
									<div class="col1">
										<span class="tick"></span>
										<strong>登陆密码</strong>
									</div>
									<div class="col2">
										<span>如果密码出现异常，请尽快修改密码已保护账户安全</span>
									</div>
									<div class="col3">
										<a id="user_change_password" href="javascript:void(0);">修改</a>
									</div>
								</li>
								<li class="list-group-item">
									<div class="col1">
										<span class="tick"></span>
										<strong>手机验证</strong>
									</div>
									<div class="col2">
										<span>您验证的手机：<strong>{{ substr_replace($user->wy_phone, '****', 3, 4) }}</strong> 若已丢失或停用，请立即更换</span>
									</div>
									<div class="col3">
										<a id="user_change_phone" href="javascript:void(0);">修改</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>

@stop

@section('afterScript')
	@parent
	{{ HTML::script('assets/js/account.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            Account.initUser();
        });
    </script>
@stop