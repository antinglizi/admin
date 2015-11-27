<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
* 路由书写规则
* 
* 所有路由都不设置路由参数，方便前台对路由参数的处理
* 路由参数都是已?key1=value1@key2=value2的形式传递
* 这样的话，前后台都可以操作参数，方便扩展
*
* 以主菜单建立路由组，子菜单为这个路由组的路由
*/

//平台管理系统的介绍
Route::get('/', array('as' => 'index', 'uses' => 'DisplayController@getIndex'));

//平台管理系统的注册
Route::get('/register', array('as' => 'register', 'uses' => 'AuthController@getRegister'));
Route::post('/register', array('as' => 'register', 'uses' => 'AuthController@postRegister'));
Route::get('/authcode', array('as' => 'authcode', 'uses' => 'AuthController@getAuthCode'));

//平台管理系统的登陆
Route::get('/login', array('as' => 'login', 'uses' => 'AuthController@getLogin'));
Route::post('/login', array('as' => 'login', 'uses' => 'AuthController@postLogin'));

//平台管理系统的锁定
Route::get('/lock', array('as' => 'lock', 'uses' => 'AuthController@getLock'));

//平台管理系统的登出
Route::get('/logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));

//平台管理系统的忘记密码
Route::get('/forgotpassword', array('as' => 'forgotpassword', 'uses' => 'AuthController@getForgotPassword'));
Route::post('/forgotpassword', array('as' => 'forgotpassword', 'uses' => 'AuthController@postForgotPassword'));

/*
* 平台管理路由组
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
	//平台管理首页
	Route::get('/', array('as' => 'admin.index', 'uses' => 'AdminController@getIndex'));

	/*
	* 生鲜业务
	*/
	Route::group(array('prefix' => 'freshbiz'), function()
	{
		/*
		* 订单管理路由组
		*/
		Route::group(array('prefix' => 'order'), function()
		{
			//获取特定订单状态订单
			Route::get('/list/status', array('as' => 'order.list.status', 'uses' => 'MainOrderController@getListStatus'));

			//按着店铺显示订单
			Route::get('/list', array('as' => 'order.list', 'uses' => 'MainOrderController@getList'));

			//订单详情
			Route::get('/list/info', array('as' => 'order.list.info', 'uses' => 'MainOrderController@getListInfo'));

			//订单打印
			Route::get('/print/{shopID}/{orderID}', array('as' => 'order.print', 'uses' => 'MainOrderController@getPrint'));
			
			//修改订单状态
			Route::post('/change/status', array('as' => 'order.change.status', 'uses' => 'MainOrderController@postChangeStatus'));
		});

		/*
		* 商品管理路由组
		 */
		Route::group(array('prefix' => 'goods'), function()
		{
			//出售中菜品展示
			Route::get('/list/selling', array('as' => 'goods.list.selling', 'uses' => 'GoodsController@getListSelling'));

			//已下架菜品展示
			Route::get('/list/unsell', array('as' => 'goods.list.unsell', 'uses' => 'GoodsController@getListUnsell'));

			//查询出售中商品信息
			Route::get('/list/selling/info', array('as' => 'goods.list.selling.info', 'uses' => 'GoodsController@getSellingInfo'));

			//查询已下架菜品信息
			Route::get('/list/unsell/info', array('as' => 'goods.list.unsell.info', 'uses' => 'GoodsController@getUnsellInfo'));
			
			//查询商品类型
			Route::get('/type', array('as' => 'goods.type', 'uses' => 'GoodsController@getType'));

			//增加商品页面
			Route::get('/add', array('as' => 'goods.add', 'uses' => 'GoodsController@getAdd'));

			//增加商品
			Route::post('/add', array('as' => 'goods.add', 'uses' => 'GoodsController@postAdd'));

			//删除商品
			Route::post('/delete', array('as' => 'goods.delete', 'uses' => 'GoodsController@postDelete'));

			//修改商品
			Route::post('/modify', array('as' => 'goods.modify', 'uses' => 'GoodsController@postModify'));

			//修改商品状态
			Route::post('/change/status', array('as' => 'goods.change.status', 'uses' => 'GoodsController@postChangeStatus'));

		});
	});

	/*
	* 营销中心
	 */
	Route::group(array('prefix' => 'market'), function()
	{
		/*
		* 活动路由组
		*/
		Route::group(array('prefix' => 'activity'), function()
		{
			//显示活动列表
			Route::get('list', array('as' => 'activity.list', 'uses' => 'ActivityController@getList'));

			//报名参加活动
			Route::post('participate', array('as' => 'activity.participate', 'uses' => 'ActivityController@postParticipate'));

			//取消活动
			Route::post('cancel', array('as' => 'activity.cancel', 'uses' => 'ActivityController@postCancel'));
		});

		/*
		 * 代金券路由组
		 */
		Route::group(array('prefix' => 'voucher'), function()
		{
			//代金券列表
			Route::get('list', array('as' => 'voucher.list', 'uses' => 'VoucherController@getList'));
		});

		/*
		 * 满就减路由组
		 */
		Route::group(array('prefix' => 'reward'), function()
		{
			//满就减
			Route::get('list', array('as' => 'reward.list', 'uses' => 'RewardController@getList'));

			//获取增加满就减活动界面
			Route::get('add', array('as' => 'reward.add', 'uses' => 'RewardController@getAdd'));

			//增加满就减活动
			Route::post('add', array('as' => 'reward.add', 'uses' => 'RewardController@postAdd'));
		});
		
	});

	/*
	* 统计报表
	*/
	Route::group(array('prefix' => 'report'), function()
	{
		/*
		* 交易报表路由组
		*/
		Route::group(array('prefix' => 'trade'), function()
		{
			//交易报表
			Route::get('list', array('as' => 'trade.list', 'uses' => 'TradeController@getList'));

			//订单详情
			Route::get('list/info', array('as' => 'trade.list.info', 'uses' => 'TradeController@getListInfo'));

			//订单列表
			Route::get('tradelist', array('as' => 'trade.tradelist', 'uses' => 'TradeController@getTradeList'));

			//营业报表
			Route::get('bizreport', array('as' => 'trade.bizreport', 'uses' => 'TradeController@getBizReport'));

			//报表中的订单列表
			Route::get('bizreportlist', array('as' => 'trade.bizreportlist', 'uses' => 'TradeController@getBizReportList'));

		});

		/*
		* 财务报表路由组
		 */
		Route::group(array('prefix' => 'finance'), function()
		{
			//财务报表
			Route::get('list', array('as' => 'finance.list', 'uses' => 'FinanceController@getList'));

			//订单详情
			Route::get('list/info', array('as' => 'finance.list.info', 'uses' => 'FinanceController@getListInfo'));

			//财务报表数据
			Route::get('financereport', array('as' => 'finance.financereport', 'uses' => 'FinanceController@getFinanceReport'));
		
			//财务报表中的订单列表
			Route::get('financereportlist', array('as' => 'trade.financereportlist', 'uses' => 'FinanceController@getFinanceReportList'));
		});
	});

	/*
	* 管理
	*/
	Route::group(array('prefix' => 'manage'), function()
	{
		/*
		* 店铺管理路由组
		*/
		Route::group(array('prefix' => 'shop'), function()
		{
			//显示店铺
			Route::get('/list', array('as' => 'shop.list', 'uses' => 'ShopController@getList'));

			//店铺信息
			Route::get('/list/info', array('as' => 'shop.info', 'uses' => 'ShopController@getInfo'));

			//店铺类型
			Route::get('/type', array('as' => 'shop.type', 'uses' => 'ShopController@getType'));

			//店铺位置
			Route::get('/region/{regionID}/{regionLevel}', array('as' => 'shop.region', 'uses' => 'ShopController@getRegion'));

			//增加店铺
			Route::post('/add', array('as' => 'shop.add', 'uses' => 'ShopController@postAdd'));

			//删除店铺
			Route::post('/delete', array('as' => 'shop.delete', 'uses' => 'ShopController@postDelete'));

			//修改店铺
			Route::post('/modify', array('as' => 'shop.modify', 'uses' => 'ShopController@postModify'));

			//修改店铺营业状态
			Route::post('/change/status', array('as' => 'shop.change.status', 'uses' => 'ShopController@postChangeStatus'));
		});

		/*
		 * 评价管理
		 */
		Route::group(array('prefix' => 'rate'), function()
		{
			//显示评价信息
			Route::get('/list', array('as' => 'rate.list', 'uses' => 'RateController@getList'));

			//解释评价信息
			Route::post('/explain', array('as' => 'rate.explain', 'uses' => 'RateController@postExplain'));
		});

		/*
		 * 账号管理
		 */
		Route::group(array('prefix' => 'user'), function()
		{
			//显示账号信息
			Route::get('/list', array('as' => 'user.list', 'uses' => 'UserController@getList'));

			//修改账号信息
			Route::post('modify', array('as' => 'user.modify', 'uses' => 'UserController@postModify'));

			//显示修改密码界面
			Route::get('change/password', array('as' => 'user.change.phone', 'uses' => 'UserController@getChangePassword'));

			//修改密码
			Route::post('change/password', array('as' => 'user.change.phone', 'uses' => 'UserController@postChangePassword'));

			//显示验证手机号界面
			Route::get('auth/phone', array('as' => 'user.auth.phone', 'uses' => 'UserController@getAuthPhone'));

			//显示修改手机号界面
			Route::get('change/phone', array('as' => 'user.change.phone', 'uses' => 'UserController@getChangePhone'));

			//修改手机号
			Route::post('change/phone', array('as' => 'user.change.phone', 'uses' => 'UserController@postChangePhone'));
		});

		/*
		 * 协议签署
		 */
		Route::group(array('prefix' => 'protocol'), function()
		{
			//显示协议信息
			Route::get('/list', array('as' => 'protocol.list', 'uses' => 'ProtocolController@getList'));

			//获取协议详细信息
			Route::get('/list/info', array('as' => 'protocol.list.info', 'uses' => 'ProtocolController@getListInfo'));
		});
	});

	/*
	* 设置
	*/
	Route::group(array('prefix' => 'setting'), function()
	{

	});

});

//测试路由
Route::resource('/test', 'TestController');