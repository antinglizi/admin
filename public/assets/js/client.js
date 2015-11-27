/**
 * Created by Daniel on 2015/3/25.
 */

/*
客户端连接状态：
正在建立连接
已连接
已断开
已连接未认证
已连接并认证 （只有这个状态才可以接收数据）
 */

var Client = function() {

	var ClientFuncNo = {
		FuncNo_700001 : 700001,		//登陆 登陆之后就获取未推送订单
		FuncNo_700002 : 700002, 	//获取未推送的实时订单 暂时不需要 修改为认证成功确认消息
		FuncNo_700003 : 700003,		//接收推送实时订单
		FuncNo_700004 : 700004,		//定时查询实时订单
		FuncNo_700005 : 700005,		//手动查询实时订单
		FuncNo_700006 : 700006,		//获取订单详情
		FuncNo_700007 : 700007,		//定时校验订单数量
		FuncNo_700008 : 700008,		//取消订单
		FuncNo_700009 : 700009,		//催单
	};

	var OrderStatus = {
		Status_1 : 1,	//下单
		Status_2 : 2,	//接单
		Status_3 : 3,	//配送中
		Status_4 : 4,	//取消
		Status_5 : 5,	//完成
		Status_6 : 6,	//退单
		Status_7 : 7,	//催单
	};

	var initVariable = function() {
		Client.host = "";
		Client.port = ""
		Client.user_id = "";
		Client.pwd = "";
		Client.shop_id = "";
		Client.client_id = "";
		Client.token = "";
		Client.ws = null;
		Client.connected = false;
		Client.timer = 0;
		Client.notifyVoice = null;
		Client.version = "0.0.3";
		Client.success = 0;
		Client.reconnectTime = 30000;
	}

	var connect = function() {
		if ( window.WebSocket || window.MozWebSocket ) {
			try {
				var shopID = encodeURIComponent(Client.shop_id);
				var token = encodeURIComponent(Client.token);
				var params = "shopID=" + shopID + "&token=" + token;
				var URI = "ws://" + Client.host + ":" + Client.port + "/admin/websocket?" + params;
				Client.ws = new WebSocket(URI);
				//修改界面的连接状态
				// showMessage('连接状态为：' + Client.ws.readyState);
				showMessage("正在建立连接");
				//安装回调事件
				Client.ws.onopen = onOpen;
				Client.ws.onmessage = onMessage;
				Client.ws.onclose = onClose;
			} catch (exception) {
				showAlertMessage("发生异常:" + exception);		
			}
		}
		else {
			showAlertMessage("该浏览器不支持WebSocket，建议使用谷歌浏览器！");
		}
	}

	// 利用页面刷新来进行重新连接
	var reconnect = function() {
		window.location.reload();
	}

	var onOpen = function() {
		//修改界面的连接状态
		showMessage("已连接");
		Client.connected = true;
		if(!Client.timer) {
			clearTimeout(Client.timer);
			Client.timer = 0;
		}

		//发送登陆验证信息
		funcNo_700001();
	}

	var onMessage = function(content) {
		// showMessage(content.data);
		var jsonData = "";
		if ( window.JSON && window.JSON.parse ) {
			try {
				jsonData = JSON.parse(content.data);
			} catch(e) {
				showAlertMessage('数据格式不正确！');
			}
		}
		if ( "undefined" == typeof(jsonData) || "" == jsonData ) {
			//是否需要服务服务器，根据业务来
			showAlertMessage('数据格式不正确！');
			return;
		}
		
		switch(jsonData.func_no) {
			case ClientFuncNo.FuncNo_700001:
			{
				handlerMessage_700001(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700002:
			{
				handlerMessage_700002(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700003:
			{
				handlerMessage_700003(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700004:
			{
				handlerMessage_700004(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700006:
			{
				handlerMessage_700006(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700007:
			{
				handlerMessage_700007(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700008:
			{
				handlerMessage_700008(jsonData);
				break;
			}
			case ClientFuncNo.FuncNo_700009:
			{
				handlerMessage_700009(jsonData);
				break;
			}
			default:
				showAlertMessage('暂时不支持此'+jsonData.func_no+'功能');
				break;
		}
	}

	var onClose = function() {
		//修改界面的连接状态
		showMessage('已断开');
		showAlertMessage('已断开');
		Client.connected = false;
		//启动定时器去连接服务器
		if(!Client.timer) {
			Client.timer = setTimeout(reconnect, Client.reconnectTime);
		}
	}

	var sendMsg = function(data) {
		if(!Client.connected) {
			showAlertMessage('与服务器已经断开，发送数据失败，请刷新连接服务器！');
		} else {
			Client.ws.send(JSON.stringify(data));
		}
	}

	var showMessage = function(msg) {
		$('#message').html(msg);
	}

	var showAlertMessage = function(msg) {
		$('#content-right div#alert_msg').after('<div class="alert alert-danger alert-dismissible" role="alert"> \
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
			<span aria-hidden="true">&times;</span></button>'+msg+'</div>');
	}

	//注意js的数组与PHP的数组的不同，以及js数组和对象的不同
	var funcNo_700001 = function() {
		//构造发送的消息
		var msgHeader = new Object();
		var msgBody = new Array();
		var msgData = new Object();
		//包头
		msgHeader.func_no = ClientFuncNo.FuncNo_700001;
		var timestamp = new Date();
		msgHeader.timestamp = timestamp.getTime();
		msgHeader.version = Client.version;
		//包体中数组0
		msgData.user_id = Client.user_id;
		msgData.pwd = Client.pwd;
		msgData.shop_id = Client.shop_id;
		msgData.client_id = Client.client_id;
		msgData.token = Client.token;
		//包体中增加数组项
		msgBody[0] = msgData;
		//包体中数据
		msgHeader.data = msgBody;
		sendMsg(msgHeader);
	}

	var handlerMessage_700001 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			showMessage(jsonData.msg);
		} else {
			showAlertMessage(jsonData.msg);
			showMessage('已经连未验证通过');
			Client.ws.close();
		}
	}

	var handlerMessage_700002 = function(jsonData) {

	}

	var handlerMessage_700003 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			var mainOrderArray = new Array();
			uniqueMainOrders('new_table', jsonData.data, mainOrderArray);
			if ( mainOrderArray.length > 0 ) {
				Client.notifyVoice.play();
				var orderNew = $('#new_order_count').next();
				if ( orderNew.hasClass('hidden') ) {
					orderNew.removeClass('hidden');
				}
				handlerMessage_NewOrder('new_table', 'new_order_count', mainOrderArray, false);
			}
		} else {
			showAlertMessage(jsonData.msg);
		}
	}

	var handlerMessage_700004 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			var mainOrderArray = new Array();
			uniqueMainOrders('new_table', jsonData.data, mainOrderArray);
			if ( mainOrderArray.length > 0 ) {
				Client.notifyVoice.play();
				var orderNew = $('#new_order_count').next();
				if ( orderNew.hasClass('hidden') ) {
					orderNew.removeClass('hidden');
				}
				handlerMessage_NewOrder('new_table', 'new_order_count', mainOrderArray, false);
			}
		} else {
			showAlertMessage(jsonData.msg);
		}
	}

	var handlerMessage_700006 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			handlerOrdersCount(jsonData.data);
		}
	}

	var handlerMessage_700007 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			$('#new_table tbody').each(function(index, el) {
				var mainOrderID = $(this).data('mainorderid');
				var mainOrderIDArray = jsonData.data;
				var flag = true;
				for (var i = 0; i < mainOrderIDArray.length; i++) {
					if ( mainOrderID == mainOrderIDArray[i].wy_main_order_id ) {
						flag = false;
						break;
					}
				}

				if ( flag ) {
					$(this).remove();
					var newOrderCount = $('#new_order_count');
					newOrderCount.text(parseInt(newOrderCount.text()) - 1);
					var orderNew = $('#new_order_count').next();
					if ( orderNew.hasClass('hidden') ) {
						orderNew.removeClass('hidden');
					}
				}
			});
		}
	}

	var handlerMessage_700008 = function(jsonData) {
		if ( Client.success == jsonData.ret_code ) {
			//移除被取消的订单
			$.each(jsonData.data, function(key, item){
				$('#content-right a[data-toggle="pill"]').each(function(index, el){
					var orderStatus = $(this).data('orderstatus');
					if ( orderStatus == item['previous_order_state'] || orderStatus == OrderStatus.Status_7 ) {
						var tableID = $(this).attr('aria-controls') + '_table';
						var tabID = $(this).find('span').attr('id');
						removeMainOrders(tableID, tabID, jsonData.data);
					}
				});
			});
			
			//增加已取消的订单
			var mainOrderArray = new Array();
			uniqueMainOrders('canceled_table', jsonData.data, mainOrderArray);
			if ( mainOrderArray.length > 0 ) {
				Client.notifyVoice.play();
				var orderCount = $('#canceled_order_count');
				var orderNew = orderCount.next();
				if ( orderNew.hasClass('hidden') ) {
					orderNew.removeClass('hidden');
				}
				if ( $('#content-right a[href="#canceled"]').parent().hasClass('active') ) {
					handlerMessage_CancelOrder('canceled_table', 'canceled_order_count', mainOrderArray, false);
				} else {
					count = parseInt(orderCount.text()) + mainOrderArray.length;
					orderCount.text(count);
				}
			}
		} else {
			showAlertMessage(jsonData.msg);
		}
	}

	var handlerMessage_700009 = function(jsonData) {
		//增加是否为活动状态的判断
		if ( Client.success == jsonData.ret_code ) {
			var mainOrderArray = new Array();
			uniqueMainOrders('reminder_table', jsonData.data, mainOrderArray);
			if ( mainOrderArray.length > 0 ) {
				Client.notifyVoice.play();
				var orderCount = $('#reminder_order_count');
				var orderNew = orderCount.next();
				if ( orderNew.hasClass('hidden') ) {
					orderNew.removeClass('hidden');
				}
				if ( $('#content-right a[href="#reminder"]').parent().hasClass('active') ) {
					handlerMessage_ReminderOrder('reminder_table', 'reminder_order_count', mainOrderArray, false);
				} else {
					count = parseInt(orderCount.text()) + mainOrderArray.length;
					orderCount.text(count);
				}
			}
		} else {
			showAlertMessage(jsonData.msg);
		}
	}

	var uniqueMainOrders = function(tableID, jsonArray, mainOrderArray) {
		//获取界面中的主订单ID，防止重复添加
		var mainOrderIDArray = new Array();

		$('#'+tableID+' tbody').each(function(index, el) {
			mainOrderIDArray.push($(this).data('mainorderid'));
		});

		$.each(jsonArray, function(index, item) {
			var flag = true;
			for (var i = 0; i < mainOrderIDArray.length; i++) {
				if ( mainOrderIDArray[i] == item['wy_main_order_id'] ) {
					flag = false;
					break;
				}
			};
			if ( flag ) {
				mainOrderArray.push(item);
			}
		});
	}

	var removeMainOrders = function(tableID, tabID, jsonArray)
	{
		//移除订单
		for (var i = 0; i < jsonArray.length; i++) {
			var tbodyArray = $('#'+tableID+' tbody');
			for ( j = 0; j < tbodyArray.length; j++ ) {
				var mainOrderID = $(tbodyArray[j]).data('mainorderid');
				if ( mainOrderID == jsonArray[i].wy_main_order_id ) {
					$(tbodyArray[j]).remove();
					var orderCount = $('#'+tabID);
					orderCount.text(parseInt(orderCount.text()) - 1);
					var orderNew = orderCount.next();
					if ( orderNew.hasClass('hidden') ) {
						orderNew.removeClass('hidden');
					}
					break;
				}
			}
		}
	}

	//新订单显示界面
	var handlerMessage_NewOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var discountMoney = parseFloat(mainOrder.wy_consumption_money) - parseFloat(mainOrder.wy_actual_money);
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="sep-row"></tr> \
                		<tr class="order-header"> \
                			<td colspan="4"> \
                    			<ul> \
				                    <li> \
				                        <div> \
				                            <strong>订单编号：</strong> \
				                            <span id="order_number">'+mainOrder.wy_order_number+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong> \
				                            <span>'+mainOrder.wy_recv_name+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>配送地址：</strong> \
				                            <span>'+mainOrder.wy_recv_addr+'</span> \
				                        </div> \
				                    </li> \
				                    <li> \
				                        <div> \
				                            <strong>下单时间：</strong> \
				                            <span>'+mainOrder.wy_generate_time+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>联系电话：</strong> \
				                            <span>'+mainOrder.wy_recv_phone+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>买家留言：</strong> \
				                            <span>'+mainOrder.wy_user_note+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td colspan="2"> \
                				<ul> \
				                    <li> \
				                        <div> \
				                            <strong>消费金额（元）：</strong> \
				                            <span>'+mainOrder.wy_consumption_money+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>优惠金额（元）：</strong> \
				                            <span>'+discountMoney+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>订单总额（元）：</strong> \
				                            <span>'+mainOrder.wy_actual_money+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                		</tr>';

            $.each(mainOrder.subOrders, function(subIndex, subOrder) {
				content += '<tr class="text-center"> \
                    			<td>'+subOrder.wy_goods_name+'</td> \
                    			<td>'+subOrder.wy_goods_unit_price+'</td> \
                    			<td>'+subOrder.wy_goods_amount+'</td> \
                    			<td>'+subOrder.wy_goods_total_price+'</td>';
				if ( 0 == subIndex ) {
					content += '<td rowspan="'+mainOrder.subOrders.length+'"><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
                    			<td rowspan="'+mainOrder.subOrders.length+'"> \
                    				<div> \
                    					<button id="recved_order" class="btn btn-success btn-sm bg-green"><span class="fa fa-check"><span class="pl-5">接单</span></span></button> \
                    				</div> \
									<div class="mt-15"> \
										<button id="refused_order" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#refused_order_modal"><span class="fa fa-times"><span class="pl-5">拒单</span></span></button> \
									</div> \
                    			</td>';
				}
				content += '</tr>';
			});
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}
	//已接单显示界面
	var handlerMessage_RecvOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var discountMoney = parseFloat(mainOrder.wy_consumption_money) - parseFloat(mainOrder.wy_actual_money);
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="sep-row"></tr> \
                		<tr class="order-header"> \
                			<td colspan="3"> \
                    			<ul> \
				                    <li> \
				                        <div> \
				                            <strong>订单编号：</strong> \
				                            <span>'+mainOrder.wy_order_number+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong> \
				                            <span>'+mainOrder.wy_recv_name+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>配送地址：</strong> \
				                            <span>'+mainOrder.wy_recv_addr+'</span> \
				                        </div> \
				                    </li> \
				                    <li> \
				                        <div> \
				                            <strong>接单时间：</strong> \
				                            <span>'+mainOrder.wy_confirm_time+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>联系电话：</strong> \
				                            <span>'+mainOrder.wy_recv_phone+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>买家留言：</strong> \
				                            <span>'+mainOrder.wy_user_note+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td colspan="2"> \
                				<ul> \
				                    <li> \
				                        <div> \
				                            <strong>消费金额（元）：</strong> \
				                            <span>'+mainOrder.wy_consumption_money+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>优惠金额（元）：</strong> \
				                            <span>'+discountMoney+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>订单总额（元）：</strong> \
				                            <span>'+mainOrder.wy_actual_money+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td> \
                				<div class="print"> \
                					<a class="btn btn-default btn-sm" href="/admin/freshbiz/order/print/'+mainOrder.wy_shop_id+'/'+mainOrder.wy_main_order_id+'" target="_blank"><span class="fa fa-print"></span>打印发货单</a> \
                				</div> \
                			</td> \
                		</tr>';

            $.each(mainOrder.subOrders, function(subIndex, subOrder) {
				content += '<tr class="text-center" data-goodsid="'+subOrder.wy_goods_id+'"> \
                    			<td>'+subOrder.wy_goods_name+'</td> \
                    			<td>'+subOrder.wy_goods_unit_price+'</td> \
                    			<td>'+subOrder.wy_goods_amount+'</td> \
                    			<td>'+subOrder.wy_goods_total_price+'</td>';
				if ( 0 == subIndex ) {
					content += '<td rowspan="'+mainOrder.subOrders.length+'"><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
                    			<td rowspan="'+mainOrder.subOrders.length+'"> \
                    				<div> \
                    					<button id="deliverying_order" class="btn btn-success btn-sm bg-green"><span class="fa fa-paper-plane"><span class="pl-5">配送</span></span></button> \
                    				</div> \
                    			</td>';
				}
				content += '</tr>';
			});
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerMessage_DeliveryingOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var discountMoney = parseFloat(mainOrder.wy_consumption_money) - parseFloat(mainOrder.wy_actual_money);
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="sep-row"></tr> \
                		<tr class="order-header"> \
                			<td colspan="3"> \
                    			<ul> \
				                    <li> \
				                        <div> \
				                            <strong>订单编号：</strong> \
				                            <span>'+mainOrder.wy_order_number+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong> \
				                            <span>'+mainOrder.wy_recv_name+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>配送地址：</strong> \
				                            <span>'+mainOrder.wy_recv_addr+'</span> \
				                        </div> \
				                    </li> \
				                    <li> \
				                        <div> \
				                            <strong>配送时间：</strong> \
				                            <span>'+mainOrder.wy_send_time+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>联系电话：</strong> \
				                            <span>'+mainOrder.wy_recv_phone+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>买家留言：</strong> \
				                            <span>'+mainOrder.wy_user_note+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td colspan="2"> \
                				<ul> \
				                    <li> \
				                        <div> \
				                            <strong>消费金额（元）：</strong> \
				                            <span>'+mainOrder.wy_consumption_money+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>优惠金额（元）：</strong> \
				                            <span>'+discountMoney+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>订单总额（元）：</strong> \
				                            <span>'+mainOrder.wy_actual_money+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td> \
                				<div class="print"> \
                					<a class="btn btn-default btn-sm" href="/admin/freshbiz/order/print/'+mainOrder.wy_shop_id+'/'+mainOrder.wy_main_order_id+'" target="_blank"><span class="fa fa-print"></span>打印发货单</a> \
                				</div> \
                			</td> \
                		</tr>';

            $.each(mainOrder.subOrders, function(subIndex, subOrder) {
				content += '<tr class="text-center" data-goodsid="'+subOrder.wy_goods_id+'"> \
                    			<td>'+subOrder.wy_goods_name+'</td> \
                    			<td>'+subOrder.wy_goods_unit_price+'</td> \
                    			<td>'+subOrder.wy_goods_amount+'</td> \
                    			<td>'+subOrder.wy_goods_total_price+'</td>';
				if ( 0 == subIndex ) {
					content += '<td rowspan="'+mainOrder.subOrders.length+'"><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
                    			<td rowspan="'+mainOrder.subOrders.length+'"> \
                    				<div> \
                    					<button id="finished_order" class="btn btn-success btn-sm bg-green"><span class="fa fa-smile-o"><span class="pl-5">完成</span></span></button> \
                    				</div> \
                    			</td>';
				}
				content += '</tr>';
			});
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerMessage_FinishOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var params = 'mainOrderID=' + mainOrder.wy_main_order_id + '&' + 'shopID=' + mainOrder.wy_shop_id;
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="text-center"> \
				            <td>'+mainOrder.wy_order_number+'</td> \
				            <td>'+mainOrder.wy_recv_name+'</td> \
				            <td>'+mainOrder.wy_generate_time+'</td> \
				            <td>'+mainOrder.wy_arrive_time+'</td> \
				            <td>'+mainOrder.wy_actual_money+'</td> \
				            <td><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
				            <td> \
				                <a class="btn btn-success btn-xs bg-green" href="/admin/freshbiz/order/list/info?'+params+'" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a> \
				            </td> \
				        </tr>';
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerMessage_CancelOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var params = 'mainOrderID=' + mainOrder.wy_main_order_id + '&' + 'shopID=' + mainOrder.wy_shop_id;
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="text-center"> \
				            <td>'+mainOrder.wy_order_number+'</td> \
				            <td>'+mainOrder.wy_recv_name+'</td> \
				            <td>'+mainOrder.wy_generate_time+'</td> \
				            <td>'+mainOrder.wy_cancel_time+'</td> \
				            <td>'+mainOrder.wy_actual_money+'</td> \
				            <td><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
				            <td> \
				                <a class="btn btn-success btn-xs bg-green" href="/admin/freshbiz/order/list/info?'+params+'" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a> \
				            </td> \
				        </tr>';
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerMessage_RefuseOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var params = 'mainOrderID=' + mainOrder.wy_main_order_id + '&' + 'shopID=' + mainOrder.wy_shop_id;
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="text-center"> \
				            <td>'+mainOrder.wy_order_number+'</td> \
				            <td>'+mainOrder.wy_recv_name+'</td> \
				            <td>'+mainOrder.wy_generate_time+'</td> \
				            <td>'+mainOrder.wy_refuse_time+'</td> \
				            <td>'+mainOrder.wy_actual_money+'</td> \
				            <td><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
				            <td> \
				                <a class="btn btn-success btn-xs bg-green" href="/admin/freshbiz/order/list/info?'+params+'" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a> \
				            </td> \
				        </tr>';
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerMessage_ReminderOrder = function(tableID, tabID, jsonArray, replace) {
		//更新Tab页的显示数量
		var count = 0;
		if ( replace ) {
			count = jsonArray.length;
			$('#'+tableID+' tbody').remove();
		} else {
			count = parseInt($('#'+tabID).text()) + jsonArray.length;
		}
		$('#'+tabID).text(count);
		//更新Tab页的内容
		$.each(jsonArray, function(index, mainOrder) {
			var content = '';
			var params = 'mainOrderID=' + mainOrder.wy_main_order_id + '&' + 'shopID=' + mainOrder.wy_shop_id;
			var discountMoney = parseFloat(mainOrder.wy_consumption_money) - parseFloat(mainOrder.wy_actual_money);
			content = '<tbody data-mainorderid="'+mainOrder.wy_main_order_id+'" data-mainorderstatus="'+mainOrder.wy_order_state+'" data-shoopid="'+mainOrder.wy_shop_id+'">';
			content += '<tr class="sep-row"></tr> \
                		<tr class="order-header"> \
                			<td colspan="3"> \
                    			<ul> \
				                    <li> \
				                        <div> \
				                            <strong>订单编号：</strong> \
				                            <span>'+mainOrder.wy_order_number+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong> \
				                            <span>'+mainOrder.wy_recv_name+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>配送地址：</strong> \
				                            <span>'+mainOrder.wy_recv_addr+'</span> \
				                        </div> \
				                    </li> \
				                    <li> \
				                        <div> \
				                            <strong>催单时间：</strong> \
				                            <span>'+mainOrder.wy_reminder_time+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>联系电话：</strong> \
				                            <span>'+mainOrder.wy_recv_phone+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>买家留言：</strong> \
				                            <span>'+mainOrder.wy_user_note+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td colspan="2"> \
                				<ul> \
				                    <li> \
				                        <div> \
				                            <strong>消费金额（元）：</strong> \
				                            <span>'+mainOrder.wy_consumption_money+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>优惠金额（元）：</strong> \
				                            <span>'+discountMoney+'</span> \
				                        </div> \
				                        <div> \
				                            <strong>订单总额（元）：</strong> \
				                            <span>'+mainOrder.wy_actual_money+'</span> \
				                        </div> \
				                    </li> \
				                </ul> \
                			</td> \
                			<td> \
                				<div class="print"> \
                					<a class="btn btn-default btn-sm" href="/admin/freshbiz/order/print/'+mainOrder.wy_shop_id+'/'+mainOrder.wy_main_order_id+'" target="_blank"><span class="fa fa-print"></span>打印发货单</a> \
                				</div> \
                			</td> \
                		</tr>';

            $.each(mainOrder.subOrders, function(subIndex, subOrder) {
				content += '<tr class="text-center" data-goodsid="'+subOrder.wy_goods_id+'"> \
                    			<td>'+subOrder.wy_goods_name+'</td> \
                    			<td>'+subOrder.wy_goods_unit_price+'</td> \
                    			<td>'+subOrder.wy_goods_amount+'</td> \
                    			<td>'+subOrder.wy_goods_total_price+'</td>';
				if ( 0 == subIndex ) {
					content += '<td rowspan="'+mainOrder.subOrders.length+'"><span class="label label-warning">'+mainOrder.wy_order_state_name+'</span></td> \
                    			<td rowspan="'+mainOrder.subOrders.length+'"> \
                    				<div> \
						                <a class="btn btn-success btn-xs bg-green" href="/admin/freshbiz/order/list/info?'+params+'" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a> \
                    				</div> \
                    			</td>';
				}
				content += '</tr>';
			});
			content += '</tbody>';
			$('#'+tableID+' thead').after(content);
		});
	}

	var handlerOrdersCount = function(ordersCount) {
		var recved = $('#recved_order_count');
		if ( recved.text() != ordersCount.recved ) {
			recved.text(ordersCount.recved);
			if ( recved.next().hasClass('hidden') ) {
				recved.next().removeClass('hidden');
			}
		}
		var deliverying = $('#deliverying_order_count');
		if ( deliverying.text() != ordersCount.deliverying ) {
			deliverying.text(ordersCount.deliverying);
			if (deliverying.next().hasClass('hidden') ) {
				deliverying.next().removeClass('hidden');
			}
		}
		var finished = $('#finished_order_count');
		if ( finished.text() != ordersCount.finished ) {
			finished.text(ordersCount.finished);
			if ( finished.next().hasClass('hidden') ) {
				finished.next().removeClass('hidden');
			}
		}
		var canceled = $('#canceled_order_count');
		if ( canceled.text() != ordersCount.canceled ) {
			canceled.text(ordersCount.canceled);
			if ( canceled.next().hasClass('hidden') ) {
				canceled.next().removeClass('hidden');
			}
		}
		var refused = $('#refused_order_count');
		if ( refused.text() != ordersCount.refused ) {
			refused.text(ordersCount.refused);
			if ( refused.next().hasClass('hidden') ) {
				refused.next().removeClass('hidden');
			}
		}
		var reminder = $('#reminder_order_count');
		if ( reminder.text() != ordersCount.reminder ) {
			reminder.text(ordersCount.reminder);
			if ( reminder.next().hasClass('hidden') ) {
				reminder.next().removeClass('hidden');
			}
		}
	}

	//接单
	var recvOrder = function() {
		$('#new_table').on('click', 'tbody tr button#recved_order', function(event) {
			event.preventDefault();
			handlerAjax_ChangeStatus(this, OrderStatus.Status_2, 'new');
		});
	}

	var initRefuseOrderModal = function() {
	 	$('#refused_order_modal').on('show.bs.modal', function(event){
	 		var tbody = $(event.relatedTarget).parent().parent().parent().parent();
	 		var orderNumber = tbody.find('span#order_number').text();
	 		$(this).find('div.modal-body mark').text(orderNumber);
	 	});
	}

	//拒单
	var refuseOrder = function() {
		$('#refused_order_modal').on('click', 'button#refuse_order', function(event) {
			event.preventDefault();
			var button = $('#new_table button#refused_order').get(0);
			handlerAjax_ChangeStatus(button, OrderStatus.Status_6, 'new');
		});
	}

	//配送
	var deliveryingOrder = function() {
		$('#recved_table').on('click', 'tbody tr button#deliverying_order', function(event) {
			event.preventDefault();
			handlerAjax_ChangeStatus(this, OrderStatus.Status_3, 'recved');
		});
	}

	//完成
	var finishOrder = function() {
		$('#deliverying_table').on('click', 'tbody tr button#finished_order', function(event) {
			event.preventDefault();
			handlerAjax_ChangeStatus(this, OrderStatus.Status_4, 'deliverying');
		});
	}

	var handlerOrderTabShow = function() {
		$('#content-right a[data-toggle="pill"]').on('show.bs.tab', function(event) {
			handlerAjax_RefreshOrder(event.target);
		});
	}

	var handlerOrderTabHide = function() {
		$('#content-right a[data-toggle="pill"]').on('hide.bs.tab', function(event) {
			var tab = $(event.target);
			var orderNew = tab.find('div.order-new');
			var controls = tab.attr('aria-controls');
			if ( 'new' == controls 
				|| 'canceled' == controls
				|| 'reminder' == controls ) {
				if ( !orderNew.hasClass('hidden') ) {
					orderNew.addClass('hidden');
				}
			}
		});
	}

	var handlerAjax_ChangeStatus = function(element, toBeStatus, controls) {
		//发送之前进行界面等待
		var tbody = $(element).parent().parent().parent().parent();
		var mainOrderID = tbody.data('mainorderid');
		var mainOrderCurrentStatus = tbody.data('mainorderstatus');
		$(element).attr('tabID', controls);

		$.ajax({
			url: '/admin/freshbiz/order/change/status',
			type: 'POST',
			dataType: 'json',
			data: { shop_id : Client.shop_id, main_order_id : mainOrderID, current_status : mainOrderCurrentStatus, to_be_status : toBeStatus },
			context: $(element),
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				if ( Client.success == data.ret_code ) {
					//移除当前的显示值
					this.parent().parent().parent().parent().remove();
					//更新当前页的数量
					var tabID = this.attr('tabID');
					var orderCount = $('#'+tabID+'_order_count');
					orderCount.text(parseInt(orderCount.text()) - 1);

					var index = this.attr('id').search(/_/i);
					var name = this.attr('id').substr(0, index);
					orderCount = $('#'+name+'_order_count');
					orderCount.text(parseInt(orderCount.text()) + 1);
					var orderNew = orderCount.next();
					if ( orderNew.hasClass('hidden') ) {
						orderNew.removeClass('hidden');
					}
				} else {
					showAlertMessage(data.msg);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				showAlertMessage(textStatus+errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
			});
	}

	var handlerAjax_RefreshOrder = function(element) {
		var tab = $(element);
		var orderNew = tab.find('div.order-new');
		if ( !orderNew.hasClass('hidden') ) {
			orderNew.addClass('hidden');
		}
		var orderStatus = tab.data('orderstatus');
		//新单不需要刷新，因为新订单是实时刷新的
		if ( OrderStatus.Status_1 != orderStatus ) {
			$.ajax({
				url: '/admin/freshbiz/order/list/status',
				type: 'GET',
				dataType: 'json',
				data: { shop_id : Client.shop_id, order_status : orderStatus },
				context: tab,
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				if ( Client.success == data.ret_code ) {
					//刷新Tab的数量
					handlerOrdersCount(data.count);
					var controls = this.attr('aria-controls');
					switch (controls)
					{
					case 'recved':
						handlerMessage_RecvOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					case 'deliverying':
						handlerMessage_DeliveryingOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					case 'finished':
						handlerMessage_FinishOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					case 'canceled':
						handlerMessage_CancelOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					case 'refused':
						handlerMessage_RefuseOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					case 'reminder':
						handlerMessage_ReminderOrder(controls+'_table', controls+'_order_count', data.data, true);
						break;
					}
				} else {
					showAlertMessage(data.msg);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				showAlertMessage(textStatus+errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
			});
		}
	}

	var initAudio = function() {
		// Client.notifyVoice = new Audio("assets/audio/notify.mp3");
		// Client.notifyVoice.preload = "auto";
		// Client.notifyVoice = $('#notify')[0];
		Client.notifyVoice = document.getElementById('notify');
	}

	return {

		init: function(host, port, shop_id, token) {
			
			//初始化变量
			initVariable();

			Client.host = host;
			Client.port = port;
			Client.shop_id = shop_id;
			Client.token = token;
			connect();

			initAudio();

			// 接单按钮事件
			recvOrder();
			// 拒绝按钮事件
			initRefuseOrderModal();
			refuseOrder();
			// 配送订单
			deliveryingOrder();
			// 完成订单
			finishOrder();
			// Tab页事件
			handlerOrderTabShow();
			handlerOrderTabHide();
		}
	}
}();