<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';
require_once app_path().'/lib/api/ucpaas/Ucpaas.class.php';

class AuthController extends \BaseController {
	
	/**
	 * 显示注册页面
	 *
	 * @param [type] $[name] [description]
	 * @return [type] [description]
	 */
	public function getRegister()
	{
		return View::make('admin.auth.register');
	}

	/**
	 * 注册信息提交
	 *
	 * @param [type] $[name] [description]
	 * @return [type] [description]
	 */
	public function postRegister()
	{
		// 获取表单提交数据
		$data = Input::all();

		if ( !preg_match('/^[A-Za-z]\w{4,20}/', Input::get('user_name')) ) {
			return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-10063'));
		}

		// 建立验证规则
		$rules = array(
			'user_name' => 'unique:user,wy_user_name',
			'user_phone' => 'digits:11|unique:user,wy_phone',
			'user_pwd' => 'confirmed|max:16|alpha_dash',
			'user_auth_code' => 'numeric',
			'agree_protocol' => 'accepted',
		);

		// 验证规则
		$validator = Validator::make($data, $rules);
		if ( $validator->fails() ) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$userName = Input::get('user_name');
		$userPhone = Input::get('user_phone');
		$authCode = Input::get('user_auth_code');
		$userPwd = Input::get('user_pwd');

		// 验证码校验
		$registCode = RegistCode::find($userPhone);
		if ( empty($registCode) ) {
			$context = array(
				"errorCode"	=>	-10001,
				"userPhone"	=>	$userPhone,
			);
			Log::error(Lang::get('errormessages.-10001'), $context);
			return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-10001'));
		} else {
			$updateTime = strtotime($registCode->update_time);
			$nowTime = strtotime(Carbon::now());
			$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
			if ( $breakTime < AUTHCODE_TIME ) {
				if ( 0 != strcmp($authCode, $registCode->code) ) {
					$context = array(
						"errorCode"	=>	-10002,
						"authCode"	=>	$authCode,
						"registCode"	=>	$registCode->code,
					);
					Log::error(Lang::get('errormessages.-10002'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-10002'));
				}
			} else {
				$context = array(
					"errorCode"	=>	-10003,
					"authCode"	=>	$authCode,
					"registCode"	=>	$registCode->code,
					"breakTime"	=>	$breakTime,
				);
				Log::error(Lang::get('errormessages.-10002'), $context);
				return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-10003'));
			}
		}

		$user = new User;
		$user->wy_user_name = $userName;
		$user->wy_phone = $userPhone;
		$user->wy_pwd = Hash::make($userPwd);
		$user->wy_regist_time = Carbon::now();
		$user->wy_user_type = USER_TYPE_2; //商家
		$user->wy_state = USER_STATUS_2;	//目前设置为已激活状态
		$user->wy_login_status = LOGIN_STATUS_0; //离线状态
		
		$result = $user->save();
		if ( $result ) {
			$cookie = Cookie::make('login_user', $userPhone, REGISTER_COOKIE_TIME);
			return Redirect::back()->with('success', Lang::get('messages.10016'))->withCookie($cookie);
		} else {
			$context = array(
				"errorCode"	=>	-15000,
				"user"	=>	$user->toJson(),
			);
			Log::error(Lang::get('errormessages.-15000'), $context);
			return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15000'));
		}
	}

	/**
	 * 获取验证码
	 *
	 * @param [int] $[userPhone] [手机号]
	 * 
	 * @return [type] [description]
	 */
	public function getAuthCode()
	{
		$retCode = SUCCESS;
		$retMsg = "";

		do {

			$userPhone = Input::get('user_phone');
			if ( empty($userPhone) ) {
				$retCode = -10004;
				$retMsg = Lang::get('errormessages.-10004');
				break;
			}

			$accountsid = Config::get('ucpaas.accountsid');
			$token = Config::get('ucpaas.token');
			$option = array(
				'accountsid' => $accountsid,
				'token' => $token,
			);
			try {
				$ucpaas = new Ucpaas($option);
			} catch ( Exception $e ) {
				$retCode = -10005;
				$retMsg = Lang::get('errormessages.-10005');
				break;
			}

			// 生成验证码
			$authCode = $this->generateAuthCode();

			// 判断重复获取验证码
			$registCode = RegistCode::find($userPhone);
			if ( empty($registCode) ) {
				$registCode = new RegistCode;
				$registCode->user_phone_id = $userPhone;
				$registCode->code = $authCode;
				$registCode->update_time = Carbon::now();
				if ( !$registCode->save() ) {
					$retCode = -15001;
					$retMsg = Lang::get('errormessages.-15001');
					break;
				}
			} else {
				$updateTime = strtotime($registCode->update_time);
				$nowTime = strtotime(Carbon::now());
				$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
				if ( $breakTime < AUTHCODE_TIME ) {
					$authCode = $registCode->code;
				} else {
					$registCode->code = $authCode;
					$registCode->update_time = Carbon::now();
					if ( !$registCode->save() ) {
						$retCode = -15002;
						$retMsg = Lang::get('errormessages.-15002');
						break;
					}
				}
			}

			$appId = Config::get('ucpaas.appId');
			$templateId = Config::get('ucpaas.templateId');
			$isDebug = Config::get('app.debug');
			if ( $isDebug ) {
				$templateId = AUTHCODE_TEST;
				$param = $authCode;
			} else {
				$templateId = $templateId;
				$param = "$authCode";
				// $param = AUTHCODE_NAME . "," . $authCode . "," . AUTHCODE_TIME;
			}

			$result = $ucpaas->templateSMS($appId, $userPhone, $templateId, $param);
			Log::warning($result);
			$resultArray = json_decode($result, true);
			if ( isset($resultArray['resp']['respCode']) ) {
				if ( 0 != strcmp($resultArray['resp']['respCode'], AUTHCODE_SUCCESS) ) {
					$retCode = -10006;
					$retMsg = Lang::get('errormessages.-10006');
					break;
				}
			} else {
				$retCode = -10007;
				$retMsg = Lang::get('errormessages.-10007');
				break;
			}

		} while ( false );

		if ( SUCCESS != $retCode ) {
			$context = array(
				"errorCode"	=>	$retCode,
			);
			Log::error($retMsg, $context);
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}

	/**
	 * 生成验证码
	 *
	 * @return [int] [验证码]
	 */
	protected function generateAuthCode()
	{
		$originalCode = Config::get('ucpaas.originalcode');
		$codeLength = Config::get('ucpaas.length');
		$countDistrub = strlen($originalCode);

		$authCode = '';
		for ( $i = 0; $i < $codeLength; $i++ ) {
			$tempAuthCode = $originalCode[rand(0, $countDistrub-1)];
			$authCode .= $tempAuthCode;
		}

		return $authCode;
	}

	/**
	 * 显示登陆页面
	 *
	 * @return [Response] [响应]
	 */
	public function getLogin()
	{
		if ( Auth::check() ) {
			return Redirect::route('admin.index');
		} else {
			return View::make('admin.auth.login');
		}
	}

	/**
	 * 登陆信息提交
	 *
	 * @param [string] $[login_user] [用户名]
	 * @param [string] $[login_pwd] [用户名]
	 * 
	 * @return [Response] [响应]
	 */
	public function postLogin()
	{
		$userName = Input::get('login_user');
		$userPwd = Input::get('login_pwd');
		$userRemember = (bool)Input::get('login_remeber_me', false);
		
		if ( preg_match('/^1\d{10}$/i', $userName) ) {
			$credentials = array(
				'wy_phone' => $userName,
				'password' => $userPwd,
				'wy_user_type' => USER_TYPE_2,
				'wy_state' => USER_STATUS_2,
			);
		} elseif ( preg_match('/^[\w\.]+@\w+\.\w+$/i', $userName) ) {
			$credentials = array(
				'wy_email' => $userName,
				'password' => $userPwd,
				'wy_user_type' => USER_TYPE_2,
				'wy_state' => USER_STATUS_2,
			);
		} else {
			$credentials = array(
				'wy_user_name' => $userName,
				'password' => $userPwd,
				'wy_user_type' => USER_TYPE_2,
				'wy_state' => USER_STATUS_2,
			);
		}

		if ( Auth::attempt($credentials) ) {
			$user = Auth::user();
			$user->wy_last_login_time = Carbon::now();
			$user->wy_last_login_machine = Request::server('REMOTE_ADDR');
			$user->wy_login_status = LOGIN_STATUS_1;
			$user->save();
			if ( $userRemember ) {
				$cookie = Cookie::forever('login_user', $userName);
			} else {
				$cookie = Cookie::forget('login_user');
			}
			return Redirect::intended('admin')->withCookie($cookie);
		} else {
			 return Redirect::back()
			 			->withInput()
			 			->with('error', Lang::get('errormessages.-10000'));
		}
	}

	/**
	 * 登出处理
	 *
	 * @return [Response] [登陆界面]
	 */
	public function getLogout()
	{
		if ( Auth::check() ) {
			$user = Auth::user();
			$user->wy_login_status = LOGIN_STATUS_0;
			$user->save();
			Auth::logout();
		}
		return Redirect::route('login');
	}

	/**
	 * 显示忘记密码页面
	 *
	 * @return [Response] [忘记密码界面]
	 */
	public function getForgotPassword()
	{
		$retCode = SUCCESS;
		$retMsg = "";
		if ( Request::ajax() ) {
			do {

				$userPhone = Input::get('user_phone');
				$authCode = Input::get('user_auth_code');

				$user = User::where('wy_phone', $userPhone)->first();
				if ( empty($user) ) {
					$retCode = -10008;
					$retMsg = Lang::get('errormessages.-10008');
					$context = array(
						"errorCode"	=>	$retCode,
						"userPhone"	=>	$userPhone,
					);
					break;
				}

				$registCode = RegistCode::find($userPhone);
				if ( empty($registCode) ) {
					$retCode = -10009;
					$retMsg = Lang::get('errormessages.-10009');
					$context = array(
						"errorCode"	=>	$retCode,
						"userPhone"	=>	$userPhone,
					);
					break;
				} else {
					$updateTime = strtotime($registCode->update_time);
					$nowTime = strtotime(Carbon::now());
					$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
					if ( $breakTime < AUTHCODE_TIME ) {
						if ( 0 != strcmp($authCode, $registCode->code) ) {
							$retCode = -10010;
							$retMsg = Lang::get('errormessages.-10010');
							$context = array(
								"errorCode"	=>	$retCode,
								"authCode"	=>	$authCode,
								"registCode"	=>	$registCode->code,
							);
							break;
						}
					} else {
						$retCode = -10011;
						$retMsg = Lang::get('errormessages.-10011');
						$context = array(
							"errorCode"	=>	$retCode,
							"authCode"	=>	$authCode,
							"registCode"	=>	$registCode->code,
							"breakTime"	=>	$breakTime,
						);
						break;
					}
				}

				Session::flash('userPhone', $userPhone);
				Session::flash('authCode', $authCode);

				return View::make('admin.auth.forgotpassword_resetpwd');

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

		} else {
			if ( Auth::check() ) {
				return Redirect::route('admin.index');
			} else {
				return View::make('admin.auth.forgotpassword');
			}
		}
	}

	/**
	 * 处理忘记密码
	 *
	 * @return [Response] [成功与否界面]
	 */
	public function postForgotPassword()
	{
		$retCode = SUCCESS;
		$retMsg = "";

		do {
			// 获取表单数据
			$data = Input::all();

			// 建立验证规则
			$rules = array(
				'user_pwd' => 'confirmed|max:16|alpha_dash',
			);

			$validator = Validator::make($data, $rules);
			if ( $validator->fails() ) {
				$retCode = -10012;
				foreach ($validator->messages()->all() as $message) {
					$retMsg .= $message;
				}
				$context = array(
					"errorCode"	=>	$retCode,
					"data"	=>	$data,
				);
				break;
			}

			$userPhone = Session::get('userPhone');
			$user = User::where('wy_phone', $userPhone)->first();
			if ( empty($user) ) {
				$retCode = -10013;
				$retMsg = Lang::get('errormessages.-10013');
				$context = array(
					"errorCode"	=>	$retCode,
					"userPhone"	=>	$userPhone,
				);
				break;
			}

			$userPwd = Input::get('user_pwd');
			if ( Hash::check($userPwd, $user->wy_pwd) ) {
				$retCode = -10014;
				$retMsg = Lang::get('errormessages.-10014');
				break;
			}

			$authCode = Session::get('authCode');
			$registCode = RegistCode::find($userPhone);
			if ( empty($registCode) ) {
				$retCode = -10015;
				$retMsg = Lang::get('errormessages.-10015');
				$context = array(
					"errorCode"	=>	$retCode,
					"userPhone"	=>	$userPhone,
				);
				break;
			} else {
				$updateTime = strtotime($registCode->update_time);
				$nowTime = strtotime(Carbon::now());
				$breakTime = ceil(($nowTime-$updateTime) / AUTHCODE_MIN);
				if ( $breakTime < AUTHCODE_TIME ) {
					if ( 0 != strcmp($authCode, $registCode->code) ) {
						$retCode = -10016;
						$retMsg = Lang::get('errormessages.-10016');
						$context = array(
							"errorCode"	=>	$retCode,
							"authCode"	=>	$authCode,
							"registCode"	=>	$registCode->code,
						);
						break;
					}
				} else {
					$retCode = -10017;
					$retMsg = Lang::get('errormessages.-10017');
					$context = array(
						"errorCode"	=>	$retCode,
						"authCode"	=>	$authCode,
						"registCode"	=>	$registCode->code,
						"breakTime"	=>	$breakTime,
					);
					break;
				}
			}

			$user->wy_pwd = Hash::make($userPwd);
			if ( $user->save() ) {
				Session::flush();
				return View::make('admin.auth.forgotpassword_finish')->withSuccess(Lang::get('messages.10017'));
			} else {
				$retCode = -15003;
				$retMsg = Lang::get('errormessages.-15003');
				break;
			}

		} while( false );

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
	 * 获取用户的所有店铺
	 *
	 * @return [Array] [店铺信息]
	 */
	public static function getShops()
	{
		$userID = Auth::id();
		$shops = Shop::where('wy_shopkeeper', $userID)
					->get(array('wy_shop_id', 'wy_shop_name', 'wy_audit_state', 'wy_province_id', 'wy_city_id', 'wy_district_id'));
		return $shops;
	}

	/**
	 * 检测该店铺是否为登陆用户并且为通过店铺
	 *
	 * @param [int] $[shopID] [店铺的ID]
	 * @param [bool] $[isGoBack] [是否返回]
	 * 
	 * @return 成功 [Array] [店铺信息] 失败 返回错误页面或者刷新当前页面
	 */
	public static function checkShop($shopID, $isGoBack = false)
	{
		$userID = Auth::id();
		$shop = Shop::where('wy_shop_id', $shopID)->where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)
					->first(array('wy_shop_id', 'wy_shop_name', 'wy_audit_state', 'wy_province_id', 'wy_city_id', 'wy_district_id'));
		
		if ( empty($shop) ) {
			$context = array(
				"errorCode"	=>	-10018,
				"userID"	=>	$userID,
				"shopID"	=>	$shopID,
			);
			Log::error(Lang::get('errormessages.-10018'), $context);
			if ( Request::ajax() ) {
				$sendMsgArray = array(
					"ret_code" => -10018,
					"msg" => Lang::get('errormessages.-10018'),
				);
				return Response::json($sendMsgArray);
			} else {
				if ( $isGoBack ) {
					return Redirect::back()->with('error', Lang::get('errormessages.-10018'));
				} else {
					return $shop;
				}
			}
		} else {
			$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
		}
		return $shop;
	}

	/**
	 * 暂时没有使用
	 * 检测该店铺是否为登陆用户，不区分是否通过
	 *
	 * @param [int] $[shopID] [店铺的ID]
	 * @param [bool] $[isGoBack] [是否返回]
	 * 
	 * @return 成功 [Array] [店铺信息] 失败 返回错误页面或者刷新当前页面
	 */
	public static function checkAllShopByID($shopID, $isGoBack = false)
	{
		$userID = Auth::id();
		$shop = Shop::where('wy_shop_id', $shopID)->where('wy_shopkeeper', $userID)
					->first(array('wy_shop_id', 'wy_shop_name', 'wy_audit_state', 'wy_province_id', 'wy_city_id', 'wy_district_id'));
		
		if ( empty($shop) ) {
			$context = array(
				"errorCode"	=>	-10019,
				"userID"	=>	$userID,
				"shopID"	=>	$shopID,
			);
			Log::error(Lang::get('errormessages.-10019'), $context);
			if ( Request::ajax() ) {
				$sendMsgArray = array(
					"ret_code" => -10019,
					"msg" => Lang::get('errormessages.-10019'),
				);
				return Response::json($sendMsgArray);
			} else {
				if ( $isGoBack ) {
					return Redirect::back()->with('error', Lang::get('errormessages.-10019'));
				} else {
					return $shop;
				}
			}
		} else {
			$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
		}
		return $shop;
	}

	/**
	 * 检测该店铺是否为登陆用户
	 *
	 * @param [bool] $[isGoBack] [是否返回]
	 * 
	 * @return 成功 [Array] [店铺信息] 失败 返回错误页面或者刷新当前页面
	 */
	public static function checkAllShops($isGoBack = false)
	{
		$userID = Auth::id();
		$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)
					->get(array('wy_shop_id', 'wy_shop_name', 'wy_audit_state', 'wy_province_id', 'wy_city_id', 'wy_district_id'));
		
		if ( empty($shops->toArray()) ) {
			$context = array(
				"errorCode"	=>	-10020,
				"userID"	=>	$userID,
			);
			Log::error(Lang::get('errormessages.-10020'), $context);
			if ( Request::ajax() ) {
				$sendMsgArray = array(
					"ret_code" => -10020,
					"msg" => Lang::get('errormessages.-10020'),
				);
				return Response::json($sendMsgArray);
			} else {
				if ( $isGoBack ) {
					return Redirect::back()->with('error', Lang::get('errormessages.-10020'));
				} else {
					return $shops;
				}
			}
		} else {
			foreach ($shops as $index => $shop) {
				$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
			}
		}
		return $shops;
	}

	/**
	 * 检测URL中shopID的参数
	 *
	 * @param [int] $[shopID] [店铺的ID]
	 * @param [bool] $[isGoBack] [是否返回]
	 * 
	 * @return 成功 [Array] [店铺信息] 失败 返回错误页面或者刷新当前页面
	 */
	public static function checkUserURL($isGoBack = false)
	{
		$shop = array();
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$shop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
			} else {
				$shop = AuthController::checkShop($shopID, $isGoBack);
			}
		}

		return $shop;
	}

	//并不是所有的都是goback处理，还有显示当前页面
	public static function getShop($shopID)
	{
		$userID = Auth::id();
		$shop = Shop::where('wy_shop_id', $shopID)->where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->first(array('wy_shop_id', 'wy_region_id', 'wy_shop_name'));
		if ( empty($shop) ) {
			$context = array(
				"errorCode"	=>	-10021,
				"userID"	=>	$userID,
				"shopID"	=>	$shopID,
			);
			Log::error(Lang::get('errormessages.-10021'), $context);
			if ( Request::ajax() ) {
				$sendMsgArray = array(
					"ret_code" => -10021,
					"msg" => Lang::get('errormessages.-10021'),
				);
				return Response::json($sendMsgArray);
			} else {
				return Redirect::back()->with('error', Lang::get('errormessages.-10021'));
			}
		} else {
			$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
		}
		return $shop;
	}
}