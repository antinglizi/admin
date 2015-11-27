<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class AdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				//获取公告信息
				$announces = array();
				$this->getAnnounces($announces);

				$totalTurnover = 0;
				$totalOrderCount = 0;
				$newOrderCount = 0;
				$this->getAllTradeInfo($totalTurnover, $totalOrderCount, $newOrderCount);
				return View::make('admin.index', compact('headerShop', 'totalTurnover', 'totalOrderCount', 'newOrderCount', 'announces'));
			} else {
				//获取公告信息
				$announces = array();
				$this->getAnnounces($announces);

				$headerShop = AuthController::checkShop($shopID);
				$totalTurnover = 0;
				$totalOrderCount = 0;
				$newOrderCount = 0;
				//显示当前店铺信息
				//缺少对headerShop为空的判断，这是程序出现问题了，应该统一跳回到原来的界面
				//应该统一到一个界面，提示错误，倒计时跳转到上一个正确的界面即可
				if ( !empty($headerShop) ) {
					$this->getSingleTradeInfo($shopID, $totalTurnover, $totalOrderCount, $newOrderCount);
					return View::make('admin.index', compact('headerShop', 'totalTurnover', 'totalOrderCount', 'newOrderCount', 'announces'));
				} else {
					return View::make('admin.index', compact('totalTurnover', 'totalOrderCount', 'newOrderCount', 'announces'));
				}
			}
		} else {
			$shops = AuthController::getShops();
			if ( empty($shops->toArray()) ) {
				return View::make('admin.intro.useflow');
			} else {
				$isPassed = false;
				foreach ($shops as $index => $shop) {
					if ( SHOP_AUDIT_STATUS_4 == $shop->wy_audit_state ) {
						$headerShop = $shop;
						$headerShop->wy_shop_id = base64_encode($headerShop->wy_shop_id);
						$isPassed = true;
						break;
					}
				}
				if ( $isPassed ) {
					//获取公告信息
					$announces = array();
					$this->getAnnounces($announces);
					//返回整体数据
					$totalTurnover = 0;
					$totalOrderCount = 0;
					$newOrderCount = 0;
					$this->getAllTradeInfo($totalTurnover, $totalOrderCount, $newOrderCount);
					return View::make('admin.index', compact('headerShop', 'totalTurnover', 'totalOrderCount', 'newOrderCount', 'announces'));
				} else {
					return View::make('admin.template.tip')->withMessage(Lang::get('messages.10014'));
				}
			}
		}
	}

	/*
	 * 获取当前用户的所有生鲜交易信息
	 */
	protected function getAllTradeInfo(&$totalTurnover, &$totalOrderCount, &$newOrderCount)
	{
		$userID = Auth::id();
		$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->get(array('wy_shop_id'));
		$shopIDs = array();
		foreach ($shops as $index => $shop) {
			array_push($shopIDs, $shop->wy_shop_id);
		}

		if ( !empty($shopIDs) ) {
			$date = Carbon::now()->toDateString();
			$totalTurnover = MainOrder::whereIn('wy_shop_id', $shopIDs)->where('wy_order_state', ORDER_STATE_4)->whereRaw('date(wy_generate_time) = ?', array($date))->sum('wy_actual_money');
			$totalTurnover = round($totalTurnover, 2);
			$totalOrderCount = MainOrder::whereIn('wy_shop_id', $shopIDs)->whereRaw('date(wy_generate_time) = ?', array($date))->count();
			$newOrderCount = MainOrder::whereIn('wy_shop_id', $shopIDs)->where('wy_order_state', ORDER_STATE_1)->whereRaw('date(wy_generate_time) = ?', array($date))->count();
		}
	}

	/*
	 * 获取当前店铺的生鲜交易信息
	 */
	protected function getSingleTradeInfo($shopID, &$totalTurnover, &$totalOrderCount, &$newOrderCount)
	{
		$date = Carbon::now()->toDateString();
		$totalTurnover = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', ORDER_STATE_4)->whereRaw('date(wy_generate_time) = ?', array($date))->sum('wy_actual_money');
		$totalTurnover = round($totalTurnover, 2);
		$totalOrderCount = MainOrder::where('wy_shop_id', $shopID)->whereRaw('date(wy_generate_time) = ?', array($date))->count();
		$newOrderCount = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', ORDER_STATE_1)->whereRaw('date(wy_generate_time) = ?', array($date))->count();
	}

	/*
	 * 获取公告信息
	 */
	protected function getAnnounces(&$announces)
	{
		$announces = Announce::orderBy('wy_announce_time','desc')->paginate(PERPAGE_COUNT_20);
		foreach ($announces as $index => $announce) {
			$announceTime = $announce->wy_announce_time;
			$announce->wy_announce_date = Carbon::parse($announceTime)->toDateString();
		}
	}
}
