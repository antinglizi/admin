<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class FinanceController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getList()
	{
		$headerShop = AuthController::checkUserURL();
		if ( empty($headerShop) ) {
			return View::make('admin.report.finance.finance');
		} else {
			return View::make('admin.report.finance.finance', compact('headerShop'));
		}
	}

	/**
	 * 获取财务数据报表
	 *
	 */
	public function getFinanceReport()
	{
		$retCode = SUCCESS;
		$retMsg = "";
		$shopID = base64_decode(Input::get('shop_id'));
		$dates = array();
		$statisticsDatas = array();
		$conditions = "";
		$params = array();

		if ( Request::ajax() ) {
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$userID = Auth::id();
				$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->get(array('wy_shop_id'));
				$shopIDs = array();
				foreach ($shops as $index => $shop) {
					array_push($shopIDs, $shop->wy_shop_id);
				}

				if ( !empty($shopIDs) ) {
					$this->getGenerateConditions($conditions, $params, $dates);
					if ( empty($conditions) && empty($params) ) {
						$retCode = -10067;
						$retMsg = Lang::get('errormessages.-10067');
					} else {
						$statisticsDatas = MainOrder::whereIn('wy_shop_id', $shopIDs)->where('wy_order_state', ORDER_STATE_4)->whereRaw($conditions, $params)
							->select(DB::raw('date(wy_generate_time) as generate_date, count(*) as amount, sum(wy_consumption_money) as consume_money, sum(wy_actual_money) as actual_money'))->get()->toArray();
					}
				} else {
					$retCode = -10066;
					$retMsg = Lang::get('errormessages.-10066');
					$context = array(
						"errorCode" => $retCode,
						"userID" => Auth::id(),
						"shopID" => $shopID,
						"data" => Input::all(),
					);
					Log::error($retMsg, $context);
				}
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( !empty($headerShop) ) {
					$this->getGenerateConditions($conditions, $params, $dates);
					if ( empty($conditions) && empty($params) ) {
						$retCode = -10067;
						$retMsg = Lang::get('errormessages.-10067');
					} else {
						$statisticsDatas = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', ORDER_STATE_4)->whereRaw($conditions, $params)
							->select(DB::raw('date(wy_generate_time) as generate_date, count(*) as amount, sum(wy_consumption_money) as consume_money, sum(wy_actual_money) as actual_money'))->get()->toArray();
					}
				} else {
					$retCode = -10065;
					$retMsg = Lang::get('errormessages.-10065');
					$context = array(
						"errorCode" => $retCode,
						"userID" => Auth::id(),
						"shopID" => $shopID,
						"data" => Input::all(),
					);
					Log::error($retMsg, $context);
				}
			}
		} else {
			$retCode = -10064;
			$retMsg = Lang::get('errormessages.-10064');
			$context = array(
				"errorCode" => $retCode,
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"data" => Input::all(),
			);
			Log::error($retMsg, $context);
		}

		if ( SUCCESS == $retCode ) {
			$amount = array();
			$amountSum = 0;
			$consumeMoney = array();
			$consumeMoneySum = 0.000;
			$actualMoney = array();
			$actualMoneySum = 0.000;
			
			//对日期循环，使用array_shift操作statistics数据进行归类
			$flag = true;
			foreach ($dates as $date) {
				if ( $flag ) {
					$statisticsData = array_shift($statisticsDatas);
				}
				if ( 0 == strcmp($date, $statisticsData['generate_date']) ) {
					$flag = true;
					array_push($amount, $statisticsData['amount']);
					array_push($consumeMoney, $statisticsData['consume_money']);
					array_push($actualMoney, $statisticsData['actual_money']);
					$amountSum += intval($statisticsData['amount']);
					$consumeMoneySum += floatval($statisticsData['consume_money']);
					$actualMoneySum += floatval($statisticsData['actual_money']);				
				} else {
					$flag = false;
					array_push($amount, DEFAULT_DECIMAL_0);
					array_push($consumeMoney, DEFAULT_DECIMAL_0);
					array_push($actualMoney, DEFAULT_DECIMAL_0);
				}
			}

			$sendMsgArray = array(
				"ret_code" => $retCode,
				"msg" => $retMsg,
				"data" => array(
					"dates" => $dates,
					"amount" => $amount,
					"amount_sum" => $amountSum,
					"consume_money" => $consumeMoney,
					"consume_money_sum" => $consumeMoneySum,
					"actual_money" => $actualMoney,
					"actual_money_sum" => $actualMoneySum,
				),
			);
		} else {
			$sendMsgArray = array(
				"ret_code" => $retCode,
				"msg" => $retMsg,
			);
		}

		return Response::json($sendMsgArray);
	}

	/*
	 * 获取订单列表
	 */
	public function getFinanceReportList()
	{
		$mainOrders = array();
		$conditions = "";
		$params = array();
		$dates = array();
		$shopID = base64_decode(Input::get('shop_id'));

		if ( Request::ajax() ) {
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$userID = Auth::id();
				$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->get(array('wy_shop_id'));
				$shopIDs = array();
				foreach ($shops as $index => $shop) {
					array_push($shopIDs, $shop->wy_shop_id);
				}

				if ( !empty($shopIDs) ) {
					$this->getGenerateConditions($conditions, $params, $dates, false);
					if ( !empty($conditions) && !empty($params) ) {
						$mainOrders = MainOrder::whereIn('wy_shop_id', $shopIDs)->where('wy_order_state', ORDER_STATE_4)->whereRaw($conditions, $params)->orderBy('wy_generate_time')
							->paginate(PERPAGE_COUNT_10, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_generate_time','wy_consumption_money','wy_actual_money','wy_order_state'));
						foreach ($mainOrders as $index => $mainOrder) {
							$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
				            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
				            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
							$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
						}
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
				}
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( !empty($headerShop) ) {
					$this->getGenerateConditions($conditions, $params, $dates, false);
					if ( !empty($conditions) && !empty($params) ) {
						$mainOrders = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', ORDER_STATE_4)->whereRaw($conditions, $params)->orderBy('wy_generate_time')
							->paginate(PERPAGE_COUNT_10, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_generate_time','wy_consumption_money','wy_actual_money','wy_order_state'));
						foreach ($mainOrders as $index => $mainOrder) {
							$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
				            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
				            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
							$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
						}
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
				}
			}
		}

		return View::make('admin.report.finance.financelist', compact('mainOrders'));
	}

	/**
	 * 获取生成报表的条件
	 */
	protected function getGenerateConditions(&$conditions, &$params, &$dates, $bStatistics = true)
	{
		$startDate = Input::get('start_date');
		$endDate = Input::get('end_date');

		//时间的一些判断
		if ( !empty($startDate) && !empty($endDate) ) {
			$sd = Carbon::parse($startDate);
			$ed = Carbon::parse($endDate);
			$now = Carbon::parse(Carbon::now()->toDateString());
			$durations = $sd->diffInDays($ed, false);
			if ( $durations <= REPORT_DURATIONS && $durations >= 0 ) {
				if ( $ed->lte($now) ) {
					$conditions = 'date(wy_generate_time) between ? and ?';
					array_push($params, $startDate);
					array_push($params, $endDate);

					if ( $bStatistics ) {
						$conditions .= ' group by date(wy_generate_time) order by date(wy_generate_time)';
					}

					for ($i=0; $i <= $durations; $i++) { 
						$date = $sd->toDateString();
						array_push($dates, $date);
						$sd = $sd->addDay();
					}					
				}
			}
		}
	}

	/*
	 * 获取订单详情
	 */
	public function getListInfo()
	{
		$shopID = base64_decode(Input::get('shopID'));
		$mainOrderID = base64_decode(Input::get('mainOrderID'));
		$disableChange = DEFAULT_0;

		$headerShop = AuthController::checkShop($shopID);
		if ( !empty($headerShop) ) {
			$mainOrder = MainOrder::where('wy_main_order_id', $mainOrderID)->where('wy_shop_id',$shopID)
					->first(array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_recv_addr','wy_recv_phone','wy_consumption_money','wy_actual_money',
					        	'wy_order_state','wy_order_state_flow','wy_generate_time','wy_confirm_time','wy_send_time','wy_arrive_time','wy_reminder_flag','wy_reminder_count',
					        	'wy_reminder_time','wy_refuse_time','wy_cancel_time','wy_user_note'));
			if ( !empty($mainOrder) ) {
				$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
	            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
	            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
	            $mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
				$subOrders = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->get(array('wy_goods_id', 'wy_goods_name', 'wy_goods_unit_price', 'wy_goods_amount',
	                    'wy_goods_total_price'));
				$mainOrder->subOrders = $subOrders;
				$mainOrder->wy_shop_name = $headerShop->wy_shop_name;
				$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
				$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
				//订单流的处理
				$orderStatFlows = str_split($mainOrder->wy_order_state_flow);
				return View::make('admin.template.order.orderdetail', compact('headerShop','disableChange','mainOrder','orderStatFlows'));
			} else {
				return View::make('admin.template.order.orderdetail', compact('headerShop','disableChange'))->withError(Lang::get('errormessages.-10068'));
			}
		} else {
			return View::make('admin.template.order.orderdetail', compact('disableChange'))->withError(Lang::get('errormessages.-10068'));
		}
	}
}
