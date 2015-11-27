<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class TradeController extends \BaseController {

	/**
	 * 显示交易报表界面
	 *
	 * @return Response
	 */
	public function getList()
	{
		$mainOrders = array();
		$types = array();
		$reminderFlags = array();

		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);

				$userID = Auth::id();
				$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->get(array('wy_shop_id'));
				$shopIDs = array();
				foreach ($shops as $index => $shop) {
					array_push($shopIDs, $shop->wy_shop_id);
				}

				if ( !empty($shopIDs) ) {
					$mainOrders = MainOrder::whereIn('wy_shop_id', $shopIDs)
						->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					foreach ($mainOrders as $index => $mainOrder) {
						$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
			            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
			            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            $mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
			            $shop = Shop::where('wy_shop_id', $mainOrder->wy_shop_id)->first(array('wy_shop_name'));
			            if ( !empty($shop) ) {
			            	$mainOrder->wy_shop_name = $shop->wy_shop_name;
			            } else {
			            	$mainOrder->wy_shop_name = $headerShop->wy_shop_name;
			            }
			            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
						$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_15);
					if ( Request::ajax() ) {
						return View::make('admin.report.trade.tradelist', compact('mainOrders'));
					} else {
						return View::make('admin.report.trade.trade', compact('mainOrders','reminderFlags'))
							->nest('orderStatus','admin.template.dic.type',compact('types'));
					}
				}
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( !empty($headerShop) ) {
					$mainOrders = MainOrder::where('wy_shop_id', $shopID)
							->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					foreach ($mainOrders as $index => $mainOrder) {
						$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
			            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
			            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            $mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
			            $mainOrder->wy_shop_name = $headerShop->wy_shop_name;
			            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
						$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_15);
					if ( Request::ajax() ) {
						return View::make('admin.report.trade.tradelist', compact('mainOrders'));
					} else {
						return View::make('admin.report.trade.trade', compact('mainOrders','reminderFlags'))
							->nest('orderStatus','admin.template.dic.type',compact('types'));
					}
				}
			}

			if ( Request::ajax() ) {
				return View::make('admin.report.trade.tradelist', compact('mainOrders'));
			} else {
				$types = $this->getOrderStatus();
				$reminderFlags = $this->getReminderFlag();
				return View::make('admin.report.trade.trade', compact('headerShop','mainOrders','reminderFlags'))
					->nest('orderStatus','admin.template.dic.type',compact('types'));
			}
		} else {
			$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_15);
			if ( Request::ajax() ) {
				return View::make('admin.report.trade.tradelist', compact('mainOrders'));
			} else {
				return View::make('admin.report.trade.trade', compact('mainOrders','reminderFlags'))
					->nest('orderStatus','admin.template.dic.type',compact('types'));
			}
		}
	}

	/*
	 * 获取订单状态数据字典信息
	 */
	protected function getOrderStatus()
	{
		$orderStatuses = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', '!=', ORDER_STATE_7)
				->get(array('wy_dic_item_id','wy_dic_value'));
		return $orderStatuses;
	}

	/*
	 * 获取催单状态数据字典信息
	 */
	protected function getReminderFlag()
	{
		$reminderFlags = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->get(array('wy_dic_item_id','wy_dic_value'));
		return $reminderFlags;
	}	

	/*
	 * 获取订单列表
	 */
	public function getTradeList()
	{
		$mainOrders = array();
		$conditions = "";
		$params = array();
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
					$this->getSearchConditions($conditions, $params);
					if ( empty($conditions) && empty($params) ) {
						$mainOrders = MainOrder::whereIn('wy_shop_id', $shopIDs)
							->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					} else {
						$mainOrders = MainOrder::whereIn('wy_shop_id', $shopIDs)->whereRaw($conditions, $params)
							->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					}
					foreach ($mainOrders as $index => $mainOrder) {
						$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
			            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
			            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            $mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
			            $shop = Shop::where('wy_shop_id', $mainOrder->wy_shop_id)->first(array('wy_shop_name'));
			            if ( !empty($shop) ) {
			            	$mainOrder->wy_shop_name = $shop->wy_shop_name;
			            } else {
			            	$mainOrder->wy_shop_name = $headerShop->wy_shop_name;
			            }
			            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
						$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_15);
				}
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( !empty($headerShop) ) {
					$this->getSearchConditions($conditions, $params);
					if ( empty($conditions) && empty($params) ) {
						$mainOrders = MainOrder::where('wy_shop_id', $shopID)
							->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					} else {
						$mainOrders = MainOrder::where('wy_shop_id', $shopID)->whereRaw($conditions, $params)
							->paginate(PERPAGE_COUNT_15, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
					}
					foreach ($mainOrders as $index => $mainOrder) {
						$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
			            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
			            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            $mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
			            $mainOrder->wy_shop_name = $headerShop->wy_shop_name;
			            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
						$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_15);
				}
			}
		}
		
		return View::make('admin.report.trade.tradelist', compact('mainOrders'));
	}

	/*
	 * 构造查询条件
	 */
	protected function getSearchConditions(&$conditions, &$params)
	{
		$orderNumber = Input::get('order_number');
		$buyerName = Input::get('buyer_name');
		$orderStatus = Input::get('order_status');
		$reminderFlag = Input::get('reminder_flag');
		$startTime = Input::get('start_time');
		$endTime = Input::get('end_time');

		if ( !empty($orderNumber) ) {
			$conditions = 'wy_order_number = ?';
			array_push($params, $orderNumber);
		}

		if ( !empty($buyerName) ) {
			if ( !empty($conditions) ) {
				$conditions .= ' and wy_recv_name = ?';
			} else {
				$conditions .= 'wy_recv_name = ?';
			}
			array_push($params, $buyerName);
		}

		if ( INVALID_SELECT != $orderStatus ) {
			if ( !empty($conditions) ) {
				$conditions .= ' and wy_order_state = ?';
			} else {
				$conditions .= 'wy_order_state = ?';
			}
			array_push($params, $orderStatus);
		}

		if ( INVALID_SELECT != $reminderFlag ) {
			if ( !empty($conditions) ) {
				$conditions .= ' and wy_reminder_flag = ?';
			} else {
				$conditions .= 'wy_reminder_flag = ?';
			}
			array_push($params, $reminderFlag);
		}

		if ( !empty($startTime) ) {
			if ( !empty($conditions) ) {
				$conditions .= ' and wy_generate_time >= ?';
			} else {
				$conditions .= 'wy_generate_time >= ?';
			}
			array_push($params, $startTime);
		}

		if ( !empty($endTime) ) {
			if ( !empty($conditions) ) {
				$conditions .= ' and wy_generate_time <= ?';
			} else {
				$conditions .= 'wy_generate_time <= ?';
			}
			array_push($params, $endTime);
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

	/*
	 * 获取营业报表信息
	 */
	public function getBizReport()
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
					$this->getStatisticsConditions($conditions, $params, $dates);
					if ( empty($conditions) && empty($params) ) {
						$retCode = -10062;
						$retMsg = Lang::get('errormessages.-10062');
					} else {
						$statisticsDatas = MainOrder::whereIn('wy_shop_id', $shopIDs)->whereRaw($conditions, $params)
							->select(DB::raw('date(wy_generate_time) as generate_date, count(*) as amount, sum(wy_actual_money) as money'))->get()->toArray();
					}
				} else {
					$retCode = -10061;
					$retMsg = Lang::get('errormessages.-10061');
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
					$this->getStatisticsConditions($conditions, $params, $dates);
					if ( empty($conditions) && empty($params) ) {
						$retCode = -10062;
						$retMsg = Lang::get('errormessages.-10062');
					} else {
						$statisticsDatas = MainOrder::where('wy_shop_id', $shopID)->whereRaw($conditions, $params)
							->select(DB::raw('date(wy_generate_time) as generate_date, count(*) as amount, sum(wy_actual_money) as money'))->get()->toArray();
					}
				} else {
					$retCode = -10059;
					$retMsg = Lang::get('errormessages.-10059');
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
			$retCode = -10060;
			$retMsg = Lang::get('errormessages.-10060');
			$context = array(
				"errorCode" => $retCode,
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"data" => Input::all(),
			);
			Log::error($retMsg, $context);
		}

		if ( SUCCESS == $retCode ) {
			$money = array();
			$amount = array();
			$moneySum = 0.000;
			$amountSum = 0;
			//对日期循环，使用array_shift操作statistics数据进行归类
			$flag = true;
			foreach ($dates as $date) {
				if ( $flag ) {
					$statisticsData = array_shift($statisticsDatas);
				}
				if ( 0 == strcmp($date, $statisticsData['generate_date']) ) {
					$flag = true;
					array_push($money, $statisticsData['money']);
					array_push($amount, $statisticsData['amount']);
					$moneySum += floatval($statisticsData['money']);
					$amountSum += intval($statisticsData['amount']);
				} else {
					$flag = false;
					array_push($money, DEFAULT_DECIMAL_0);
					array_push($amount, DEFAULT_INT_0);
				}
			}

			$sendMsgArray = array(
				"ret_code" => $retCode,
				"msg" => $retMsg,
				"data" => array(
					"dates" => $dates,
					"money" => $money,
					"money_sum" => $moneySum,
					"amount" => $amount,
					"amount_sum" => $amountSum,
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
	 * 生成统计查询条件
	 */
	
	protected function getStatisticsConditions(&$conditions, &$params, &$dates, $bStatistics = true)
	{
		$startDate = Input::get('start_date');
		$endDate = Input::get('end_date');
		$orderStatus = Input::get('order_status');
		$reminderFlag = Input::get('reminder_flag');

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

					if ( INVALID_SELECT != $orderStatus ) {
						$conditions .= ' and wy_order_state = ?';
						array_push($params, $orderStatus);
					}

					if ( INVALID_SELECT != $reminderFlag ) {
						$conditions .= ' and wy_reminder_flag = ?';
						array_push($params, $reminderFlag);
					}

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
	 * 获取营业报表的订单列表
	 */
	public function getBizReportList()
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
					$this->getStatisticsConditions($conditions, $params, $dates, false);
					if ( !empty($conditions) && !empty($params) ) {
						$mainOrders = MainOrder::whereIn('wy_shop_id', $shopIDs)->whereRaw($conditions, $params)->orderBy('wy_generate_time')
							->paginate(PERPAGE_COUNT_10, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
						foreach ($mainOrders as $index => $mainOrder) {
							$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
				            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
				            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            	$mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
				            $shop = Shop::where('wy_shop_id', $mainOrder->wy_shop_id)->first(array('wy_shop_name'));
				            if ( !empty($shop) ) {
				            	$mainOrder->wy_shop_name = $shop->wy_shop_name;
				            } else {
				            	$mainOrder->wy_shop_name = $headerShop->wy_shop_name;
				            }
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
					$this->getStatisticsConditions($conditions, $params, $dates, false);
					if ( !empty($conditions) && !empty($params) ) {
						$mainOrders = MainOrder::where('wy_shop_id', $shopID)->whereRaw($conditions, $params)->orderBy('wy_generate_time')
							->paginate(PERPAGE_COUNT_10, array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_generate_time','wy_actual_money','wy_order_state','wy_reminder_flag'));
						foreach ($mainOrders as $index => $mainOrder) {
							$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first(array('wy_dic_value'));
				            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
				            $reminderFlag = Dictionary::where('wy_dic_id', DIC_REMINDER_FLAG)->where('wy_dic_item_id', $mainOrder->wy_reminder_flag)->first(array('wy_dic_value'));
			            	$mainOrder->wy_reminder_flag_name = $reminderFlag->wy_dic_value;
				            $mainOrder->wy_shop_name = $headerShop->wy_shop_name;
				            $mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
							$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
						}
					}
				} else {
					$mainOrders = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
				}
			}
		}

		return View::make('admin.report.trade.bizreportlist', compact('mainOrders'));
	}
}
