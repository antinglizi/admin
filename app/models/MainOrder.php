<?php

class MainOrder extends \Eloquent {
	
	protected $table = 'main_order';
	
	protected $fillable = [];

	protected $primaryKey = 'wy_main_order_id';

	public $timestamps = false;

	public function subOrders()
	{
		return $this->hasMany('SubOrder', 'wy_main_order_id');
	}
}