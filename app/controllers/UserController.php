<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getList()
	{
		//
		$user = Auth::user();
		
		$disableChange = DEFAULT_0;
		$headerShop = AuthController::checkUserURL();
		if ( empty($headerShop) ) {
			return View::make('admin.manage.user.user', compact('user', 'disableChange'));
		} else {
			return View::make('admin.manage.user.user', compact('headerShop', 'user', 'disableChange'));
		}
	}

	/**
	 * 修改账户基本信息
	 *
	 * @return Response
	 */
	public function postModify()
	{
		$data = Input::all();
		$userInfoType = Input::get('user_info_type');
		$user = Auth::user();

		switch ($userInfoType) {
			case USER_INFO_0:
			{
				$rules = array(
					'user_nickname' => 'max:64',
					'user_email' => 'email',
				);

				$validator = Validator::make($data, $rules);
				if ( $validator->fails() ) {
					return Redirect::back()->withInput()->withErrors($validator);
				}

				$userNickName = Input::get('user_nickname');
				$userEmail = Input::get('user_email');
				
				$user->wy_nick_name = $userNickName;
				$user->wy_email = $userEmail;

				$result = $user->save();
				if ( $result ) {
					return Redirect::back()->with('success', Lang::get('messages.10021'));
				} else {
					$context = array(
						"errorCode"	=>	-15008,
						"userID"	=> $user->wy_user_id,	
						"data"	=> $data,
					);
					Log::error(Lang::get('errormessages.-15008'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15008'));
				}
				break;
			}
			case USER_INFO_1:
			{
				break;
			}
			default:
			{
				$context = array(
					"errorCode"	=>	-10027,
					"userID"	=>	$user->wy_user_id,
					"data"		=>	$data,
				);
				Log::error(Lang::get('errormessages.-10027'), $context);
				return Redirect::back()->with('error', Lang::get('errormessages.-10027'));
				break;
			}	
		}
	}

	/**
	 * 显示修改密码界面
	 * 
	 */
	public function getChangePassword()
	{
		return View::make('admin.manage.user.user_changepassword');
	}

	/** 
	 * 修改密码
	 * 
	 */
	public function postChangePassword()
	{
		$data = Input::all();
		$user = Auth::user();

		$retCode = SUCCESS;
		$retMsg = "";

		do {

			$rules = array(
				'user_pwd' => 'confirmed|max:16|alpha_dash',
			);

			$validator = Validator::make($data, $rules);
			if ( $validator->fails() ) {
				$retCode = -10028;
				foreach ($validator->messages()->all() as $message) {
					var_dump($message);
					$retMsg .= $message;
				}
				break;
			}

			$userPwd = Input::get('user_pwd');
			if ( Hash::check($userPwd, $user->wy_pwd) ) {
				$retCode = -10025;
				$retMsg = Lang::get('errormessages.-10025');
				break;
			}

			$userOldPwd = Input::get('user_old_pwd');
			if ( !Hash::check($userOldPwd, $user->wy_pwd) ) {
				$retCode = -10026;
				$retMsg = Lang::get('errormessages.-10026');
				break;
			}

			$user->wy_pwd = Hash::make($userPwd);
			if ( $user->save() ) {
				Session::flush();
				return View::make('admin.manage.user.user_changepasswordfinish')->withSuccess(Lang::get('messages.10020'));
			} else {
				$retCode = -15007;
				$retMsg = Lang::get('errormessages.-15007');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID"	=> $user->wy_user_id,
				);
				Log::error($retMsg, $context);
				break;
			}

		} while ( false );

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}

	/** 
	 * 显示验证手机号界面
	 * 
	 */
	public function getAuthPhone()
	{
		$user = Auth::user();
		return View::make('admin.manage.user.user_authphone', compact('user'));
	}

	/** 
	 * 显示修改手机号界面
	 * 
	 */
	public function getChangePhone()
	{
		$retCode = SUCCESS;
		$retMsg = "";

		do {
			$user = Auth::user();

			$userPhone = Input::get('user_phone');
			$authCode = Input::get('user_auth_code');

			if ( $userPhone != $user->wy_phone ) {
				$retCode = -10029;
				$retMsg = Lang::get('errormessages.-10029');
				$context = array(
					"errorCode" =>	$retCode,
					"userID" =>	$user->wy_user_id,
					"userPhone" =>	$userPhone,
				);
				break;
			}

			$registCode = RegistCode::find($userPhone);
			if ( empty($registCode) ) {
				$retCode = -10030;
				$retMsg = Lang::get('errormessages.-10030');
				$context = array(
					"errorCode" => $retCode,
					"userID" => $user->wy_user_id,
					"userPhone" => $userPhone,
				);
				break;
			} else {
				$updateTime = strtotime($registCode->update_time);
				$nowTime = strtotime(Carbon::now());
				$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
				if ( $breakTime < AUTHCODE_TIME ) {
					if ( 0 != strcmp($authCode, $registCode->code) ) {
						$retCode = -10031;
						$retMsg = Lang::get('errormessages.-10031');
						$context = array(
							"errorCode"	=>	$retCode,
							"userID" =>	$user->wy_user_id,
							"authCode"	=>	$authCode,
							"registCode"	=>	$registCode->code,
						);
						break;
					}
				} else {
					$retCode = -10032;
					$retMsg = Lang::get('errormessages.-10032');
					$context = array(
						"errorCode"	=>	$retCode,
						"userID" => $user->wy_user_id,
						"authCode"	=>	$authCode,
						"registCode"	=>	$registCode->code,
						"breakTime"	=>	$breakTime,
					);
					break;
				}
			}

			return View::make('admin.manage.user.user_changephone');

		} while ( false );

		if ( isset($context) ) {
			Log::error($retMsg, $context);
		} else {
			Log::error($retMsg);
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}

	/** 
	 * 修改手机号
	 * 
	 */
	public function postChangePhone()
	{
		$retCode = SUCCESS;
		$retMsg = "";

		do {
			$user = Auth::user();

			$userPhone = Input::get('user_phone');
			$authCode = Input::get('user_auth_code');

			if ( !preg_match('/^1\d{10}$/i', $userPhone) ) {
				$retCode = -10033;
				$retMsg = Lang::get('errormessages.-10033');
				$context = array(
					"errorCode" => $retCode,
					"userID" => $user->wy_user_id,
					"userPhone" => $userPhone,
				);
				break;
			}

			if ( $userPhone == $user->wy_phone ) {
				$retCode = -10034;
				$retMsg = Lang::get('errormessages.-10034');
				$context = array(
					"errorCode" => $retCode,
					"userID" => $user->wy_user_id,
					"userPhone" => $userPhone,
					"oldUserPhone" => $user->wy_phone,
				);
				break;
			}

			$userTemp = User::where('wy_phone', $userPhone)->first(array('wy_phone'));
			if ( !empty($userTemp) ) {
				$retCode = -10035;
				$retMsg = Lang::get('errormessages.-10035');
				$context = array(
					"errorCode" => $retCode,
					"userID" => $user->wy_user_id,
					"userPhone" => $userPhone,
				);
				break;
			}

			$registCode = RegistCode::find($userPhone);
			if ( empty($registCode) ) {
				$retCode = -10038;
				$retMsg = Lang::get('errormessages.-10038');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID" => $user->wy_user_id,
					"userPhone"	=>	$userPhone,
				);
				break;
			} else {
				$updateTime = strtotime($registCode->update_time);
				$nowTime = strtotime(Carbon::now());
				$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
				if ( $breakTime < AUTHCODE_TIME ) {
					if ( 0 != strcmp($authCode, $registCode->code) ) {
						$retCode = -10036;
						$retMsg = Lang::get('errormessages.-10036');
						$context = array(
							"errorCode"	=>	$retCode,
							"userID" => $user->wy_user_id,
							"authCode"	=>	$authCode,
							"registCode" =>	$registCode->code,
						);
						break;
					}
				} else {
					$retCode = -10037;
					$retMsg = Lang::get('errormessages.-10037');
					$context = array(
						"errorCode"	=>	$retCode,
						"userID" => $user->wy_user_id,
						"authCode"	=>	$authCode,
						"registCode"	=>	$registCode->code,
						"breakTime"	=>	$breakTime,
					);
					break;
				}
			}

			$user->wy_phone = $userPhone;
			if ( $user->save() ) {
				return View::make('admin.manage.user.user_changephonefinish')->withSuccess(Lang::get('messages.10022'));
			} else {
				$retCode = -15009;
				$retMsg = Lang::get('errormessages.-15009');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID" => $user->wy_user_id,
					"userPhone"	=>	$userPhone,
				);
				break;
			}
			
		} while ( false );

		if ( isset($context) ) {
			Log::error($retMsg, $context);
		} else {
			Log::error($retMsg);
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}
}