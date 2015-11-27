<?php

class Shop extends \Eloquent {
	
	protected $table = 'shop';

	protected $fillable = [];

	protected $primaryKey = 'wy_shop_id';

	public $timestamps = false;

	public function getShopID()
	{
		return $this->getKey();
	}
}