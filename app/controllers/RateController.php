<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class RateController extends \BaseController {

	/**
	 * 显示评价信息
	 * GET /rate
	 *
	 * @return Response
	 */
	public function getList()
	{
		//
		$rates = array();
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				return View::make('admin.manage.rate.rate', compact('headerShop','rates'))->withAll(Lang::get('messages.10015'));
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( empty($headerShop) ) {
					return View::make('admin.manage.rate.rate', compact('headerShop','rates'))->withError(Lang::get('errormessages.-10045'));
				} else {
					$shop = Shop::where('wy_shop_id', $shopID)->first(array('wy_comprehensive_evaluation','wy_service_score','wy_goods_score'));					
					$rates = Rate::where('wy_shop_id', $shopID)->orderBy('wy_time','desc')
					    ->paginate(PERPAGE_COUNT_10, array('wy_comment_id','wy_main_order_id','wy_user_phone','wy_time','wy_content'));
					foreach ($rates as $index => $rate) {
						$rate->wy_comment_id = base64_encode($rate->wy_comment_id);
						$mainOrder = MainOrder::where('wy_main_order_id', $rate->wy_main_order_id)->first(array('wy_order_number'));
						if ( !empty($mainOrder) ) {
							$rate->wy_order_number = $mainOrder->wy_order_number;
						} else {
							$rate->wy_order_number = Lang::get('errormessages.-10058');
						}
						$rate->wy_main_order_id = base64_encode($rate->wy_main_order_id);
					}
					return View::make('admin.manage.rate.rate', compact('headerShop','shop','rates'));
				}
			}
		} else {
			return View::make('admin.manage.rate.rate', compact('rates'))->withError(Lang::get('errormessages.-10045'));
		}
	}

	/**
	 * 解释评价信息
	 *
	 * @return  Response
	 */
	public function postExplain()
	{
		$commentID = base64_decode(Input::get('comment_id'));
		$shopID = base64_decode(Input::get('shop_id'));
		$mainOrderID = base64_decode(Input::get('main_order_id'));
		$explain = Input::get('explain');

		$rate = Rate::where('wy_comment_id', $commentID)->where('wy_shop_id', $shopID)->where('wy_main_order_id', $mainOrderID)->first();
	}
}