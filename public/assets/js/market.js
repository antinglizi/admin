/*
 * 营销中心的管理
 */

var Market = function() {

	//初始化变量
	var initVariable = function() {
		Market.success = 0;
		Market.enable = "已报名";
		Market.disenable = "未报名";
	}

	// 处理活动报名
 	var handleActivity = function() {
 		$('#activity_list_table tbody').on('click', 'button#participate_activity', function(event){
 			event.preventDefault();
 			var tbody = $(this).parent().parent().parent().parent();
 			var shopID = tbody.data('shopid');
 			var activityID = tbody.data('activityid');

 			$.ajax({
				url: '/admin/market/activity/participate',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID, activity_id : activityID },
				context: tbody,
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				var activityName = tbody.data('activityname');
				if ( Market.success == data.ret_code ) {
					this.find('span.label').text(Market.enable);
					var content = '<div> \
								       <button id="cancel_activity" class="btn btn-danger btn-sm"><span class="fa fa-minus-circle"><span class="pl-5">取消</span></span></button> \
								   </div>';
					this.find('tr.text-center td:last').html(content);
				}
				App.showTipMessage(data.msg+activityName);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				App.showTipMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
			});
 		});

 		$('#activity_list_table tbody').on('click', 'button#cancel_activity', function(event){
 			event.preventDefault();
 			var tbody = $(this).parent().parent().parent().parent();
 			var shopID = tbody.data('shopid');
 			var activityID = tbody.data('activityid');

 			$.ajax({
				url: '/admin/market/activity/cancel',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID, activity_id : activityID },
				context: tbody,
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				var activityName = tbody.data('activityname');
				if ( Market.success == data.ret_code ) {
					this.find('span.label').text(Market.disenable);
					var content = '<div> \
								       <button id="participate_activity" class="btn btn-success btn-sm bg-green"><span class="fa fa-plus-circle"><span class="pl-5">报名</span></span></button> \
								   </div>';
					this.find('tr.text-center td:last').html(content);
				}
				App.showTipMessage(data.msg+activityName);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				App.showTipMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
			});
 		});
 	}

	return {
		initActivity: function() {
			
			initVariable();

			handleActivity();
		},
	}

}();