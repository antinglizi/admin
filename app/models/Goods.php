<?php

class Goods extends \Eloquent {
	
	protected $table = 'goods';

	protected $fillable = [];

	protected $primaryKey = 'wy_goods_id';

	public $timestamps = false;

	public function getGoodsID()
	{
		return $this->getKey();
	}
}