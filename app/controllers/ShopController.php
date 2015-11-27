<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class ShopController extends \BaseController {

	/**
	 * 显示店铺信息
	 *
	 * @return Response
	 */
	public function getList()
	{
		$userID = Auth::id();
		if ( Request::ajax() ) {
			$shops = Shop::where('wy_shopkeeper', $userID)->where('wy_audit_state', SHOP_AUDIT_STATUS_4)
					->get(array('wy_shop_id', 'wy_shop_name', 'wy_audit_state', 'wy_province_id', 'wy_city_id', 'wy_district_id'));
			$retCode = SUCCESS;
			$retMsg = "";
			if ( empty($shops->toArray()) ) {
				$retCode = 10009;
				$retMsg = Lang::get('messages.10009');
			} else {
				foreach ($shops as $shop) {
					$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
				}
				$shop = array(
					"wy_shop_id" => base64_encode(ALL_SHOPS_FALG),
					"wy_shop_name" => ALL_SHOPS,
				);
				$shops = $shops->toArray();
				array_unshift($shops, $shop);
			}

			$sendMsgArray = array(
				"ret_code" => $retCode,
				"msg" => $retMsg,
				"data" => $shops,
			);
			return Response::json($sendMsgArray);
		} else {
			$shops = Shop::where('wy_shopkeeper', $userID)->orderBy('wy_start_time','desc')->get();
			$shopStatuses = array();
			foreach ($shops as $shop) {
				$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
				$shopType = Dictionary::where('wy_dic_id', DIC_SHOP_TYPE)->where('wy_dic_item_id', $shop->wy_shop_type)->first();
				$shop->wy_shop_type_name = $shopType->wy_dic_value;
				$shopAuditStatus = Dictionary::where('wy_dic_id', DIC_SHOP_AUDIT_STATUS)->where('wy_dic_item_id', $shop->wy_audit_state)->first();
				$shop->wy_audit_state_name = $shopAuditStatus->wy_dic_value;
				$shopStatuses = Dictionary::where('wy_dic_id', DIC_SHOP_STATUS)->get();
			}

			$disableChange = DEFAULT_0;
			$headerShop = AuthController::checkUserURL();
			if ( empty($headerShop) ) {
				return View::make('admin.manage.shop.shop', compact('shops', 'shopStatuses', 'disableChange'));
			} else {
				return View::make('admin.manage.shop.shop', compact('headerShop', 'shops', 'shopStatuses', 'disableChange'));
			}
		}
	}

	/**
	 * 获取店铺详细信息
	 *
	 * @return Response
	 */
	public function getInfo()
	{
		$userID = Auth::id();
		$shopInfoID = base64_decode(Input::get('shopInfoID'));
		$shop = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopInfoID)
		    ->first(array('wy_shop_id','wy_shop_type','wy_shop_name','wy_shop_icon','wy_province_id','wy_city_id','wy_district_id',
		    			'wy_region_id','wy_latitude','wy_longitude','wy_distance','wy_state','wy_audit_state','wy_send_up_price','wy_express_fee',
		    			'wy_phone','wy_send_up_time','wy_brief','wy_start_time','wy_keywords','wy_addr','wy_open_begin','wy_open_end','wy_delivery_begin',
		    			'wy_delivery_end'));
		
		if ( empty($shop) ) {
			$shopID = base64_decode(Input::get('shopID'));
			return Redirect::route('shop.list', array("shopID" => $shopID))->with('error', Lang::get('errormessages.-10039'));
		} else {
			$shop->wy_shop_id = base64_encode($shop->wy_shop_id);
			$types = Dictionary::where('wy_dic_id', DIC_SHOP_TYPE)->get(array('wy_dic_item_id', 'wy_dic_value'));
			$typeValue = $shop->wy_shop_type;
			
			$provinceValues = Region::where('wy_region_parentid', DEFAULT_0)->where('wy_region_level', REGION_LEVEL_1)->get(array('wy_region_id',
					'wy_region_name','wy_region_parentid','wy_region_shortname'));
			$cityValues = Region::where('wy_region_parentid', $shop->wy_province_id)->where('wy_region_level', REGION_LEVEL_2)->get(array('wy_region_id',
					'wy_region_name','wy_region_parentid','wy_region_shortname'));
			$districtValues = Region::where('wy_region_parentid', $shop->wy_city_id)->where('wy_region_level', REGION_LEVEL_3)->get(array('wy_region_id',
					'wy_region_name','wy_region_parentid','wy_region_shortname'));

			$disableChange = DEFAULT_0;
			$headerShop = AuthController::checkUserURL();
			if ( empty($headerShop) ) {
				return View::make('admin.manage.shop.shopinfo', compact('shop', 'provinceValues', 'cityValues', 'districtValues', 'disableChange'))
					->nest('shopType', 'admin.template.dic.type', compact('types', 'typeValue'));
			} else {
				return View::make('admin.manage.shop.shopinfo', compact('headerShop', 'shop', 'provinceValues', 'cityValues', 'districtValues', 'disableChange'))
					->nest('shopType', 'admin.template.dic.type', compact('types', 'typeValue'));
			}
		}
	}

	/**
	 * 获取店铺类型
	 *
	 * @return Response
	 */
	public function getType()
	{
		$types = Dictionary::where('wy_dic_id', DIC_SHOP_TYPE)->get(array('wy_dic_item_id', 'wy_dic_value'));
		if ( empty($types->toArray()) ) {
			return View::make('admin.template.dic.type')->withError(Lang::get('errormessages.-10040'));
		} else {
			return View::make('admin.template.dic.type', compact('types'));
		}
	}

	/**
	 * 获取区域信息
	 *
	 * @return Response
	 */
	public function getRegion($regionID, $regionLevel)
	{
		$shopRegions = Region::where('wy_region_parentid', $regionID)->where('wy_region_level', $regionLevel)->get(array('wy_region_id',
			'wy_region_name','wy_region_parentid','wy_region_shortname'));
		if ( empty($shopRegions->toArray()) ) {
			return View::make('admin.manage.shop.shopregion')->withError(Lang::get('errormessages.-10041'));
		} else {
			return View::make('admin.manage.shop.shopregion', compact('shopRegions'));
		}
	}

	/**
	 * 增加店铺
	 *
	 * @return Response
	 */
	public function postAdd()
	{
		//提交表达数据
		$data = Input::all();

		//建立验证规则
		$rules = array(
			'shop_name' 			=> 'max:64',
			'shop_phone'			=> 'digits:11',
			'shop_longitude'		=> 'required',
			'shop_latitude'			=> 'required',
			'shop_addr'				=> 'max:255',
			'shop_keywords'			=> 'max:128',
			'shop_brief'			=> 'max:255',	
			'shop_delivery_time'	=> 'integer|digits_between:1,11|numeric|min:0',
			'shop_distance'			=> 'digits_between:1,12|numeric|min:0',
		);
		if ( "true" != Input::get('shop_delivery_free') ) {
			$rules = array_merge($rules, array('shop_delivery_fee' => 'digits_between:1,16|numeric|min:0'));
		}
		if ( "true" == Input::get('shop_has_min_amount') ) {
			$rules = array_merge($rules, array('shop_delivery_price' => 'digits_between:1,16|numeric|min:0'));
		}

		//进行验证
		$validator = Validator::make($data, $rules);
		if ( $validator->fails() ) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$userID = Auth::id();
		$shopName = Input::get('shop_name');
		$shopIcon = Input::get('shop_icon');
		$shopIconName = Input::get('shop_icon_name');
		$shopType = Input::get('shop_type');
		$shopPhone = Input::get('shop_phone');
		$shopProvinceID = Input::get('shop_province');
		$shopCityID = Input::get('shop_city');
		$shopDistrictID = Input::get('shop_district');
		$shopRegionID = Input::get('shop_district');
		$shopAddr = Input::get('shop_addr');
		$shopLng = Input::get('shop_longitude');
		$shopLat = Input::get('shop_latitude');
		$shopKeywords = Input::get('shop_keywords');
		$shopBrief = Input::get('shop_brief');
		$shopOpenBegin = Input::get('shop_open_begin');
		$shopOpenEnd = Input::get('shop_open_end');
		$shopDeliveryBegin = Input::get('shop_delivery_begin');
		$shopDeliveryEnd = Input::get('shop_delivery_end');
		$shopDeliveryTime = Input::get('shop_delivery_time');
		$shopDistance = Input::get('shop_distance');
		$shopDistance = round($shopDistance, PRECISION_2);
		$shopDeliveryFee;
		if ( "true" == Input::get('shop_delivery_free') ) {
			$shopDeliveryFee = DEFAULT_0;
		} else {
			$shopDeliveryFee = Input::get('shop_delivery_fee');
			$shopDeliveryFee = round($shopDeliveryFee, PRECISION_3);
		}
		$shopDeliveryPrice;
		if ( "true" == Input::get('shop_has_min_amount') ) {
			$shopDeliveryPrice = Input::get('shop_delivery_price');
			$shopDeliveryPrice = round($shopDeliveryPrice, PRECISION_3);
		} else {
			$shopDeliveryPrice = DEFAULT_0;
		}

		$shop = new Shop;
		$shop->wy_shopkeeper = $userID;
		$shop->wy_shop_name = $shopName;
		$shop->wy_shop_icon = $shopIconName;
		$shop->wy_shop_type = $shopType;
		$shop->wy_phone = $shopPhone;
		$shop->wy_province_id = $shopProvinceID;
		$shop->wy_city_id = $shopCityID;
		$shop->wy_district_id = $shopDistrictID;
		$shop->wy_region_id = $shopRegionID;
		$shop->wy_addr = $shopAddr;
		$shop->wy_longitude = $shopLng;
		$shop->wy_latitude = $shopLat;
		$shop->wy_keywords = $shopKeywords;
		$shop->wy_brief = $shopBrief;
		$shop->wy_open_begin = $shopOpenBegin;
		$shop->wy_open_end = $shopOpenEnd;
		$shop->wy_delivery_begin = $shopDeliveryBegin;
		$shop->wy_delivery_end = $shopDeliveryEnd;
		$shop->wy_send_up_time = $shopDeliveryTime;
		$shop->wy_distance = $shopDistance;
		$shop->wy_express_fee = $shopDeliveryFee;
		$shop->wy_send_up_price = $shopDeliveryPrice;
		$shop->wy_state = SHOP_STATUS_2;	//默认为营业状态
		$shop->wy_audit_state = SHOP_AUDIT_STATUS_1; //默认为未审核状态
		$shop->wy_start_time = Carbon::now();
		
		$result = $shop->save();
		if ( $result ) {
			//增加图片管理表
			$img = new Img;
			$img->wy_user_id = $userID;
			$img->wy_shop_id = $shop->getShopID();
			$img->wy_img_name = $shopIconName;
			$img->wy_img_type = IMG_TYPE_2;
			$img->wy_create_at = Carbon::now();
			$img->save();
			return Redirect::back()->with('success', Lang::get('messages.10001'));
		} else {
			$context = array(
				"errorCode" => -15010,
				"userID" => $userID,
				"data" => $data,
			);
			Log::error(Lang::get('errormessages.-15010'), $context);
			return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15010'));
		}
	}

	/**
	 * 删除店铺
	 *
	 * @return Response
	 */
	public function postDelete()
	{
		$userID = Auth::id();
		$shopID = base64_decode(Input::get('shop_id'));
		$result = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopID)->delete();

		$retCode = SUCCESS;
		$retMsg = "";
		if ( $result ) {
			$retMsg = Lang::get('messages.10002');
			$context = array(
				"userID" => $userID,
				"shopID" => $shopID,
			);
			Log::info($retMsg, $context);
		} else {
			$retCode = -15011;
			$retMsg = Lang::get('errormessages.-15011');
			$context = array(
				"errorCode" => $retCode,
				"userID" => $userID,
				"shopID" => $shopID,
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
	 * 修改店铺
	 *
	 * @return Response
	 */
	public function postModify()
	{
		//获取表达提交数据
		$data = Input::all();

		$userID = Auth::id();
		$shopID = base64_decode(Input::get('shop_id'));
		$shopInfoType = Input::get('shop_info_type');
		
		switch ($shopInfoType) {
			case SHOP_BASIC_INFO:
			{
				//建立验证规则
				$rules = array(
					'shop_phone'		=> 'digits:11',
					'shop_longitude'	=> 'required',
					'shop_latitude'		=> 'required',
					'shop_addr'		=> 'max:255',
					'shop_keywords'		=> 'max:128',
					'shop_brief'		=> 'max:255',
				);

				//进行验证
				$validator = Validator::make($data, $rules);
				if ( $validator->fails() ) {
					return Redirect::back()->withInput()->withErrors($validator);
				}

				$shopIconName = Input::get('shop_icon_name');
				$shopType = Input::get('shop_type');
				$shopPhone = Input::get('shop_phone');
				$shopProvinceID = Input::get('shop_province');
				$shopCityID = Input::get('shop_city');
				$shopDistrictID = Input::get('shop_district');
				$shopRegionID = Input::get('shop_district');
				$shopAddr = Input::get('shop_addr');
				$shopLng = Input::get('shop_longitude');
				$shopLat = Input::get('shop_latitude');
				$shopKeywords = Input::get('shop_keywords');
				$shopBrief = Input::get('shop_brief');

				$shop = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopID)->first();
				if ( empty($shop) ) {
					$context = array(
						"errorCode" => -15012,
						"userID" => $userID,
						"shopID" => $shopID,
					);
					Log::error(Lang::get('errormessages.-15012'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15012'));
				}
				$shop->wy_shop_icon = $shopIconName;
				$shop->wy_shop_type = $shopType;
				$shop->wy_phone = $shopPhone;
				$shop->wy_province_id = $shopProvinceID;
				$shop->wy_city_id = $shopCityID;
				$shop->wy_district_id = $shopDistrictID;
				$shop->wy_region_id = $shopRegionID;
				$shop->wy_addr = $shopAddr;
				$shop->wy_longitude = $shopLng;
				$shop->wy_latitude = $shopLat;
				$shop->wy_keywords = $shopKeywords;
				$shop->wy_brief = $shopBrief;

				$result = $shop->save();
				if ( $result ) {
					$img = Img::where('wy_user_id', $userID)->where('wy_shop_id', $shop->getShopID())->first();
					if ( empty($img) ) {
						//增加图片管理表
						$img = new Img;
						$img->wy_user_id = $userID;
						$img->wy_shop_id = $shop->getShopID();
						$img->wy_img_name = $shopIconName;
						$img->wy_img_type = IMG_TYPE_2;
						$img->wy_create_at = Carbon::now();
						$img->save();
					} else {
						$img->wy_img_name = $shopIconName;
						$img->wy_update_at = Carbon::now();
						$img->save();
					}
					
					return Redirect::back()->with('success', Lang::get('messages.10003'));
				} else {
					$context = array(
						"errorCode" => -15012,
						"userID" => $userID,
						"shopID" => $shopID,
						"data" => $data,
					);
					Log::error(Lang::get('errormessages.-15012'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15012'));
				}
				break;
			}
			case SHOP_OPEN_RULE:
			{
				//建立验证规则		
				$rules = array(
					'shop_delivery_time'	=> 'integer|digits_between:1,11|numeric|min:0',
					'shop_distance'			=> 'digits_between:1,12|numeric|min:0',
				);
				if ( "true" != Input::get('shop_delivery_free') ) {
					$rules = array_merge($rules, array('shop_delivery_fee' => 'digits_between:1,16|numeric|min:0'));
				}
				if ( "true" == Input::get('shop_has_min_amount') ) {
					$rules = array_merge($rules, array('shop_delivery_price' => 'digits_between:1,16|numeric|min:0'));
				}

				//进行验证
				$validator = Validator::make($data, $rules);
				if ( $validator->fails() ) {
					return Redirect::back()->withInput()->withErrors($validator);
				}

				$shopOpenBegin = Input::get('shop_open_begin');
				$shopOpenEnd = Input::get('shop_open_end');
				$shopDeliveryBegin = Input::get('shop_delivery_begin');
				$shopDeliveryEnd = Input::get('shop_delivery_end');
				$shopDeliveryTime = Input::get('shop_delivery_time');
				$shopDistance = Input::get('shop_distance');
				$shopDistance = round($shopDistance, PRECISION_2);
				$shopDeliveryFee;
				if ( "true" == Input::get('shop_delivery_free') ) {
					$shopDeliveryFee = DEFAULT_0;
				} else {
					$shopDeliveryFee = Input::get('shop_delivery_fee');
					$shopDeliveryFee = round($shopDeliveryFee, PRECISION_3);
				}
				$shopDeliveryPrice;
				if ( "true" == Input::get('shop_has_min_amount') ) {
					$shopDeliveryPrice = Input::get('shop_delivery_price');
					$shopDeliveryPrice = round($shopDeliveryPrice, PRECISION_3);
				} else {
					$shopDeliveryPrice = DEFAULT_0;
				}

				$shop = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopID)->first();
				if ( empty($shop) ) {
					$context = array(
						"errorCode" => -15013,
						"userID" => $userID,
						"shopID" => $shopID,
					);
					Log::error(Lang::get('errormessages.-15013'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15013'));
				}
				$shop->wy_open_begin = $shopOpenBegin;
				$shop->wy_open_end = $shopOpenEnd;
				$shop->wy_delivery_begin = $shopDeliveryBegin;
				$shop->wy_delivery_end = $shopDeliveryEnd;
				$shop->wy_send_up_time = $shopDeliveryTime;
				$shop->wy_distance = $shopDistance;
				$shop->wy_express_fee = $shopDeliveryFee;
				$shop->wy_send_up_price = $shopDeliveryPrice;

				$result = $shop->save(); 
				if ( $result ) {
					return Redirect::back()->with('success', Lang::get('messages.10004'));
				} else {
					$context = array(
						"errorCode" => -15013,
						"userID" => $userID,
						"shopID" => $shopID,
						"data" => $data,
					);
					Log::error(Lang::get('errormessages.-15013'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15013'));
				}
				break;
			}
			case SHOP_OPEN_INFO:
			{
				$shop = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopID)->first();
				if ( empty($shop) ) {
					$context = array(
						"errorCode" => -15014,
						"userID" => $userID,
						"shopID" => $shopID,
					);
					Log::error(Lang::get('errormessages.-15014'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15014'));
				}
				$result = $shop->save(); 
				if ( $result ) {
					return Redirect::back()->with('success', Lang::get('messages.10005'));
				} else {
					$context = array(
						"errorCode" => -15014,
						"userID" => $userID,
						"shopID" => $shopID,
						"data" => $data,
					);
					Log::error(Lang::get('errormessages.-15014'), $context);
					return Redirect::back()->withInput()->with('error', Lang::get('errormessages.-15014'));
				}
				break;
			}
			default:
				return Redirect::back()->with('error', Lang::get('errormessages.-10042'));
				break;
		}
	}

	/**
	 * 修改店铺营业状态
	 *
	 * @return Json
	 */
	public function postChangeStatus()
	{
		$userID = Auth::id();
		$shopID = base64_decode(Input::get('shop_id'));
		$shopStatus = Input::get('shop_status');

		$retCode = SUCCESS;
		$retMsg = "";
		$shop = Shop::where('wy_shopkeeper', $userID)->where('wy_shop_id', $shopID)
						->where('wy_audit_state', SHOP_AUDIT_STATUS_4)->where('wy_state', '!=', $shopStatus)->first();
		if ( empty($shop) ) {
			$retCode = -10043;
			$retMsg = Lang::get('errormessages.-14043');
			$context = array(
				"errorCode" => $retCode,
				"userID" => $userID,
				"shopID" => $shopID,
			);
			Log::error($retMsg, $context);
		} else {
			$shop->wy_state = $shopStatus;
			$result = $shop->save();
			if ( $result ) {
				$retMsg = Lang::get('messages.10008');
			} else {
				$retCode = -10044;
				$retMsg = Lang::get('errormessages.-14044');
				$context = array(
					"errorCode" => $retCode,
					"userID" => $userID,
					"shopID" => $shopID,
					"shopStatus" => $shopStatus,
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