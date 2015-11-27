<?php

use Illuminate\Encryption\DecryptException;
//后台常量文件
require_once app_path().'/lib/admin/constant.php';

class TestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		// App::abort(404);
		// App::abort(403, '403');
		// throw new DecryptException("Error Processing Request", 1);
		// return $_ENV;
		// return App::environment();
		// Crypt::setMode('ctr');
		// Crypt::setCipher($cipher);
		//return hash_algos();
		// return base64_decode('MQ==');
		// return urlencode('shop_id=1');
		// return com_create_guid();
		// return base64_encode(12);
		// return hash('sha1', 2);
		
		// Crypt::setCipher(MCRYPT_SAFER64);
		// Crypt::setMode(MCRYPT_MODE_ECB);

		// $groups = Sentry::findAllGroups();
		// return $groups;
		// $credentials = array(
	 //        'email'    => 'john.doe@example.com',
	 //        'password' => 'test',
	 //    );

	 //    // Authenticate the user
	 //    $user = Sentry::authenticate($credentials, false);
	 //    return $user;

		// setcookie('test','test',time()+24*60*60);
		// Session::put('password', 'password');
		// Cookie::get('password');
		// return Session::getId();
		// Session::setId(id)
		// return Session::get('intended');
		// return $url->intended;
		$activities = Activity::all();
		$shopActivities = ShopActivity::where('wy_shop_id', 2)->where('wy_enable', ACTIVITY_ENABLE_1)->get(array('wy_activity_id'));
		var_dump($shopActivities);
		return;


		$user = new User;
		$user->wy_user_name = "xx";
		return $user->toJson();

		$mainOrders = MainOrder::with('subOrders')->where('wy_shop_id', 2)->get();
		return $mainOrders;
		$name = DB::table('user')->skip(2)->get(array('wy_user_id', 'wy_user_name'));
		DB::table('users')->where('votes', '>', 100)->sharedLock()->get();
		Log::info();
		var_dump($name);
		return $name;
		return Session::all();
		$user = new User;
		var_dump($user);
		$value = Request::server();
		return $value;
		return urldecode('Mg%3D%3D');
		return base64_encode("all");
		$headShop = new stdObject();
		$headShop->wy_shop_id = "all";
		$headShop->wy_shop_name = "全部店铺";
		// $headShop = (object)array(
		// 	"wy_shop_id" => "all",
		// 	"wy_shop_name" => "全部店铺",
		// );
		var_dump($headShop);
		return $headShop->wy_shop_id;

		return Cookie::get('login_user');
		return base64_decode('NTE=');
		$url = Session::get('url');
		return $url['intended'];
		return Session::all();
		// return $_COOKIE;
		// return base64_decode('YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVHpEcXBUd2NXZlpEaFZWd0VDV1g2eWNvNmkwQ0UxMWg5cThyc3hpZyI7czozOiJ1cmwiO2E6MDp7fXM6NToiZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo4OiJwYXNzd29yZCI7czo4OiJwYXNzd29yZCI7czozODoibG9naW5fODJlNWQyYzU2YmRkMDgxMTMxOGYwY2YwNzhiNzhiZmMiO3M6MjoiNDMiO3M6OToiX3NmMl9tZXRhIjthOjM6e3M6MToidSI7aToxNDI4NTUyMzc1O3M6MToiYyI7aToxNDI4NTUxODg5O3M6MToibCI7czoxOiIwIjt9fQ==');
		// return $user = Sentry::getUser();
		// return base64_decode(base64_decode('TVE9PQ=='));
		DB::connection()->getPdo();
        DB::getPdo();
        return base64_encode('wenyi_2');
		return Session::all();
//        new MessageBag;
		// Session::get(name, default)
		// return Cookie::get(key, default);
		$encrypted1 = Crypt::encrypt('1');
		$encrypted2 = Crypt::encrypt(1);
		$encrypted3 = Crypt::encrypt(1);
		echo $encrypted1 . PHP_EOL;
		echo $encrypted2 . PHP_EOL;
		echo $encrypted3 . PHP_EOL;
		$decrypted1 = Crypt::decrypt($encrypted1);
		$decrypted2 = Crypt::decrypt($encrypted2);
		$decrypted3 = Crypt::decrypt($encrypted3);
		var_dump($decrypted1);
		var_dump($decrypted2);
		var_dump($decrypted3);
        // Response::make()

		//
		// $orders = MainOrder::all();
		// $suborder = new SubOrder;
		// return $suborder->get();
		// return $suborder->sum();
		// return $orders;
		// $main = new MainOrder();
		// dd($main->getNameSpace);
		// $count = Input::get('count');
		// if( empty($count) ) {
		// 	$count = 3;
		// }
		// $count = 3;
		// $goods = Goods::where('wy_goods_id', '<=', $count)->get(array('wy_goods_id', 'wy_goods_name',
		// 	'wy_goods_img','wy_goods_sale_price','wy_sale_count','wy_brief'));
		// $goods = $goods->toArray();

		// $orders = MainOrder::where('wy_order_state', 1)->groupBy('wy_shop_id')->get(array('wy_shop_id','wy_order_number', 'wy_recv_name',
		// 	'wy_recv_phone','wy_recv_addr','wy_actual_money','wy_book_time','wy_order_state'));

		// var_dump($orders);

		// unset($goods[0]['wy_goods_id']);

		// return $orders;
		
		//推送前台的数据最好携带uid信息，可以直接推送
		// $sendMsg = array(
		// 	"func_no" => 700003,
		// 	"data" => array(
		// 		array(
		// 			"main_order_id" => 1,
		// 			"shop_id" => 1
		// 		)
		// 	)		 
		// );

		// var_dump($sendMsg);

		// $jsonData = 'content=' . json_encode($sendMsg);

		// var_dump($jsonData);
		
		// $url = "http://192.168.140.128:9501/push";
		// return $this->post($url, $jsonData);
	}

	function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HEADER, array(
        // 	'Content-Type: application/json; charset=utf-8',
        // 	'Content-Length: ' . strlen($data))
        // );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result_content = curl_exec($ch);
        if (!$result_content)
        {
            error_log(curl_error($ch));
        }
        $result_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array($result_code, $result_content);
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		Sentry::

		$user = Sentry::createUser(array(
	        'email'     => 'john.doe@example.com',
	        'password'  => 'test',
	        'activated' => true,
	    ));

	    // Find the group using the group id
	    $adminGroup = Sentry::findGroupById(1);

	    // Assign the group to the user
	    $user->addGroup($adminGroup);


		// $group = Sentry::createGroup(array(
	 //        'name'        => 'Moderator',
	 //        'permissions' => array(
	 //            'admin' => 1,
	 //            'users' => 1,
	 //        ),
	 //    ));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
