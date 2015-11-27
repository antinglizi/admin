<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class ActivityController extends \BaseController {

	/**
	 * 显示活动信息
	 *
	 * @return Response
	 */
	public function getList()
	{
		//
		$activities = array();
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				return View::make('admin.market.activity.activity', compact('headerShop','activities'))->withAll(Lang::get('messages.10015'));
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( empty($headerShop) ) {
					$context = array(
						"errorCode"	=>	-10022,
						"userID"	=>	Auth::id(),
						"shopID"	=>	$shopID,
					);
					Log::error(Lang::get('errormessages.-10022'), $context);
					return View::make('admin.market.activity.activity', compact('headerShop','activities'))->withError(Lang::get('errormessages.-10022'));
				} else {
					$date = Carbon::now()->toDateString();
					$activities = Activity::where('wy_activity_start', '<=', $date)->where('wy_activity_end', '>=', $date)->get();
					$shopActivities = ShopActivity::where('wy_shop_id', $shopID)->where('wy_enable', ACTIVITY_ENABLE_1)->get(array('wy_activity_id'));
					$shopActivityIDArray = array();
					foreach ($shopActivities as $index => $shopActivitity) {
						array_push($shopActivityIDArray, $shopActivitity->wy_activity_id);
					}
					foreach ($activities as $index => $activity) {
						$activityID = $activity->wy_activity_id;
						if ( in_array($activityID, $shopActivityIDArray) ) {
							$activity->wy_activity_status = ACTIVITY_ENABLE_1;
							$activity->wy_activity_status_name = ACTIVITY_ENABLE_1_NAME;
						} else {
							$activity->wy_activity_status = ACTIVITY_ENABLE_0;
							$activity->wy_activity_status_name = ACTIVITY_ENABLE_0_NAME;
						}
					}
					return View::make('admin.market.activity.activity', compact('headerShop','activities'));
				}
			}
		} else {
			$context = array(
				"errorCode"	=>	-10023,
				"userID"	=>	Auth::id(),
			);
			Log::error(Lang::get('errormessages.-10023'), $context);
			return View::make('admin.market.activity.activity', compact('activities'))->withError(Lang::get('errormessages.-10023'));
		}
	}

	/**
	 * 参加活动
	 */
	public function postParticipate()
	{
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);

		$retCode = SUCCESS;
		$retMsg = "";

		$activityID = Input::get('activity_id');
		$activity = Activity::where('wy_activity_id', $activityID)->first();
		//活动对应表中需要增加时间控制
		$shopActivitity = ShopActivity::where('wy_shop_id', $shopID)->where('wy_activity_id', $activityID)->first();
		if ( empty($shopActivitity) ) {
			$shopActivitity = new ShopActivity;
			$shopActivitity->wy_shop_id = $shopID;
			$shopActivitity->wy_activity_id = $activityID;
			$shopActivitity->wy_enable = ACTIVITY_ENABLE_1;
			if ( !empty($activity) ) {
				$shopActivitity->wy_money = $activity->wy_activity_cutmoney;
			}
			$result = $shopActivitity->save();
			if ( $result ) {
				$retMsg = Lang::get('messages.10018');
			} else {
				$retCode = -15004;
				$retMsg = Lang::get('errormessages.-15004');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID"	=>	Auth::id(),
					"shopID"	=>	$shopID,
					"activityID"=>	$activityID,	
				);
				Log::error($retMsg, $context);
			}
		} else {
			if ( !empty($activity) ) {
				$result = ShopActivity::where('wy_shop_id', $shopID)->where('wy_activity_id', $activityID)->update(array('wy_enable' => ACTIVITY_ENABLE_1, 'wy_money' => $activity->wy_activity_cutmoney));
			} else {
				$result = ShopActivity::where('wy_shop_id', $shopID)->where('wy_activity_id', $activityID)->update(array('wy_enable' => ACTIVITY_ENABLE_1));
			}
			if ( $result ) {
				$retMsg = Lang::get('messages.10018');
			} else {
				$retCode = -15005;
				$retMsg = Lang::get('errormessages.-15005');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID"	=>	Auth::id(),
					"shopID"	=>	$shopID,
					"activityID"=>	$activityID,	
				);
				Log::error($retMsg, $context);
			}
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}

	/**
	 * 取消活动
	 */
	public function postCancel()
	{
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);

		$retCode = SUCCESS;
		$retMsg = "";

		$activityID = Input::get('activity_id');
		$shopActivitity = ShopActivity::where('wy_shop_id', $shopID)->where('wy_activity_id', $activityID)->first();
		if ( empty($shopActivitity) ) {
			$retCode = -10024;
			$retMsg = Lang::get('errormessages.-10024');
			$context = array(
				"errorCode"	=>	$retCode,
				"userID"	=>	Auth::id(),
				"shopID"	=>	$shopID,
				"activityID"=>	$activityID,	
			);
			Log::error($retMsg, $context);
		} else {
			$result = ShopActivity::where('wy_shop_id', $shopID)->where('wy_activity_id', $activityID)->update(array('wy_enable' => ACTIVITY_ENABLE_0));
			if ( $result ) {
				$retMsg = Lang::get('messages.10019');
			} else {
				$retCode = -15006;
				$retMsg = Lang::get('errormessages.-15006');
				$context = array(
					"errorCode"	=>	$retCode,
					"userID"	=>	Auth::id(),
					"shopID"	=>	$shopID,
					"activityID"=>	$activityID,	
				);
				Log::error($retMsg, $context);
			}
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);
	}
}
