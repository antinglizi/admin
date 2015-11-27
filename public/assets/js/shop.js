/*
* 店铺管理
 */
var Shop = function() {

	var localProvince;
	var localCity;
	var localDistrict;

	var initVariable = function() {
		Shop.imgURL = 'http://image.10000times.com/newserver/';
		Shop.success = 0;
	}

	var initDateTimePicker = function() {
		// $('#shop_add_modal #shop_open_begin').datetimepicker({
		// 	language : 'zh-CN',
		// 	autoclose : true,
		// 	format : 'yyyy-mm-dd hh:ii',
		// 	startView : 1,
		// 	minView : 0,
		// 	maxView : 1,
		// 	minuteStep : 15,
		// });

		// $('#shop_add_modal #shop_open_end').datetimepicker({
		// 	language : 'zh-CN',
		// 	autoclose : true,
		// 	format : 'hh:ii',
		// 	startView : 1,
		// 	minView : 0,
		// 	maxView : 1,
		// 	minuteStep : 15,
		// });

		// $('#datetimepicker').datetimepicker({
		// 	language : 'zh-CN',
		// 	autoclose : true,
		// 	format : 'yyyy-mm-dd hh:ii',
		// 	minuteStep : 15,
		// 	todayBtn : true,
		// 	todayHighlight : true,
		// });

		// $('body').removeClass("modal-open");
	}

	var initTimePicker = function() {
		$('#shop_open_begin').timepicker({
			show2400 : true,
			timeFormat : 'H:i',
			className : 'form-control',
			step : 15,
			useSelect : true,
		});

		$('#shop_open_end').timepicker({
			show2400 : true,
			timeFormat : 'H:i',
			className : 'form-control',
			step : 15,
			useSelect : true,
		});

		$('#shop_delivery_begin').timepicker({
			show2400 : true,
			timeFormat : 'H:i',
			className : 'form-control',
			step : 15,
			useSelect : true,
		});

		$('#shop_delivery_end').timepicker({
			show2400 : true,
			timeFormat : 'H:i',
			className : 'form-control',
			step : 15,
			useSelect : true,
		});
	}

	var initMap = function(mapContainer, mapCity) {
		map = new BMap.Map(mapContainer);    // 创建Map实例
		map.centerAndZoom(mapCity);  		// 初始化地图,设置中心点坐标和地图级别
		map.setCurrentCity(mapCity);          // 设置地图显示的城市 此项是必须设置的
		map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
		map.addControl(new BMap.NavigationControl());
		map.enableScrollWheelZoom();     //开启鼠标滚轮缩放
		map.enableKeyboard();			//启用键盘操作

		mapGeo = new BMap.Geocoder();
	}

	var initLocalCity = function() {
		var locationURL = 'http://api.map.baidu.com/location/ip?ak=hwGS60zBnBRh5YZWyhXWBRAK&coor=bd09ll';
		$.get(locationURL, function(data,textStatus,jqXHR){
			Shop.localProvince = data.content.address_detail.province;
			Shop.localCity =  data.content.address_detail.city;
			Shop.localDistrict = data.content.address_detail.district;
			initMap('shop_map', Shop.localCity);
		},'jsonp');
	}

	var showAlertMessage = function(msg) {
		$('#content-right div#alert_msg').after('<div class="alert alert-danger alert-dismissible" role="alert"> \
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
			<span aria-hidden="true">&times;</span></button>'+msg+'</div>');
	}

	// var showTipMessage = function(msg) {
	// 	$('#info_modal p').text(msg);
	// 	$('#info_modal').modal();
	// }

	var initModal = function() {
		$('#shop_delete_modal').on('show.bs.modal', function(event){
			var tbody = $(event.relatedTarget).parent().parent().parent().parent();
			var shopName = tbody.data('shopname');
			var shopID = tbody.data('shopid');
			$(this).find('div.modal-body mark').text(shopName);
			$(this).find('button#delete_shop').data('shopid', shopID);
		});

		$('#shop_add_modal').on('show.bs.modal', function(event){
			//获取店铺类型和区域信息

			//初始化时间控件
			App.initTimePicker();

			$('#shop_type').load('/admin/manage/shop/type', function(responseText,textStatus,jqXHR){
				var shopTypeValue = $('#shop_type').data('value');
				if ( "" != shopTypeValue ) {
					$('#shop_type option').each(function(){
						if ( shopTypeValue == $(this).val() ) {
							$(this).attr('selected','selected');
						}
					});
				}
			});

			var regionLevel = $('#shop_province').data('regionlevel');
			var regionID = 0;
			$('#shop_province').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
				if ( 'success' == textStatus ) {
					var shopProvince = $('#shop_province').data('value');
					$('#shop_province option').each(function(){
						if ( "" == shopProvince ) {
							if ( Shop.localProvince == $(this).text() ) {
								$(this).attr('selected','selected');
							}
						} else {
							if ( shopProvince == $(this).val() ) {
								$(this).attr('selected','selected');
							}
						}
					});
					var regionLevel = $('#shop_city').data('regionlevel');
					var regionID = $(this).val();
					$('#shop_city').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
						if ( 'success' == textStatus ) {
							//初始化地图信息
							//可以根据IP定位之后，初始化地图信息
							var shopCity = $('#shop_city').data('value');
							$('#shop_city option').each(function(){
								if ( "" == shopCity ) {
									if ( Shop.localCity == $(this).text() ) {
										$(this).attr('selected','selected');
									}
								} else {
									if ( shopCity == $(this).val() ) {
										$(this).attr('selected','selected');
									}
								}
							});

							var regionLevel = $('#shop_district').data('regionlevel');
							var regionID = $(this).val();
							$('#shop_district').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
								if ( 'success' == textStatus ) {
									var shopDistrict = $('#shop_district').data('value');
									$('#shop_district option').each(function(){
										if ( "" == shopDistrict ) {
											if ( Shop.localDistrict == $(this).text() ) {
												$(this).attr('selected','selected');
											}
										} else {
											if ( shopDistrict == $(this).val() ) {
												$(this).attr('selected','selected');
											}
										}
									});
									if ( "" != $('#shop_longitude').val() && 
										"" != $('#shop_latitude').val() ) {
										var point = new BMap.Point($('#shop_longitude').val(), $('#shop_latitude').val());
										map.centerAndZoom(point, 18);
										if ( "undefined" == typeof(mapMarker) || null == mapMarker ) {
											mapMarker = new BMap.Marker(point,{
												enableDragging : true,
												enableClicking : true,
												raiseOnDrag : true,
												draggingCursor : 'move',
												title : '您店铺的坐标位置'
											});
											mapMarker.addEventListener('dragend',function(event){
												$('#shop_add_modal #shop_longitude').val(event.point.lng);
												$('#shop_add_modal #shop_latitude').val(event.point.lat);
											});
											map.addOverlay(mapMarker);
										} else {
											mapMarker.setPosition(point);
										}
									} else {
										var mapCity = $(this).find('option:selected').text();
										map.centerAndZoom(mapCity, 12);
									}
								}
								// console.log(responseText);
								// console.log(textStatus);
								// console.log(jqXHR);
							});
						}
						// console.log(responseText);
						// console.log(textStatus);
						// console.log(jqXHR);
					});
				}
				// console.log(responseText);
				// console.log(textStatus);
				// console.log(jqXHR);
			});		
		});

		$('#shop_add_modal').on('shown.bs.modal', function(event){
			$('input#shop_name').focus();
		});
	}

	var handleImgInfo = function() {
		$('#shop_add_modal #shop_icon').on('change', function(event){
			var data = new FormData();
			//还有其他参数
			data.append('func_no', 700100);
			//这里可以进行图片大小和样式判断
			var fileList = $(this)[0].files;
			if ( fileList.length <= 0 ) {
				return false;
			}
			//这里可以是多文件，循环加入即可
			data.append('file_data', fileList[0]);
			
			$('#shop_add_modal #shop_icon_upload').find('span.pl-5').text('正在上传...');
         	$('#shop_add_modal #img-loading').removeClass('hidden');
         	var uploadURL = Shop.imgURL + 'uploadImg.php';

         	$.ajax({
				url: uploadURL,
				type: 'POST',
				dataType: 'json',
				data: data,
				cache: false,
            	contentType: false,    //不可缺
            	processData: false,    //不可缺
			})
			.done(function(data, textStatus, jqXHR) {
				if ( Shop.success == data.ret_code ) {
					$.each(data.data, function(index, item){
						var showURL = Shop.imgURL + 'showImg.php?img_id=' + item.img_name;
						$('#shop_add_modal #img-preview').find('img').attr('src', showURL);
						$('#shop_add_modal #img-preview').find('img').val($('#shop_add_modal #shop_icon').val());
						$('#shop_add_modal #shop_icon_name').val(item.img_name);
					});
					$('#shop_add_modal #img-preview').find('span').addClass('hidden');
					$('#shop_add_modal #img-preview').find('img').removeClass('hidden');
					$('#shop_add_modal #shop_icon_upload').find('span.pl-5').text('更改');
				} else {
					$('#shop_add_modal #img-preview').find('span').text(data.msg);
					$('#shop_add_modal #img-preview').find('span').removeClass('hidden');
					$('#shop_add_modal #img-preview').find('img').addClass('hidden');
					$('#shop_add_modal #shop_icon_upload').find('span.pl-5').text('重新上传');
					$('#shop_add_modal #shop_icon_name').val('');
				}
				// $('#shop_add_modal #shop_icon_upload').addClass('hidden');
				// $('#shop_add_modal #shop_icon_delete').removeClass('hidden');

				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				$('#shop_add_modal #img-preview').find('span').text(textStatus);
				$('#shop_add_modal #img-preview').find('span').removeClass('hidden');
				$('#shop_add_modal #img-preview').find('img').addClass('hidden');
				$('#shop_add_modal #shop_icon_upload').find('span.pl-5').text('重新上传');
				$('#shop_add_modal #shop_icon_name').val('');
				// showAlertMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				// console.log("complete");
				$('#shop_add_modal #img-loading').addClass('hidden');
				// $('#shop_add_modal #shop_icon').val('');
			});
		});

		$('#shop_add_modal #shop_icon_delete').on('click', function(event){
			// event.preventDefault();
			$('#shop_add_modal #img-preview').find('span').removeClass('hidden');
			$('#shop_add_modal #img-preview').find('img').addClass('hidden');
			$('#shop_add_modal #shop_icon_upload').removeClass('hidden');
			$('#shop_add_modal #shop_icon_delete').addClass('hidden');
			$('#shop_add_modal #shop_icon').val('');
		});

	}

	var handleAddrInfo = function() {
		$('#shop_add_modal #shop_province').on('change', function(event){
			var regionID = $(this).val();
			var regionLevel = $('#shop_add_modal #shop_city').data('regionlevel');
			$('#shop_add_modal #shop_city').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
				if ( 'success' == textStatus ) {
					var mapCity = $(this).find('option:selected').text();
					map.centerAndZoom(mapCity, 11);
					map.setCurrentCity(mapCity);
					var regionID = $(this).val();
					var regionLevel = $('#shop_add_modal #shop_district').data('regionlevel');
					$('#shop_add_modal #shop_district').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
						if ( 'success' == textStatus ) {
							var mapCity = $(this).find('option:selected').text();
							map.centerAndZoom(mapCity, 12);
						}
						// console.log(responseText);
						// console.log(textStatus);
						// console.log(jqXHR);
					});
				}
				// console.log(responseText);
				// console.log(textStatus);
				// console.log(jqXHR);
			});
		});

		$('#shop_add_modal #shop_city').on('change', function(event){
			var mapCity = $(this).find('option:selected').text();
			map.centerAndZoom(mapCity, 11);
			map.setCurrentCity(mapCity);
			var regionID = $(this).val();
			var regionLevel = $('#shop_add_modal #shop_district').data('regionlevel');
			$('#shop_add_modal #shop_district').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
				if ( 'success' == textStatus ) {
					var mapCity = $(this).find('option:selected').text();
					map.centerAndZoom(mapCity, 12);
				}
				// console.log(responseText);
				// console.log(textStatus);
				// console.log(jqXHR);
			});
		});

		$('#shop_add_modal #shop_district').on('change', function(event){
			var mapCity = $(this).find('option:selected').text();
			map.centerAndZoom(mapCity, 12);
		});

		$('#shop_add_modal #shop_detail_addr').on('click', function(event){
			event.preventDefault();
			// var mapGeo = new BMap.Geocoder();
			var mapCity = $('#shop_add_modal #shop_city').find('option:selected').text();
			var district = $('#shop_add_modal #shop_district').find('option:selected').text();
			var detailAddr = district + $('#shop_add_modal #shop_addr').val();
			
			mapGeo.getPoint(detailAddr, function(point) {
				if ( point ) {
					$('#shop_add_modal #shop_longitude').val(point.lng);
					$('#shop_add_modal #shop_latitude').val(point.lat);
					map.centerAndZoom(point, 18);
					if ( "undefined" == typeof(mapMarker) || null == mapMarker ) {
						mapMarker = new BMap.Marker(point,{
							enableDragging : true,
							enableClicking : true,
							raiseOnDrag : true,
							draggingCursor : 'move',
							title : '您店铺的坐标位置'
						});
						mapMarker.addEventListener('dragend',function(event){
							$('#shop_add_modal #shop_longitude').val(event.point.lng);
							$('#shop_add_modal #shop_latitude').val(event.point.lat);
						});
						map.addOverlay(mapMarker);
					} else {
						mapMarker.setPosition(point);
					}
				} else {
					alert("您选择地址没有解析到结果!");
				}
			}, mapCity);
		});
	}

	var addShop = function() {
		$('#shop_add').on('submit', function(event){
			$(':submit').attr('disabled','disabled');
			$(':submit').val('正在提交中...');
		});
	}

	var deleteShop = function() {
		$('#shop_delete_modal').on('click', 'button#delete_shop', function(event){
			event.preventDefault();
			var shopID = $(this).data('shopid');
			var tbody;
			$('#shop_list_table tbody').each(function(){
				if ( shopID == $(this).data('shopid') ) {
					tbody = $(this);
				}
			});

			$.ajax({
				url: '/admin/manage/shop/delete',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID },
				context: tbody,
			})
			.done(function(data, textStatus, jqXHR) {
				var shopName = tbody.data('shopname');
				if ( Shop.success == data.ret_code ) {
					this.remove();
				}
				$('#shop_delete_modal').modal('hide');
				App.showTipMessage(shopName+data.msg);
				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				$('#shop_delete_modal').modal('hide');
				App.showTipMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				// console.log("complete");
			});
		});
	}

	var handleChangeShopStatus = function() {
		$('#shop_list_table tbody td').on('change', 'div.shop-status select', function(event){
			var shopStatus = $(this).val();
			var tbody = $(this).parent().parent().parent().parent();
			var shopID = tbody.data('shopid');

			$.ajax({
				url: '/admin/manage/shop/change/status',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID, shop_status : shopStatus},
				context: $(this),
			})
			.done(function(data, textStatus, jqXHR) {
				var tbody = this.parent().parent().parent().parent();
				var shopName = tbody.data('shopname');
				if ( Shop.success == data.ret_code ) {
					this.attr('data-shopstatus', this.val());
				} else {
					var previousShopStatus = this.data('shopstatus');
					this.find('option').each(function(index, element){
						if ( previousShopStatus == $(element).val() ) {
							$(element).attr('selected', 'selected');
						}
					});
				}
				App.showTipMessage(shopName+data.msg);
				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
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
		init : function() {

			// initDateTimePicker();

			initVariable();
			
			// initTimePicker();

			initModal();

			initLocalCity();

			handleImgInfo();

			handleAddrInfo();

			addShop();

			deleteShop();

			handleChangeShopStatus();
		},

		initShopInfo : function() {

		},
	}
}();