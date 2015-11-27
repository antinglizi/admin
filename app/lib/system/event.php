<?php

/*
 * 注册事件捕捉所有传到日志的信息
 * $level 表示日志的级别
 * $message 表示日志信息
 * $context 表示日志的上下文信息
 */
Log::listen(function($level, $message, $context)
{
	$params = func_get_args();

});

/*
 * 注册事件监听数据库操作事件
 * $sql 表示SQL语句
 * $bindings 表示参数
 * $time 表示SQL语句执行时间
 */
DB::listen(function($sql, $bindings, $time)
{
	$params = func_get_args();
	// Log::info($params);
});