<?php

/*
* 系统用到的所有常量定义
*/

// 数据字典项与数据字典值
const DIC_GOODS_TYPE = 1000; //数据字典：商品类型

const DIC_SHOP_TYPE = 1001; //数据字典：店铺类型

const DIC_ORDER_STATUS = 2000; //数据字典：订单状态
const ORDER_STATE_0	= 0; //订单状态，0表示临时订单
const ORDER_STATE_1 = 1; //订单状态，1表示未处理订单
const ORDER_STATE_2 = 2; //订单状态，2表示订单接单成功
const ORDER_STATE_3 = 3; //订单状态，3表示订单正在配送中
const ORDER_STATE_4 = 4; //订单状态，4表示订单取消
const ORDER_STATE_5 = 5; //订单状态，5表示订单完成
const ORDER_STATE_6 = 6; //订单状态，6表示订单退单
const ORDER_STATE_7 = 7; //订单状态，7表示订单催单，只有当前台请求的时候用

const DIC_SHOP_STATUS = 2001; //数据字典：店铺营业状态
const SHOP_STATUS_1 = 1;	//店铺营业状态：未营业
const SHOP_STATUS_2 = 2;	//店铺营业状态：营业中

const DIC_IMG_TYPE = 2002;	//数据字典：图片类型
const IMG_TYPE_1 = 1;	//图片类型：用户
const IMG_TYPE_2 = 2;	//图片类型：店铺
const IMG_TYPE_3 = 3;	//图片类型：商品

const DIC_REMINDER_FLAG = 2003; //数据字典：催单标记
const REMINDER_FLAG_0 = 0; //催单标记：表示未催单的订单
const REMINDER_FLAG_1 = 1; //催单标记：表示催单中的订单

const DIC_GOODS_STATUS = 2004; //数据字典：商品状态
const GOODS_STATUS_1 = 1;	//商品状态：出售中
const GOODS_STATUS_2 = 2;	//商品状态：已下架

const DIC_SHOP_AUDIT_STATUS = 2006; //数据字典：审核状态
const SHOP_AUDIT_STATUS_1 = 1; //店铺审核状态：未审核状态
const SHOP_AUDIT_STATUS_4 = 4;	//店铺审核状态：已通过状态

const DIC_REGION_LEVEL = 2007; //数据字典：城市层级
const REGION_LEVEL_1 = 1;	//城市层级：省、直辖市
const REGION_LEVEL_2 = 2;	//城市层级：地级市
const REGION_LEVEL_3 = 3;	//城市层级：区、县

const DIC_USER_TYPE = 2008; //数据字典：用户类型
const USER_TYPE_1 = 1;		//用户类型：客户
const USER_TYPE_2 = 2;		//用户类型：商家

const DIC_USER_STATUS = 2009; //数据字典：用户状态
const USER_STATUS_1 = 1;	//用户状态：未激活
const USER_STATUS_2 = 2;	//用户状态：已激活
const USER_STATUS_3 = 3;	//用户状态：已停用

const DIC_LOGIN_STATUS = 2010; //数据字典：登录状态
const LOGIN_STATUS_0 = 0;	//登陆状态：离线
const LOGIN_STATUS_1 = 1;	//登陆状态：在线

const DIC_DEVICE_TYPE = 2011;	//数据字典：设备类型
const DEVICE_TYPE_1 = 1;	//设备类型：Android手机
const DEVICE_TYPE_2 = 2;	//设备类型：ISO手机

const DIC_ACTIVITY_ENABLE = 2012;	//数据字典：活动是否启用
const ACTIVITY_ENABLE_0 = 0;	//活动是否启用：未报名
const ACTIVITY_ENABLE_1	= 1;	//活动是否启用：已报名
const ACTIVITY_ENABLE_0_NAME = "未报名";
const ACTIVITY_ENABLE_1_NAME = "已报名";

const DIC_USER_INFO = 2013;	//数据字典：账号信息类型
const USER_INFO_0 = 0;	//账号信息类型：基本信息
const USER_INFO_1 = 1;	//账号信息类型：账号安全

// 系统常量值
const DEFAULT_0	= 0;	//默认值
const DEFAULT_INT_0 = "0"; //默认整型0
const DEFAULT_DECIMAL_0 = "0.000"; //默认浮点型0
const INVALID_SELECT = -1; 	//无效的选择
const SHOP_BASIC_INFO = 0;	//店铺信息类型：基本信息
const SHOP_OPEN_RULE = 1;	//店铺信息类型：营业规则
const SHOP_OPEN_INFO = 2;	//店铺信息类型：营业信息
const ALL_SHOPS_FALG = "all";	//全部店铺
const ALL_SHOPS = "全部店铺";		//全部店铺
const REGISTER_COOKIE_TIME = 5;	//注册cookie时间
const AUTHCODE_TEST = "1";
const AUTHCODE_NAME = "文蚁";
const AUTHCODE_TIME = 30;	//验证码的有效时间为30分钟
const AUTHCODE_SUCCESS = "000000";
const AUTHCODE_MIN = 60;
const REPORT_DURATIONS = 30;	//营业报表生成的时长为30天
const PERPAGE_COUNT_10 = 10;	//数据分页之后，每页的数据量为10
const PERPAGE_COUNT_15 = 15;	//数据分页之后，每页的数据量为15
const PERPAGE_COUNT_20 = 20;	//数据分页之后，每页的数据量为20
const PRECISION_3 = 3;			//保留三位精度
const PRECISION_2 = 2;			//保留两位精度

// 系统返回码
// The success code
const SUCCESS = 0;