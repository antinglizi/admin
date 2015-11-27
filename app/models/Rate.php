<?php

class Rate extends \Eloquent {
	
	protected $table = 'user_evaluation';
	
	protected $fillable = [];

	protected $primaryKey = 'wy_comment_id';

	public $timestamps = false;
}