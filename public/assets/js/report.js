/*
 * 统计报表
 */

var Report = function() {

	var initVariable = function() {
		Report.echartsPaths = 'http://www.10000times.com/assets/lib/echarts-2.2.3';
		Report.durations = 7;
		Report.maxDurations = 30;
		Report.success = 0;
		Report.orderMoneyChart = null;
		Report.orderAmountChart = null;
		Report.financeDataChart = null;
	}

	var initTradeConfig = function() {
		//配置echarts的文件路径
        require.config({
            paths: {
                echarts: Report.echartsPaths
            }
        });

        //得到实际的宽度，设计该宽度
        var contentRightWidth = $('#content-right').width();
        $('#order_money_chart').width(contentRightWidth-64);
        $('#order_amount_chart').width(contentRightWidth-64);
	}

	var initFinanceConfig = function() {
		//配置echarts的文件路径
        require.config({
            paths: {
                echarts: Report.echartsPaths
            }
        });

        //得到实际的宽度，设计该宽度
        var contentRightWidth = $('#content-right').width();
        $('#finance_data_chart').width(contentRightWidth-64);
	}

	var initDateTimePicker = function() {
		$('#start_time').datetimepicker({
			language : 'zh-CN',
			autoclose : true,
			format : 'yyyy-mm-dd hh:ii:ss',
			minuteStep : 1,
			todayBtn : true,
			todayHighlight : true,
		}).on('show', function(event){
			$('#start_time').datetimepicker('setEndDate', new Date());
		});

		$('#end_time').datetimepicker({
			language : 'zh-CN',
			autoclose : true,
			format : 'yyyy-mm-dd hh:ii:ss',
			minuteStep : 1,
			todayBtn : true,
			todayHighlight : true,
		}).on('show', function(event){
			var startTime = $('#start_time').val();
			$('#end_time').datetimepicker('setStartDate', startTime);
			$('#end_time').datetimepicker('setEndDate', new Date());
		});

		//增加开始日期默认值
		var now = moment().format('YYYY-MM-DD');
		var sd = moment().subtract(Report.durations, 'd').format('YYYY-MM-DD');
		$('#start_date').val(sd);
		//绑定日期控件
		$('#start_date').datetimepicker({
			language : 'zh-CN',
			autoclose : true,
			format : 'yyyy-mm-dd',
			minView : 2,
			todayBtn : true,
			todayHighlight : true,
		}).on('show', function(event){
			$('#start_date').datetimepicker('setEndDate', new Date());
		});

		//修改开始日期
		$('#start_date').on('change', function(event){
			var startDate = $(this).val();
			var endDate = moment.min(moment(),moment(startDate).add(Report.durations,'d')).format('YYYY-MM-DD');
			$('#end_date').val(endDate);
		});

		//增加结束日期默认值
		$('#end_date').val(now);
		$('#end_date').datetimepicker({
			language : 'zh-CN',
			autoclose : true,
			format : 'yyyy-mm-dd',
			minView : 2,
			todayBtn : true,
			todayHighlight : true,
		}).on('show', function(event){
			var startDate = $('#start_date').val();
			$('#end_date').datetimepicker('setStartDate', startDate);
			var endDate = moment.min(moment(),moment(startDate).add(Report.maxDurations,'d')).format('YYYY-MM-DD');
			$('#end_date').datetimepicker('setEndDate', endDate);
		});
	}

	var showAlertMessage = function(msg) {
		$('div#alert_msg').after('<div class="alert alert-danger alert-dismissible" role="alert"> \
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
			<span aria-hidden="true">&times;</span></button>'+msg+'</div>');
	}

	var handleSearchOrder = function() {
		$('#order_list').on('click', 'button#search_order', function(event){
			event.preventDefault();
			var params = $('#order_list form').serialize();
			
			$('button#search_order').attr('disabled','true');
			$('button#search_order').html("正在查询中...");

			$('#orderdetail_container').load('/admin/report/trade/tradelist', params, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}

				$('button#search_order').removeAttr('disabled');
				$('button#search_order').html("查 询");
				$('button#search_order').blur();
			});
		});

		$('#order_list').on('click', 'button#clear_search_conditions', function(event){
			event.preventDefault();
			$('#order_list form :text').val('');
			$('#order_list #order_status option').eq(0).attr('selected','selected');
			$('#order_list #reminder_flag option').eq(0).attr('selected','selected');
			$(this).blur();
		});
	}

	var handleTradePagination = function() {
		$('#orderdetail_container').on('click', 'ul li a', function(event){
			event.preventDefault();
			var url = $(this).attr('href');
			$('#orderdetail_container').load(url, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}
			});
		})

		$('#orderreport_container').on('click', 'ul li a', function(event){
			event.preventDefault();
			var url = $(this).attr('href');
			$('#orderreport_container').load(url, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}
			});
		})		
	}

	var handleSearchBizReport = function() {
		$('#biz_repport').on('click', 'button#search_bizreport', function(event){
			event.preventDefault();
			var params = $('#biz_repport form').serialize();

			$('button#search_bizreport').attr('disabled','true');
			$('button#search_bizreport').html("正在生成中...");
			
			Report.orderMoneyChart.showLoading({
				effect : 'whirling'
			});

			Report.orderAmountChart.showLoading({
				effect : 'whirling'
			});

			$.getJSON('/admin/report/trade/bizreport', params, function(data, textStatus, jqXHR){
				if ( Report.success == data.ret_code ) {
					setOrderMoneyChart(data.data.dates, data.data.money, data.data.money_sum);
					setOrderAmountChart(data.data.dates, data.data.amount, data.data.amount_sum);
				} else {
					showAlertMessage(data.msg);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				showAlertMessage(textStatus + " "+ errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
			});

			$('#orderreport_container').load('/admin/report/trade/bizreportlist', params, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}
				Report.orderMoneyChart.hideLoading();
				Report.orderAmountChart.hideLoading();
				$('button#search_bizreport').removeAttr('disabled');
				$('button#search_bizreport').html("生 成");
				$('button#search_bizreport').blur();
			});
		});
	}

	var handleGenerateFinanceData = function() {
		$('#finance_report').on('click', 'button#generate_financedata', function(){
			event.preventDefault();
			var params = $('#finance_report form').serialize();

			$('button#generate_financedata').attr('disabled','true');
			$('button#generate_financedata').html("正在生成中...");

			Report.financeDataChart.showLoading({
				effect : 'whirling'
			});

			$.getJSON('/admin/report/finance/financereport', params, function(data, textStatus, jqXHR){
				if ( Report.success == data.ret_code ) {
					setFinanceDataChart(data.data.dates, data.data.amount, data.data.amount_sum, data.data.consume_money,
						data.data.actual_money, data.data.consume_money_sum, data.data.actual_money_sum);
				} else {
					showAlertMessage(data.msg);
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				showAlertMessage(textStatus + " "+ errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
				
			});

			$('#orderreport_container').load('/admin/report/finance/financereportlist', params, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}
				Report.financeDataChart.hideLoading();
				$('button#generate_financedata').removeAttr('disabled');
				$('button#generate_financedata').html("生 成");
				$('button#generate_financedata').blur();
			});
		});
	}

	var handleFinancePagination = function() {
		$('#orderreport_container').on('click', 'ul li a', function(event){
			event.preventDefault();
			var url = $(this).attr('href');
			$('#orderreport_container').load(url, function(responseText,textStatus,jqXHR){
				if ( "error" == textStatus ) {
					showAlertMessage(jqXHR.status + " " + jqXHR.statusText);
				}
			});
		})	
	} 

	var getChartOption = function(xDatas,yDatas,title) {
		if ( xDatas.length > 10 ) {
			var option = {
		        tooltip : {
		            show : true,
		            trigger : 'axis'
		        },
		        legend : {
		        	selectedMode : false,
		            data:[title]
		        },
		        toolbox : {
		        	show : true,
		        	feature : {
		        		magicType : { show : true, type : ['line', 'bar'] },
		        		restore : { show : true },
		        		saveAsImage : { show : true }
		        	}
		        },
		        xAxis : [
		            {
		                type : 'category',
		                axisTick : {
		                	interval : 0
		                },
		                axisLabel : {
		                	interval : 0,
		                	rotate : 40
		                },
		                data : xDatas,           
		            }
		        ],
		        yAxis : [
		            {
		                type : 'value',
		                scale : true
		            }
		        ],
		        series : [
		            {
		                name : title,
		                type :'line',
		                clickable : false,
		                data : yDatas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		        ]
		    };

		    return option;
		} else {
			var option = {
		        tooltip : {
		            show : true,
		            trigger : 'axis'
		        },
		        legend : {
		        	selectedMode : false,
		            data:[title]
		        },
		        toolbox : {
		        	show : true,
		        	feature : {
		        		magicType : { show : true, type : ['line', 'bar'] },
		        		restore : { show : true },
		        		saveAsImage : { show : true }
		        	}
		        },
		        xAxis : [
		            {
		                type : 'category',
		                axisTick : {
		                	interval : 0
		                },
		                axisLabel : {
		                	interval : 0
		                },
		                data : xDatas,           
		            }
		        ],
		        yAxis : [
		            {
		                type : 'value',
		                scale : true
		            }
		        ],
		        series : [
		            {
		                name : title,
		                type :'line',
		                clickable : false,
		                data : yDatas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		        ]
		    };

		    return option;
		}
	}

	var handleOrderMoneyChart = function() {
		
		// 设置日期范围
		var startDate = $('#start_date').val();
		var endDate = $('#end_date').val();
		var subTitle = "从" + startDate + "到" + endDate;
		$('#order_money h5').html(subTitle);

       	// 初始化数据
       	var dates = new Array();
       	var money = new Array();
       	for (var i = 0; i <= Report.durations; i++) {
       		dates.push(startDate);
       		money.push("0.000");
       		startDate = moment(startDate).add(1, 'd').format('YYYY-MM-DD');
       	};

        require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            function (ec) {
                Report.orderMoneyChart = ec.init(document.getElementById('order_money_chart')); 
        		var option = getChartOption(dates, money, '订单金额');
                Report.orderMoneyChart.setOption(option); 
            }
        );
	}

	var setOrderMoneyChart = function(dates,money,moneySum) {
		
		if ( 'object' == typeof(dates)
			&& dates.constructor == Array
			&& 'object' == typeof(money)
			&& money.constructor == Array
			&& dates.length > 0
			&& money.length > 0 ) {

			var startDate = dates[0];
			var endDate = dates[dates.length - 1];
			var subTitle = "从" + startDate + "到" + endDate;
			$('#order_money h5').html(subTitle);
			$('#money_sum').html(moneySum);

			var option = getChartOption(dates, money, '订单金额');
			Report.orderMoneyChart.setOption(option, true);
		} else {
			handleOrderMoneyChart();
		}
	}

	var handleOrderAmountChart = function() {
       	// 设置日期范围
		var startDate = $('#start_date').val();
		var endDate = $('#end_date').val();
		var subTitle = "从" + startDate + "到" + endDate;
		$('#order_amount h5').html(subTitle);

   		//初始化数据
   		var dates = new Array();
       	var amount = new Array();
       	for (var i = 0; i <= Report.durations; i++) {
       		dates.push(startDate);
       		amount.push(0);
       		startDate = moment(startDate).add(1, 'd').format('YYYY-MM-DD');
       	};

        require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            function (ec) {
                Report.orderAmountChart = ec.init(document.getElementById('order_amount_chart')); 
                var option = getChartOption(dates, amount, '订单笔数');
                Report.orderAmountChart.setOption(option);
            }
        );
	}

	var setOrderAmountChart = function(dates,amount,amountSum) {
		if ( 'object' == typeof(dates)
			&& dates.constructor == Array
			&& 'object' == typeof(amount)
			&& amount.constructor == Array
			&& dates.length > 0
			&& amount.length > 0 ) {
			
			var startDate = dates[0];
			var endDate = dates[dates.length - 1];
			var subTitle = "从" + startDate + "到" + endDate;
			$('#order_amount h5').html(subTitle);
			$('#amount_sum').html(amountSum);

			var option = getChartOption(dates, amount, '订单笔数');
            Report.orderAmountChart.setOption(option, true);
		} else {
			handleOrderAmountChart();
		}
	}

	var getFinanceDataChartOption = function(xDatas,y1Datas,y2Datas,titles) {
		if ( xDatas.length > 10 ) {
			var option = {
		        tooltip : {
		            show : true,
		            trigger : 'axis'
		        },
		        legend : {
		            data : titles
		        },
		        toolbox : {
		        	show : true,
		        	feature : {
		        		magicType : { show : true, type : ['line', 'bar'] },
		        		restore : { show : true },
		        		saveAsImage : { show : true }
		        	}
		        },
		        calculable : true,
		        xAxis : [
		            {
		                type : 'category',
		                axisTick : {
		                	interval : 0
		                },
		                axisLabel : {
		                	interval : 0,
		                	rotate : 40
		                },
		                data : xDatas,           
		            }
		        ],
		        yAxis : [
		            {
		                type : 'value',
		                scale : true
		            }
		        ],
		        series : [
		            {
		                name : titles[0],
		                type :'line',
		                clickable : false,
		                data : y1Datas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		            {
		                name : titles[1],
		                type :'line',
		                clickable : false,
		                data : y2Datas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		        ]
		    };

		    return option;
		} else {
			var option = {
		        tooltip : {
		            show : true,
		            trigger : 'axis'
		        },
		        legend : {
		            data : titles
		        },
		        toolbox : {
		        	show : true,
		        	feature : {
		        		magicType : { show : true, type : ['line', 'bar'] },
		        		restore : { show : true },
		        		saveAsImage : { show : true }
		        	}
		        },
		        calculable : true,
		        xAxis : [
		            {
		                type : 'category',
		                axisTick : {
		                	interval : 0
		                },
		                axisLabel : {
		                	interval : 0
		                },
		                data : xDatas,           
		            }
		        ],
		        yAxis : [
		            {
		                type : 'value',
		                scale : true
		            }
		        ],
		        series : [
		            {
		                name : titles[0],
		                type :'line',
		                clickable : false,
		                data : y1Datas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		            {
		                name : titles[1],
		                type :'line',
		                clickable : false,
		                data : y2Datas,
		                markPoint : {
		                	clickable : false,
		                	data : [
		                		{ type : 'max', name: '最大值'},
		                		{ type : 'min', name: '最小值'}
		                	]
		                },
		                smooth : true
		            },
		        ]
		    };

		    return option;
		}
	}

	var handleFinanceDataChart = function() {
		// 设置日期范围
		var startDate = $('#start_date').val();
		var endDate = $('#end_date').val();
		var subTitle = "从" + startDate + "到" + endDate;
		$('#finance_report div.chart-title h5').html(subTitle);

       	// 初始化数据
       	var dates = new Array();
       	var money = new Array();
       	for (var i = 0; i <= Report.durations; i++) {
       		dates.push(startDate);
       		money.push("0.000");
       		startDate = moment(startDate).add(1, 'd').format('YYYY-MM-DD');
       	};

        require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            function (ec) {
                Report.financeDataChart = ec.init(document.getElementById('finance_data_chart')); 
        		var titles = new Array();
        		titles.push('消费金额','实际金额');
        		var option = getFinanceDataChartOption(dates, money, money, titles);
                Report.financeDataChart.setOption(option); 
            }
        );
	}

	var setFinanceDataChart = function(dates,amount,amoutSum,consumeMoney,actualMoney,consumeMoneySum,actualMoneySum) {
		if ( 'object' == typeof(dates)
			&& dates.constructor == Array
			&& 'object' == typeof(consumeMoney)
			&& consumeMoney.constructor == Array
			&& 'object' == typeof(actualMoney)
			&& actualMoney.constructor == Array
			&& dates.length > 0
			&& consumeMoney.length > 0
			&& actualMoney.length > 0 ) {

			var startDate = dates[0];
			var endDate = dates[dates.length - 1];
			var subTitle = "从" + startDate + "到" + endDate;
			$('#finance_report div.chart-title h5').html(subTitle);

			$('#amount_sum').html(amoutSum);
			$('#consume_money_sum').html(consumeMoneySum);
			$('#actual_money_sum').html(actualMoneySum);

			var titles = new Array();
    		titles.push('消费金额','实际金额');
    		var option = getFinanceDataChartOption(dates, consumeMoney, actualMoney, titles);
            Report.financeDataChart.setOption(option, true); 
		} else {
			handleFinanceDataChart();
		}
	}

	return {
		
		initTrade : function() {
		
			initVariable();

			initTradeConfig();

			initDateTimePicker();

			handleSearchOrder();

			handleTradePagination();

			handleSearchBizReport();

			handleOrderMoneyChart();

			handleOrderAmountChart();
		},

		initFinance : function() {
			
			initVariable();

			initFinanceConfig();

			initDateTimePicker();

			handleGenerateFinanceData();

			handleFinancePagination();

			handleFinanceDataChart();
		},
	}

}();