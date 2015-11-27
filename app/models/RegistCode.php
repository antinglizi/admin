<?php

class RegistCode extends \Eloquent {
	
	protected $table = 'regist_code';

	protected $fillable = [];

	public $incrementing = false;

	protected $primaryKey = 'user_phone_id';

	public $timestamps = false;
}