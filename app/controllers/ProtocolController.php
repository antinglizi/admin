<?php

//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class ProtocolController extends \BaseController {

	/**
	 * 显示协议信息
	 *
	 * @return Response
	 */
	public function getList()
	{
		//
		$disableChange = DEFAULT_0;
		$headerShop = AuthController::checkUserURL();
		if ( empty($headerShop) ) {
			return View::make('admin.manage.protocol.protocol');
		} else {
			return View::make('admin.manage.protocol.protocol',compact('headerShop','disableChange'));
		}
	}

	/*
	 * 查看协议的详细信息
	 */
	public function getListInfo()
	{
		$disableChange = DEFAULT_0;
		$headerShop = AuthController::checkUserURL();
		if ( empty($headerShop) ) {
			return View::make('admin.manage.protocol.protocolinfo');
		} else {
			return View::make('admin.manage.protocol.protocolinfo',compact('headerShop','disableChange'));
		}
	}
}
