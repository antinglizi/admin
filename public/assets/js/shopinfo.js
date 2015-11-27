/*
*店铺信息管理
 */

var ShopInfo = function () {

	var initVariable = function() {
		ShopInfo.imgURL = 'http://image.10000times.com/newserver/';
		ShopInfo.success = 0;
	}

	var initMap = function(mapContainer) {
		map = new BMap.Map(mapContainer);    // 创建Map实例
		var point = new BMap.Point($('#shop_longitude').val(), $('#shop_latitude').val());
		map.centerAndZoom(point, 18); 		// 初始化地图,设置中心点坐标和地图级别
		map.setCurrentCity($('#shop_city').text());          // 设置地图显示的城市 此项是必须设置的
		map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
		map.addControl(new BMap.NavigationControl());
		map.enableScrollWheelZoom();     //开启鼠标滚轮缩放
		map.enableKeyboard();			//启用键盘操作

		mapGeo = new BMap.Geocoder();

		if ( "undefined" == typeof(mapMarker) || null == mapMarker ) {
			mapMarker = new BMap.Marker(point,{
				enableDragging : true,
				enableClicking : true,
				raiseOnDrag : true,
				draggingCursor : 'move',
				title : '您店铺的坐标位置'
			});
			mapMarker.addEventListener('dragend',function(event){
				$('#shop_longitude').val(event.point.lng);
				$('#shop_latitude').val(event.point.lat);
			});
			map.addOverlay(mapMarker);
		} else {
			mapMarker.setPosition(point);
		}
	}
	
	var handleImgInfo = function() {
		$('#shop_basic_info_modify #shop_icon').on('change', function(){
			console.log(event);
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

			$('#shop_basic_info_modify #shop_icon_upload').find('span.pl-5').text('正在上传...');
         	$('#shop_basic_info_modify #img-loading').removeClass('hidden');
         	var uploadURL = ShopInfo.imgURL + 'uploadImg.php';

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
				console.log("success");
				console.log(data);
				if ( ShopInfo.success == data.ret_code ) {
					$.each(data.data, function(index, item){
						var showURL = ShopInfo.imgURL + 'showImg.php?img_id=' + item.img_name;
						$('#shop_basic_info_modify #img-preview').find('img').attr('src', showURL);
						$('#shop_basic_info_modify #img-preview').find('img').val($('#shop_basic_info_modify #shop_icon').val());
						$('#shop_basic_info_modify #shop_icon_name').val(item.img_name);
					});
					$('#shop_basic_info_modify #img-preview').find('span').addClass('hidden');
					$('#shop_basic_info_modify #img-preview').find('img').removeClass('hidden');
					$('#shop_basic_info_modify #shop_icon_upload').find('span.pl-5').text('更改');
				} else {
					$('#shop_basic_info_modify #img-preview').find('span').text(data.msg);
					$('#shop_basic_info_modify #img-preview').find('span').removeClass('hidden');
					$('#shop_basic_info_modify #img-preview').find('img').addClass('hidden');
					$('#shop_basic_info_modify #shop_icon_upload').find('span.pl-5').text('重新上传');
					$('#shop_basic_info_modify #shop_icon_name').val('');
				}
				// $('#shop_basic_info_modify #shop_icon_upload').addClass('hidden');
				// $('#shop_basic_info_modify #shop_icon_delete').removeClass('hidden');

				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				$('#shop_basic_info_modify #img-preview').find('span').text(textStatus);
				$('#shop_basic_info_modify #img-preview').find('span').removeClass('hidden');
				$('#shop_basic_info_modify #img-preview').find('img').addClass('hidden');
				$('#shop_basic_info_modify #shop_icon_upload').find('span.pl-5').text('重新上传');
				$('#shop_basic_info_modify #shop_icon_name').val('');
				// showAlertMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
				$('#shop_basic_info_modify #img-loading').addClass('hidden');
				// $('#shop_basic_info_modify #shop_icon').val('');
			});
		});

		$('#shop_basic_info_modify #shop_icon_delete').on('click', function(event){
			// event.preventDefault();
			$('#shop_basic_info_modify #img-preview').find('span').removeClass('hidden');
			$('#shop_basic_info_modify #img-preview').find('img').addClass('hidden');
			$('#shop_basic_info_modify #shop_icon_upload').removeClass('hidden');
			$('#shop_basic_info_modify #shop_icon_delete').addClass('hidden');
			$('#shop_icon').val('');
		});
	}

	var handleAddrInfo = function() {
		$('#shop_province').on('change', function(event){
			var regionID = $(this).val();
			var regionLevel = $('#shop_city').data('regionlevel');
			$('#shop_city').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
				if ( 'success' == textStatus ) {
					var mapCity = $(this).find('option:selected').text();
					map.centerAndZoom(mapCity, 11);
					map.setCurrentCity(mapCity);
					var regionID = $(this).val();
					var regionLevel = $('#shop_district').data('regionlevel');
					$('#shop_district').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
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

		$('#shop_city').on('change', function(event){
			var mapCity = $(this).find('option:selected').text();
			map.centerAndZoom(mapCity, 11);
			map.setCurrentCity(mapCity);
			var regionID = $(this).val();
			var regionLevel = $('#shop_district').data('regionlevel');
			$('#shop_district').load('/admin/manage/shop/region/'+regionID+'/'+regionLevel, function(responseText,textStatus,jqXHR){
				if ( 'success' == textStatus ) {
					var mapCity = $(this).find('option:selected').text();
					map.centerAndZoom(mapCity, 12);
				}
				// console.log(responseText);
				// console.log(textStatus);
				// console.log(jqXHR);
			});
		});

		$('#shop_district').on('change', function(event){
			var mapCity = $(this).find('option:selected').text();
			map.centerAndZoom(mapCity, 12);
		});

		$('#shop_detail_addr').on('click', function(event){
			event.preventDefault();
			// var mapGeo = new BMap.Geocoder();
			var mapCity = $('#shop_city').find('option:selected').text();
			var district = $('#shop_district').find('option:selected').text();
			var detailAddr = district + $('#shop_addr').val();
			
			mapGeo.getPoint(detailAddr, function(point) {
				if ( point ) {
					$('#shop_longitude').val(point.lng);
					$('#shop_latitude').val(point.lat);
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
							$('#shop_longitude').val(event.point.lng);
							$('#shop_latitude').val(event.point.lat);
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

	var handleShopInfoTab = function(){
		$('#shop_info').on('shown.bs.tab', function(event){
			console.log(event);
			var shopInfoType = $(event.target).data('index');
			Cookies.set('shop_info_type', shopInfoType, { expires: 1, path: ''});
		});

		var shopInfoType = Cookies.get('shop_info_type');
		if ( undefined == shopInfoType ) {
			$('#shop_info a:first').tab('show');
		} else {
			$('#shop_info li:eq('+shopInfoType+') a').tab('show');
		}
	}

	return {
		init : function() {

			initVariable();
			
			//初始化时间控件
			App.initTimePicker();

			handleImgInfo();

			handleAddrInfo();

			handleShopInfoTab();

			initMap('shop_map');
		}
	}
}();