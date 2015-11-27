<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class GoodsController extends \BaseController {

	/**
	 * 获取增加菜品页面
	 * 
	 * @return Response
	 */
	public function getAdd()
	{
		$shops = AuthController::checkAllShops();
		$types = Dictionary::where('wy_dic_id', DIC_GOODS_TYPE)->get(array('wy_dic_item_id', 'wy_dic_value'));
		$headerShop = AuthController::checkUserURL();
		if ( empty($headerShop) ) {
			return View::make('admin.freshbiz.goods.goodsadd', compact('types','shops'))->withWarning(Lang::get('messages.10006'));
		} else {
			if ( empty($shops->toArray()) ) {
				return View::make('admin.freshbiz.goods.goodsadd', compact('headerShop', 'types','shops'))->withWarning(Lang::get('messages.10006'));
			} else {
				return View::make('admin.freshbiz.goods.goodsadd', compact('headerShop', 'types','shops'));
			}
		}
	}

	/**
	 * 获取出售中菜品
	 * 
	 * @param [int] $[shopID] [店铺ID]
	 * 
	 * @return Response
	 */
	public function getListSelling()
	{
		$goods = array();
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				return View::make('admin.freshbiz.goods.goodsinfo_selling', compact('headerShop','goods'))->withAll(Lang::get('messages.10015'));
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( empty($headerShop) ) {
					return View::make('admin.freshbiz.goods.goodsinfo_selling', compact('headerShop','goods'))->withError(Lang::get('errormessages.-10045'));
				} else {
					$goods = Goods::where('wy_shop_id', $shopID)->where('wy_goods_state', GOODS_STATUS_1)
					    ->paginate(PERPAGE_COUNT_10, array('wy_goods_id','wy_shop_id','wy_goods_name','wy_goods_type','wy_goods_state','wy_goods_icon','wy_goods_sale_price',
					    								'wy_recommend_times','wy_stock','wy_sale_count','wy_onsale_time'));
					foreach ($goods as $good ) {
						$good->wy_goods_id = base64_encode($good->wy_goods_id);
						$good->wy_shop_id = base64_encode($good->wy_shop_id);
						$goodsType = Dictionary::where('wy_dic_id', DIC_GOODS_TYPE)->where('wy_dic_item_id', $good->wy_goods_type)->first();
						$good->wy_goods_type_name = $goodsType->wy_dic_value;
					}
					return View::make('admin.freshbiz.goods.goodsinfo_selling', compact('headerShop','goods'));
				}
			}
		} else {
			return View::make('admin.freshbiz.goods.goodsinfo_selling', compact('goods'))->withError(Lang::get('errormessages.-10045'));
		}
	}

	/**
	 * 查询出售中商品信息
	 * 
	 * @param [int] $[shopID] [店铺ID]
	 * @param [int] $[goodsID] [商品ID]
	 * 
	 * @return Response
	 */
	public function getSellingInfo()
	{
		$shopID = base64_decode(Input::get('shopID'));
		$headerShop = AuthController::checkShop($shopID, true);

		$goodsID = base64_decode(Input::get('goodsID'));
		$good = Goods::where('wy_goods_id', $goodsID)->where('wy_shop_id', $shopID)->where('wy_goods_state', GOODS_STATUS_1)
		    ->first(array('wy_goods_id','wy_shop_id','wy_goods_name','wy_goods_type','wy_goods_icon','wy_goods_sale_price','wy_stock','wy_brief'));
		if ( empty($good) ) {
			return Redirect::back()->with('error', Lang::get('errormessages.-10046'));
		} else {
			$types = Dictionary::where('wy_dic_id', DIC_GOODS_TYPE)->get();
			$typeValue = $good->wy_goods_type;
			$good->wy_goods_id = base64_encode($good->wy_goods_id);
			$good->wy_shop_id = base64_encode($good->wy_shop_id);
			$disableChange = DEFAULT_0;
			return View::make('admin.freshbiz.goods.goodsinfo', compact('headerShop', 'good', 'disableChange'))
							->nest('goodsType', 'admin.template.dic.type', compact('types', 'typeValue'));
		}
	}

	/**
	 * 获取已下载菜品
	 * 
	 * @param [int] $[shopID] [店铺ID]
	 * @return Response
	 */
	public function getListUnsell()
	{
		$goods = array();
		if ( Input::has('shopID') ) {
			$shopID = base64_decode(Input::get('shopID'));
			if ( 0 == strcmp($shopID, ALL_SHOPS_FALG) ) {
				$headerShop = (object)array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				return View::make('admin.freshbiz.goods.goodsinfo_unsell', compact('headerShop','goods'))->withAll(Lang::get('messages.10015'));
			} else {
				$headerShop = AuthController::checkShop($shopID);
				if ( empty($headerShop) ) {
					return View::make('admin.freshbiz.goods.goodsinfo_unsell', compact('shop','goods'))->withError(Lang::get('errormessages.-10047'));
				} else {
					$goods = Goods::where('wy_shop_id', $shopID)->where('wy_goods_state', GOODS_STATUS_2)
					    ->paginate(PERPAGE_COUNT_10, array('wy_goods_id','wy_shop_id','wy_goods_name','wy_goods_type','wy_goods_state','wy_goods_icon','wy_goods_sale_price',
					    								'wy_recommend_times','wy_stock','wy_sale_count','wy_unsale_time'));
					foreach ($goods as $good ) {
						$good->wy_goods_id = base64_encode($good->wy_goods_id);
						$good->wy_shop_id = base64_encode($good->wy_shop_id);
						$goodsType = Dictionary::where('wy_dic_id', DIC_GOODS_TYPE)->where('wy_dic_item_id', $good->wy_goods_type)->first();
						$good->wy_goods_type_name = $goodsType->wy_dic_value;
					}
					return View::make('admin.freshbiz.goods.goodsinfo_unsell', compact('headerShop','goods'));
				}
			}
		} else {
			return View::make('admin.freshbiz.goods.goodsinfo_unsell', compact('goods'))->withError(Lang::get('errormessages.-10047'));
		}
	}

	/**
	 * 查询已下架菜品信息
	 * 
	 * @param [int] $[shopID] [店铺ID]
	 * @param [int] $[goodsID] [商品ID]
	 * 
	 * @return Response
	 */
	public function getUnsellInfo()
	{
		$shopID = base64_decode(Input::get('shopID'));
		$headerShop = AuthController::checkShop($shopID, true);

		$goodsID = base64_decode(Input::get('goodsID'));
		$good = Goods::where('wy_goods_id', $goodsID)->where('wy_shop_id', $shopID)->where('wy_goods_state', GOODS_STATUS_2)
		    ->first(array('wy_goods_id','wy_shop_id','wy_goods_name','wy_goods_type','wy_goods_icon','wy_goods_sale_price','wy_stock','wy_brief'));
		if ( empty($good) ) {
			return Redirect::back()->with('error', Lang::get('errormessages.-10048'));
		} else {
			$types = Dictionary::where('wy_dic_id', DIC_GOODS_TYPE)->get();
			$typeValue = $good->wy_goods_type;
			$good->wy_goods_id = base64_encode($good->wy_goods_id);
			$good->wy_shop_id = base64_encode($good->wy_shop_id);
			return View::make('admin.freshbiz.goods.goodsinfo', compact('headerShop', 'good'))
							->nest('goodsType', 'admin.template.dic.type', compact('types', 'typeValue'));
		}
	}

	/**
	 * 增加商品
	 *
	 * @return Response
	 */
	public function postAdd()
	{
		// return Input::all();

		//提交表单数据
		$data = Input::all();

		//建立验证规则
		$rules = array(
			'shop_id' => 'required',
			'goods_name' => 'max:32',
			'goods_price' => 'digits_between:1,16|numeric|min:0',
			'goods_stock' => 'digits_between:1,16|numeric|min:0',
			'goods_brief' => 'max:255',
		);

		//进行验证
		$validator = Validator::make($data, $rules);
		if ( $validator->fails() ) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$goodsName = Input::get('goods_name');
		$goodsIconName = Input::get('goods_icon_name');
		$goodsType = Input::get('goods_type');
		$goodsPrice = Input::get('goods_price');
		$goodsPrice = round($goodsPrice, PRECISION_3);
		$goodsStock = Input::get('goods_stock');
		$goodsStock = round($goodsStock, PRECISION_3);
		$goodsBrief = Input::get('goods_brief');

		$shopIDs = Input::get('shop_id');
		$shopIDArray = json_decode($shopIDs, true);
		if ( is_array($shopIDArray) && !empty($shopIDArray) ) {
			foreach ($shopIDArray as $index => $value) {
				$shopID = base64_decode($value);
				$goods = new Goods;
				$goods->wy_goods_name = $goodsName;
				$goods->wy_goods_icon = $goodsIconName;
				$goods->wy_goods_type = $goodsType;
				$goods->wy_goods_sale_price = $goodsPrice;
				$goods->wy_stock = $goodsStock;
				$goods->wy_brief = $goodsBrief;
				$goods->wy_sale_count = DEFAULT_0;
				$goods->wy_recommend_times = DEFAULT_0;
				$goods->wy_goods_state = GOODS_STATUS_1;
				$goods->wy_onsale_time = Carbon::now();

				$goods->wy_shop_id = $shopID;
				$result = $goods->save();
				if ( $result ) {
					//增加图片管理表
					$img = new Img;
					$img->wy_user_id = Auth::id();
					$img->wy_shop_id = $shopID;
					$img->wy_goods_id = $goods->getGoodsID();
					$img->wy_img_name = $goodsIconName;
					$img->wy_img_type = IMG_TYPE_3;
					$img->wy_create_at = Carbon::now();
					$img->save();
				} else {
					$context = array(
						"errorCode" => -15015,
						"userID" => Auth::id(),
						"data" => $data,
					);
					Log::error(Lang::get('errormessages.-15015'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15015'));
				}
			}
		}

		return Redirect::back()->with('success', Lang::get('messages.10007'));
	}

	/**
	 * 删除商品
	 *
	 * @return Response
	 */
	public function postDelete()
	{
		// return Input::all();
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);

		$goodsID = base64_decode(Input::get('goods_id'));
		$result = Goods::where('wy_goods_id', $goodsID)->where('wy_shop_id', $shopID)->delete();
		
		$retCode = SUCCESS;
		$retMsg = "";
		if ( $result ) {
			$retMsg = Lang::get('messages.10011');
			$context = array(
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"goodsID" => $goodsID,
			);
			Log::info($retMsg, $context);
		} else {
			$retCode = -15016;
			$retMsg = Lang::get('errormessages.-15016');
			$context = array(
				"errorCode" => $retCode,
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"goodsID" => $goodsID,
			);
			Log::error($retMsg, $context);
		}

		$sendMsgArray = array(
			"ret_code" => $retCode,
			"msg" => $retMsg,
		);

		return Response::json($sendMsgArray);

	}

	/**
	 * 修改商品信息
	 *
	 * @return Response
	 */
	public function postModify()
	{
		// return Input::all();
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID, true);

		//提交表单数据
		$data = Input::all();

		//建立验证规则
		$rules = array(
			'goods_price' => 'digits_between:1,16|numeric|min:0',
			'goods_stock' => 'digits_between:1,16|numeric|min:0',
			'goods_brief' => 'max:255',
		);

		//进行验证
		$validator = Validator::make($data, $rules);
		if ( $validator->fails() ) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$goodsID = base64_decode(Input::get('goods_id'));
		$goodsIconName = Input::get('goods_icon_name');
		$goodsType = Input::get('goods_type');
		$goodsPrice = Input::get('goods_price');
		$goodsPrice = round($goodsPrice, PRECISION_3);
		$goodsStock = Input::get('goods_stock');
		$goodsStock = round($goodsStock, PRECISION_3);
		$goodsBrief = Input::get('goods_brief');

		$goods = Goods::where('wy_goods_id', $goodsID)->where('wy_shop_id', $shopID)->first();
		if ( empty($goods) ) {
			return Redirect::back()->with('error', Lang::get('errormessages.-10049'));
		}

		$goods->wy_goods_icon = $goodsIconName;
		$goods->wy_goods_type = $goodsType;
		$goods->wy_goods_sale_price = $goodsPrice;
		$goods->wy_stock = $goodsStock;
		$goods->wy_brief = $goodsBrief;

		$result = $goods->save();
		if ( $result ) {
			$img = Img::where('wy_user_id', Auth::id())->where('wy_shop_id', $shopID)->where('wy_goods_id', $goods->getGoodsID())->first();
			if ( empty($img) ) {
				//增加图片管理表
				$img = new Img;
				$img->wy_user_id = Auth::id();
				$img->wy_shop_id = $shopID;
				$img->wy_goods_id = $goods->getGoodsID();
				$img->wy_img_name = $goodsIconName;
				$img->wy_img_type = IMG_TYPE_3;
				$img->wy_create_at = Carbon::now();
				$img->save();
			} else {
				$img->wy_img_name = $goodsIconName;
				$img->wy_update_at = Carbon::now();
				$img->save();
			}

			return Redirect::back()->with('success', Lang::get('messages.10010'));
		} else {
			$context = array(
				"errorCode" => -15017,
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"goodsID" => $goodsID,
				"data" => $data,
			);
			Log::error(Lang::get('errormessages.-15017'), $context);
			return Redirect::back()->with('error', Lang::get('errormessages.-15017'));
		}
	}

	/**
	 * 修改商品信息
	 *
	 * @return Response
	 */
	public function postChangeStatus()
	{
		// return Input::all();
		$shopID = base64_decode(Input::get('shop_id'));
		$headerShop = AuthController::checkShop($shopID);

		$goodsID = base64_decode(Input::get('goods_id'));
		$goodsStatus = Input::get('goods_status');
		$goods = Goods::where('wy_goods_id', $goodsID)->where('wy_shop_id', $shopID)->where('wy_goods_state', '!=', $goodsStatus)
		    ->first(array('wy_goods_id','wy_goods_state','wy_onsale_time','wy_unsale_time'));
		
		$retCode = SUCCESS;
		$retMsg = "";
		if ( empty($goods) ) {
			$retCode = -10050;
			$retMsg = Lang::get('errormessages.-10050');
			$context = array(
				"errorCode" => $retCode,
				"userID" => Auth::id(),
				"shopID" => $shopID,
				"goodsID" => $goodsID,
			);
			Log::error($retMsg, $context);
		} else {
			$goods->wy_goods_state = $goodsStatus;
			if ( GOODS_STATUS_1 == $goodsStatus ) {
				$goods->wy_onsale_time = Carbon::now();
			} elseif ( GOODS_STATUS_2 == $goodsStatus ) {
				$goods->wy_unsale_time = Carbon::now();
			}
			$result = $goods->save();
			if ( $result ) {
				$retMsg = Lang::get('messages.10012');
			} else {
				$retCode = -15018;
				$retMsg = Lang::get('errormessages.-15018');
				$context = array(
					"errorCode" => $retCode,
					"userID" => Auth::id(),
					"shopID" => $shopID,
					"goodsID" => $goodsID,
					"goodsStatus" => $goodsStatus,
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
}