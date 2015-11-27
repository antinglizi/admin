/*
* 商品信息管理
 */

var Goods = function() {

	var initVariable = function() {
		Goods.imgURL = 'http://image.10000times.com/newserver/';
		Goods.success = 0;
	}
	
	var handleShopShow = function() {

		$('#shop_select').on('click dblclick', function(event){
			$(this).blur();
			var span = $(this).find('span.pl-5');
			if ( span.hasClass('fa-angle-double-down') ) {
				span.removeClass('fa-angle-double-down');
				span.addClass('fa-angle-double-up');
			} else {
				span.removeClass('fa-angle-double-up');
				span.addClass('fa-angle-double-down');
			}
		});

		$('#shop_select_submit').on('click', function(event) {
			var shopIdArray = new Array();
			var content = "";
			$('#shop_show :checkbox:checked').each(function(index, element) {
				var vaule = $(this).val();
				shopIdArray[index] = vaule;
				var text = $(this).parent().find('span').text();
				content += '<li value="'+$(this).val()+'"><span class="label label-success">'+text+'</span></li>';
			});
			$('#shop_selected ul').html(content);	
			$('#shop_id').val(JSON.stringify(shopIdArray));
			$('#shop_selected').removeClass('hidden');
			$('#shop_select').click();
		});

		var shopIDs = $('#shop_id').val();
		if ( "" != shopIDs ) {
			var shopIdArray = JSON.parse(shopIDs);
			var content = "";
			$('#shop_show :checkbox').each(function(index, element) {
				var vaule = $(this).val();
				var flag = false;
				for (var i = 0; i < shopIdArray.length; i++) {
					if ( shopIdArray[i] == vaule ) {
						flag = true;
						$(this).attr('checked', 'checked');
						break;
					}
				};
				if ( flag ) {
					var text = $(this).parent().find('span').text();
					content += '<li value="'+$(this).val()+'"><span class="label label-success">'+text+'</span></li>';
				}
			});

			$('#shop_selected ul').html(content);	
			$('#shop_selected').removeClass('hidden');
			$('#shop_select').click();
		}
	}

	var handleImgInfo = function() {
		$('#goods_icon').on('change', function(event){
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
			
			$('#goods_icon_upload').find('span.pl-5').text('正在上传...');
         	$('#img-loading').removeClass('hidden');
         	var uploadURL = Goods.imgURL + 'uploadImg.php';

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
				if ( Goods.success == data.ret_code ) {
					$.each(data.data, function(index, item){
						var showURL = Goods.imgURL + 'showImg.php?img_id=' + item.img_name;
						$('#img-preview').find('img').attr('src', showURL);
						$('#img-preview').find('img').val($('#goods_icon').val());
						$('#goods_icon_name').val(item.img_name);
					});
					$('#img-preview').find('span').addClass('hidden');
					$('#img-preview').find('img').removeClass('hidden');
					$('#goods_icon_upload').find('span.pl-5').text('更改');
				} else {
					$('#img-preview').find('span').text(data.msg);
					$('#img-preview').find('span').removeClass('hidden');
					$('#img-preview').find('img').addClass('hidden');
					$('#goods_icon_upload').find('span.pl-5').text('重新上传');
					$('#goods_icon_name').val('');
				}
				// $('#goods_add #shop_icon_upload').addClass('hidden');
				// $('#goods_add #shop_icon_delete').removeClass('hidden');

				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				$('#img-preview').find('span').text(textStatus);
				$('#img-preview').find('span').removeClass('hidden');
				$('#img-preview').find('img').addClass('hidden');
				$('#goods_icon_upload').find('span.pl-5').text('重新上传');
				$('#goods_icon_name').val('');
				// showAlertMessage(textStatus);
				// console.log(jqXHR);
				// console.log(textStatus);
				// console.log(errorThrown.message);
			})
			.always(function() {
				//去掉等待显示
				console.log("complete");
				$('#img-loading').addClass('hidden');
				// $('#goods_add #shop_icon').val('');
			});
		});
	 }

	 var initModal = function() {
	 	$('#goods_delete_modal').on('show.bs.modal', function(event){
	 		console.log(event);
	 		var tbody = $(event.relatedTarget).parent().parent().parent().parent();
	 		var shopID = tbody.data('shopid');
	 		var goodsID = tbody.data('goodsid');
	 		var goodsName = tbody.data('goodsname');
	 		$(this).find('div.modal-body mark').text(goodsName);
			$(this).find('button#delete_goods').data('shopid', shopID);
			$(this).find('button#delete_goods').data('goodsid', goodsID);
	 	});
	 	$('#goods_changestatus_modal').on('show.bs.modal', function(event){
	 		console.log(event);
	 		var tbody = $(event.relatedTarget).parent().parent().parent().parent();
	 		var shopID = tbody.data('shopid');
	 		var goodsID = tbody.data('goodsid');
	 		var goodsName = tbody.data('goodsname');
	 		var goodsStatus = tbody.data('goodsstatus');
	 		$(this).find('div.modal-body mark').text(goodsName);
			$(this).find('button#change_goods').data('shopid', shopID);
			$(this).find('button#change_goods').data('goodsid', goodsID);
			$(this).find('button#change_goods').data('goodsstatus', goodsStatus);
	 	});
	 }

	 var handleDeleteGoods = function() {
	 	$('#goods_delete_modal').on('click', 'button#delete_goods', function(event){
	 		event.preventDefault();
	 		var shopID = $(this).data('shopid');
	 		var goodsID = $(this).data('goodsid');
	 		var tbody;
	 		$('#goods_list_table tbody').each(function(){
	 			if ( shopID == $(this).data('shopid') && goodsID == $(this).data('goodsid') ) {
	 				tbody = $(this);
	 			}
	 		});

	 		$.ajax({
				url: '/admin/freshbiz/goods/delete',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID, goods_id : goodsID },
				context: tbody,
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				var goodsName = tbody.data('goodsname');
				if ( Goods.success == data.ret_code ) {
					this.remove();
				}
				$('#goods_delete_modal').modal('hide');
				App.showTipMessage(goodsName+data.msg);
				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				$('#goods_delete_modal').modal('hide');
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

	 var handleChangeGoodsStatus = function() {
	 	$('#goods_changestatus_modal').on('click', 'button#change_goods', function(event){
	 		event.preventDefault();
	 		var shopID = $(this).data('shopid');
	 		var goodsID = $(this).data('goodsid');
	 		var goodsStatus = $(this).data('goodsstatus');
	 		var tbody;
	 		$('#goods_list_table tbody').each(function(){
	 			if ( shopID == $(this).data('shopid') && goodsID == $(this).data('goodsid') ) {
	 				tbody = $(this);
	 			}
	 		});

	 		$.ajax({
				url: '/admin/freshbiz/goods/change/status',
				type: 'POST',
				dataType: 'json',
				data: { shop_id : shopID, goods_id : goodsID, goods_status : goodsStatus },
				context: tbody,
			})
			.done(function(data, textStatus, jqXHR) {
				console.log("success");
				var goodsName = tbody.data('goodsname');
				if ( Goods.success == data.ret_code ) {
					this.remove();
				}
				$('#goods_changestatus_modal').modal('hide');
				App.showTipMessage(goodsName+data.msg);
				// showAlertMessage(data.msg);
				// this.html(data);
				// console.log(data);
				// console.log(textStatus);
				// console.log(jqXHR);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log("fail");
				$('#goods_changestatus_modal').modal('hide');
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

			initVariable();
			
			handleShopShow();

			handleImgInfo();
		},

		initGoodsInfo : function() {

			initVariable();

			handleImgInfo();
		},

		initGoodsInfoList : function() {

			initVariable();

			initModal();

			handleDeleteGoods();

			handleChangeGoodsStatus();
		},
	} 
	
}();