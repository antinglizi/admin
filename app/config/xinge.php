<?php

/*
 * 信鸽推送平台的配置文件
 */

return array(

	'accessId'	=>	'2100110252',			//注册平台的ID
	
	'secretKey'	=>	'ca89a55563a2bcb48c824ade373cd37e',	//注册平台的秘钥

	'androidRecvMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已被接受',
		
	),

	'androidDeliveryMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已开始配送',
		
	),

	'androidFinishMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已完成',
		
	),

	'androidRefuseMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单遭到拒绝',
		
	),

	'iosRecvMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已被接受',
		
	),

	'iosDeliveryMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已开始配送',
		
	),

	'iosFinishMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单已完成',
		
	),

	'iosRefuseMessage'	=> array(
		
		'expireTime'	=>	10800,	//消息离线存储多久，单位为秒
		
		'activity'		=>	'com.wenyi.hzapp.ui.OrderInfoActivity',
		
		'style'			=>	array(
			'builderId'	=>	0,
			'ring'		=> 	1,
			'vibrate'	=>	1,
			'clearable'	=>	1,
			'nId'		=>	0,
			'lights'	=>	1,
			'iconType'	=>	0,
			'styleId'	=>	1,
		),
		
		'title'			=>	'亲，您的订单状态发生变化了',
		
		'content'		=>	'您的订单遭到拒绝',
		
	),
);