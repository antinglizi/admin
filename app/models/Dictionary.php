<?php

class Dictionary extends \Eloquent {
	
	protected $table = 'dictionary';

	protected $fillable = [];

	protected $primaryKey = 'wy_dic_id';

	public $timestamps = false;
}