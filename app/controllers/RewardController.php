<?php

class RewardController extends \BaseController {

	/**
	 * 显示满就减列表
	 *
	 * @return Response
	 */
	public function getList()
	{
		//
		$shopID = base64_decode(Input::get('shopID'));

		$disableChange = DEFAULT_0;
		$headerShop = AuthController::checkUserURL();
		
		if ( empty($headerShop) ) {
			return View::make('admin.market.reward.reward', compact('disableChange'));
		} else {
			return View::make('admin.market.reward.reward', compact('headerShop', 'disableChange'));
		}
	}

}
