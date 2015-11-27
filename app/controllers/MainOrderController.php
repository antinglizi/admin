<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';
require_once app_path().'/lib/api/xinge/XingeApp.php';

class MainOrderController extends \BaseController {

	/**
	 * 显示订单
	 *
	 * @return Response
	 */
	public function getList()
	{
		//首先把所有订单取出来
		$mainOrders = array();
		$mainNewOrders = array();
		$mainRecvOrdersCount = 0;
		$mainDeliveryOrdersCount = 0;
		$mainFinishOrdersCount = 0;
		$mainCancelOrdersCount = 0;
		$mainRefuseOrdersCount = 0;
		$mainReminderOrdersCount = 0;

		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				return View::make('admin.freshbiz.order.order', compact('headerShop'))->withAll(Lang::get('messages.10015'));
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( empty($headerShop) ) {
					return View::make('admin.freshbiz.order.order')->withError(Lang::get('errormessages.-10069'));
				} else {
					$date = Carbon::now()->toDateString();
					$mainOrders = MainOrder::where('wy_shop_id', $shopID)->whereRaw('date(wy_generate_time) = ?', array($date))->orderBy('wy_generate_time','desc')
					        ->get(array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_recv_addr','wy_recv_phone','wy_actual_money',
					        	'wy_consumption_money','wy_order_state','wy_generate_time','wy_reminder_flag','wy_user_note'));
					foreach ($mainOrders as $index => $mainOrder) {
						if ( ORDER_STATE_1 == $mainOrder->wy_order_state ) {
							$orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first();
				            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;

							$subOrders = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->get(array('wy_goods_id', 'wy_goods_name', 'wy_goods_unit_price', 'wy_goods_amount',
				                    'wy_goods_total_price'));
							$mainOrder->subOrders = $subOrders;
							$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
							$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
						}

						//根据状态进行分类
						switch ($mainOrder->wy_order_state) {
					        case ORDER_STATE_1: //下单
					            array_push($mainNewOrders, $mainOrder);
					            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
					            	$mainReminderOrdersCount++;
					            }
					            break;
					        case ORDER_STATE_2: //接单
					            $mainRecvOrdersCount++;
					            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
					            	$mainReminderOrdersCount++;
					            }
					            break;
					        case ORDER_STATE_3:	//配送中
					            $mainDeliveryOrdersCount++;
					            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
					            	$mainReminderOrdersCount++;
					            }
					            break;
					        case ORDER_STATE_4: //完成
					            $mainFinishOrdersCount++;
					            break;
					        case ORDER_STATE_5:	//取消
					        	$mainCancelOrdersCount++;
					        	break;
					        case ORDER_STATE_6: //拒单
					        	$mainRefuseOrdersCount++;
					        	break;
					        default:
					            break;
			        	}
					}
					$host = Config::get('pushserver.host');
					$port = Config::get('pushserver.port');
					return View::make('admin.freshbiz.order.order', compact('headerShop','mainNewOrders','mainRecvOrdersCount','mainDeliveryOrdersCount',
						'mainFinishOrdersCount','mainCancelOrdersCount','mainRefuseOrdersCount','mainReminderOrdersCount','host','port'));
				}
			}
		} else {
			return View::make('admin.freshbiz.order.order')->withError(Lang::get('errormessages.-10069'));
		}
	}

	/**
	 * 获取特定状态的订单信息
	 *
	 * @return Response
	 */
	public function getListStatus()
	{
		$retCode = SUCCESS;
		$retMsg = "";
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);
		$orderStatus = Input::get('order_status');
		$date = Carbon::now()->toDateString();
		$mainOrders = array();
		$mainOrdersCount = array();

		if ( Request::ajax() && !empty($headerShop) ) {
			//增加每个状态的订单数量
			$mainOrdersCount = $this->getMainOrdersCount($shopID);
			//获取特定状态的订单
			if ( ORDER_STATE_7 == $orderStatus ) {
				$mainOrders = MainOrder::where('wy_shop_id', $shopID)->where('wy_reminder_flag', REMINDER_FLAG_1)
						->whereIn('wy_order_state', array(ORDER_STATE_1, ORDER_STATE_2, ORDER_STATE_3))
						->whereRaw('date(wy_generate_time) = ?', array($date))->orderBy('wy_generate_time')
						->get(array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_recv_addr','wy_recv_phone','wy_actual_money',
						        	'wy_consumption_money','wy_order_state','wy_reminder_flag','wy_reminder_count','wy_reminder_time','wy_user_note'));
				foreach ($mainOrders as $mainOrder) {
		            $orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first();
		            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;

					$subOrders = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->get(array('wy_goods_id', 'wy_goods_name', 'wy_goods_unit_price', 'wy_goods_amount',
		                    'wy_goods_total_price'));
					$mainOrder->subOrders = $subOrders;
					$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
					$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
				}
			} elseif ( ORDER_STATE_2 == $orderStatus
					|| ORDER_STATE_3 == $orderStatus ) {
		        $mainOrders = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', $orderStatus)
		                ->whereRaw('date(wy_generate_time) = ?', array($date))->orderBy('wy_generate_time')
		                ->get(array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_recv_addr','wy_recv_phone','wy_actual_money',
		                			'wy_consumption_money','wy_order_state','wy_generate_time','wy_confirm_time','wy_send_time','wy_user_note'));
				foreach ($mainOrders as $mainOrder) {
		            $orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first();
		            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;

					$subOrders = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->get(array('wy_goods_id', 'wy_goods_name', 'wy_goods_unit_price', 'wy_goods_amount',
		                    'wy_goods_total_price'));
					$mainOrder->subOrders = $subOrders;
					$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
					$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
				}
			} else {
				$mainOrders = MainOrder::where('wy_shop_id', $shopID)->where('wy_order_state', $orderStatus)
		                ->whereRaw('date(wy_generate_time) = ?', array($date))->orderBy('wy_generate_time')
		                ->get(array('wy_main_order_id','wy_shop_id','wy_order_number','wy_recv_name','wy_actual_money',
						        	'wy_order_state','wy_generate_time','wy_arrive_time','wy_refuse_time','wy_cancel_time'));
				foreach ($mainOrders as $mainOrder) {
		            $orderStatus = Dictionary::where('wy_dic_id', DIC_ORDER_STATUS)->where('wy_dic_item_id', $mainOrder->wy_order_state)->first();
		            $mainOrder->wy_order_state_name = $orderStatus->wy_dic_value;
					$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
					$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);
				}
			}
		} else {
			$retCode = -10070;
			$retMsg = Lang::get('errormessages.-10070');
			$context = array(
				"errorCode" => $retCode,
				"userID" => Auth::id(),
				"shopID" => $shopID,
			);
			Log::error($retMsg, $context);
		}
		
		//整体刷新
		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
			"count" => $mainOrdersCount,
			"data" => $mainOrders,
		);
		return Response::json($sendMsgArray);
	}

	/*
	 * 获取订单详情
	 * 
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

	/**
	 * 更改订单状态
	 *
	 * @return Response
	 */
	// 目前是单笔操作，以后要增加多笔操作
	public function postChangeStatus()
	{
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);
		$mainOrderID = base64_decode(Input::get('main_order_id'));
		$currentStatus = Input::get('current_status');
		$toBeStatus = Input::get('to_be_status');

		$retCode = SUCCESS;
		$retMsg = "";
		$mainOrder = MainOrder::where('wy_main_order_id', $mainOrderID)->where('wy_shop_id', $shopID)->where('wy_order_state', $currentStatus)
		        ->first(array('wy_main_order_id','wy_user_id','wy_shop_id','wy_order_number','wy_recv_name','wy_recv_addr','wy_recv_phone','wy_actual_money','wy_consumption_money',
					          'wy_order_state','wy_order_state_flow','wy_generate_time','wy_confirm_time','wy_send_time','wy_arrive_time','wy_refuse_time','wy_user_note'));
		if ( empty($mainOrder) ) {
			$retCode = -10051;
			$retMsg = Lang::get('errormessages.-10051');
		} else {
			switch ($toBeStatus) {
	            case ORDER_STATE_2: //接单
	                $mainOrder->wy_confirm_time = Carbon::now();
	                break;
	            case ORDER_STATE_3:	//配送中
	                $mainOrder->wy_send_time = Carbon::now();
	                break;
	            case ORDER_STATE_4: //完成
	                $mainOrder->wy_arrive_time = Carbon::now();
	                break;
	            case ORDER_STATE_6: //拒单
	            	$mainOrder->wy_refuse_time = Carbon::now();
	            	break;
	            default:
	                break;
        	}

			$mainOrder->wy_order_state = $toBeStatus;
			$mainOrder->wy_order_state_flow .= $toBeStatus;
			$result = $mainOrder->save();
			if ( $result ) {
				//向前端推送状态改变信息
				$this->pushOrderInfo($headerShop, $mainOrder, $toBeStatus);
				$retMsg = Lang::get('messages.10013');
			} else {
				$retCode = -15019;
				$retMsg = Lang::get('errormessages.-15019');
				$context = array(
					"errorCode" => $retCode,
					"userID" => Auth::id(),
					"shopID" => $shopID,
					"data" => $data,
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
	 * 获取每个状态的订单数量
	 */
	protected function getMainOrdersCount($shopID)
	{
		$mainOrders = array();
		$mainNewOrdersCount = 0;
		$mainRecvOrdersCount = 0;
		$mainDeliveryOrdersCount = 0;
		$mainFinishOrdersCount = 0;
		$mainCancelOrdersCount = 0;
		$mainRefuseOrdersCount = 0;
		$mainReminderOrdersCount = 0;

		$date = Carbon::now()->toDateString();
		$mainOrders = MainOrder::where('wy_shop_id', $shopID)->whereRaw('date(wy_generate_time) = ?', array($date))
		        ->get(array('wy_order_state','wy_reminder_flag'));
		foreach ($mainOrders as $index => $mainOrder) {
			//根据状态进行分类
			switch ($mainOrder->wy_order_state) {
		        case ORDER_STATE_1: //下单
		            $mainNewOrdersCount++;
		            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
		            	$mainReminderOrdersCount++;
		            }
		            break;
		        case ORDER_STATE_2: //接单
		            $mainRecvOrdersCount++;
		            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
		            	$mainReminderOrdersCount++;
		            }
		            break;
		        case ORDER_STATE_3:	//配送中
		            $mainDeliveryOrdersCount++;
		            if ( REMINDER_FLAG_1 == $mainOrder->wy_reminder_flag ) {
		            	$mainReminderOrdersCount++;
		            }
		            break;
		        case ORDER_STATE_4: //完成
		            $mainFinishOrdersCount++;
		            break;
		        case ORDER_STATE_5:	//取消
		        	$mainCancelOrdersCount++;
		        	break;
		        case ORDER_STATE_6: //拒单
		        	$mainRefuseOrdersCount++;
		        	break;
		        default:
		            break;
        	}
		}

		$mainOrdersCount = array(
			'new' => $mainNewOrdersCount,
			'recved' => $mainRecvOrdersCount,
			'deliverying' => $mainDeliveryOrdersCount,
			'finished' => $mainFinishOrdersCount,
			'canceled' => $mainCancelOrdersCount,
			'refused' => $mainRefuseOrdersCount,
			'reminder' => $mainReminderOrdersCount,
		);

		return $mainOrdersCount;
	}

	/**
	 * 更改订单状态时，向手机端（包括Android和IOS）发送提醒信息
	 * 
	 * @param [object] $[shop] [店铺对象]
	 * @param [object] $[mainOrder] [主订单]
	 * @param [int] $[toBeStatus] [要改变状态]
	 * 
	 * @return Response
	 */
	protected function pushOrderInfo($shop, $mainOrder, $toBeStatus)
	{
		//获取信鸽系统配置
		$accessId = Config::get('xinge.accessId');
		$secretKey = Config::get('xinge.secretKey');
		$xingeApp = new XingeApp($accessId, $secretKey);

		//根据mainorder中的userid获取token和设备类型
		$userTokens = UserToken::where('wy_user_id', $mainOrder->wy_user_id)->where('wy_user_type', USER_TYPE_1)->where('wy_status', LOGIN_STATUS_1)->get();
		if ( !empty($userTokens->toArray()) ) {
			foreach ($userTokens as $index => $userToken) {
				if ( DEVICE_TYPE_1 == $userToken->wy_device_type ) {
					//根据不同状态获取不同的模板信息
					switch ($toBeStatus) {
			            case ORDER_STATE_2: //接单
			                $orderMessage = Config::get('xinge.androidRecvMessage');
			                break;
			            case ORDER_STATE_3:	//配送中
			                $orderMessage = Config::get('xinge.androidDeliveryMessage');
			                break;
			            case ORDER_STATE_4: //完成
			                $orderMessage = Config::get('xinge.androidFinishMessage');
			                break;
			            case ORDER_STATE_6: //拒单
			            	$orderMessage = Config::get('xinge.androidRefuseMessage');
			            	break;
			            default:
			                break;
			    	}
			    	
			    	if ( isset($orderMessage) ) {
			    		//单击动作
						$action = new ClickAction();
						$action->setActionType(ClickAction::TYPE_ACTIVITY);
						$action->setActivity($orderMessage['activity']);
						//显示样式
						$styleParams = $orderMessage['style'];
						$style = new Style($styleParams['builderId'],$styleParams['ring'],$styleParams['vibrate'],$styleParams['clearable'],
							$styleParams['nId'],$styleParams['lights'],$styleParams['iconType'],$styleParams['styleId']);

						//Android平台的消息
						$message = new Message();
						$message->setTitle($orderMessage['title']);
						$message->setContent($orderMessage['content']);
						$message->setExpireTime($orderMessage['expireTime']);
						$message->setType(Message::TYPE_NOTIFICATION);
						$message->setStyle($style);
						$message->setAction($action);
			    	} else {
			    		$context = array(
			    			"errorCode" => -10054,
			    			"userID" => $mainOrder->wy_user_id,
			    			"toBeStatus" => $toBeStatus,
			    		);
			    		Log::error(Lang::get('errormessages.-10054'), $context);
			    	}

				} elseif (DEVICE_TYPE_2 == $userToken->wy_device_type) {
					//根据不同状态获取不同的模板信息
					switch ($toBeStatus) {
			            case ORDER_STATE_2: //接单
			                $orderMessage = Config::get('xinge.iosRecvMessage');
			                break;
			            case ORDER_STATE_3:	//配送中
			                $orderMessage = Config::get('xinge.iosDeliveryMessage');
			                break;
			            case ORDER_STATE_4: //完成
			                $orderMessage = Config::get('xinge.iosFinishMessage');
			                break;
			            case ORDER_STATE_6: //拒单
			            	$orderMessage = Config::get('xinge.iosRefuseMessage');
			            	break;
			            default:
			                break;
			    	}

			    	if ( isset($orderMessage) ) {
			    		//IOS平台的消息，需要IOS文档
						$message = new MessageIOS();
						$message->setTitle($orderMessage['title']);
			    	} else {
			    		$context = array(
			    			"errorCode" => -10055,
			    			"userID" => $mainOrder->wy_user_id,
			    			"toBeStatus" => $toBeStatus,
			    		);
			    		Log::error(Lang::get('errormessages.-10055'), $context);
			    	}
				} else {
					$context = array(
		    			"errorCode" => -10056,
		    			"userID" => $mainOrder->wy_user_id,
		    			"deviceType" => $userToken->wy_device_type,
		    		);
		    		Log::error(Lang::get('errormessages.-10056'), $context);
				}

				//自定义参数，key-value形式的
				if ( isset($message) ) {
					$custom = array(
						'shop_name'	=>	$shop->wy_shop_name,
						'main_order_id'	=> $mainOrder->wy_main_order_id,
					);
					$message->setCustom($custom);
					$result = $xingeApp->PushSingleDevice($userToken->wy_token, $message);
					Log::info($result);
				} else {
					$context = array(
		    			"errorCode" => -10057,
		    			"userID" => $mainOrder->wy_user_id,
		    			"deviceType" => $userToken->wy_device_type,
		    			"toBeStatus" => $toBeStatus,
		    		);
		    		Log::error(Lang::get('errormessages.-10057'), $context);
				}
			}
		} else {
			$context = array(
				"errorCode"	=> -10053,
				"userID" => $mainOrder->wy_user_id,
			);
			Log::error(Lang::get('errormessages.-10053'), $context);
		}
	}

	/**
	 * 打印订单
	 *
	 * @return Response
	 */
	public function getPrint($shop_id, $main_order_id)
	{
		$shopID = base64_decode($shop_id);
		$headerShop = AuthController::checkShop($shopID);
		$mainOrderID = base64_decode($main_order_id);
		if ( empty($headerShop) ) {
			App::abort(404);
		} else {
			
			$shop = Shop::where('wy_shop_id', $shopID)->first();
			$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
			
			$mainOrder = MainOrder::where('wy_shop_id', $shopID)->where('wy_main_order_id', $mainOrderID)->first();
			$subOrders = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->get(array('wy_goods_id', 'wy_goods_name', 'wy_goods_unit_price', 'wy_goods_amount',
                    'wy_goods_total_price'));
			$goodsTotalAmout = MainOrder::find($mainOrder->wy_main_order_id)->subOrders()->sum('wy_goods_amount');
			
			$mainOrder->goodsTotalAmout = $goodsTotalAmout;
			$mainOrder->subOrders = $subOrders;
			$mainOrder->wy_shop_id = base64_encode($mainOrder->wy_shop_id);
			$mainOrder->wy_main_order_id = base64_encode($mainOrder->wy_main_order_id);

			return View::make('admin.print.print', compact('mainOrder', 'shop'));
		}
	}
}
