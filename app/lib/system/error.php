<?php

/*
* 系统的错误处理程序
*/

use Symfony\Component\HttpKernel\Exception\HttpException;

App::error(function(Exception $exception)
{
	// Handle the basic exception...
	$context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    return Response::view('exception.missing', array(), 404);
});

App::error(function(LogicException $exception)
{
    // Handle the logic exception...
    $context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    return Response::view('exception.missing', array(), 404);
});

App::error(function(RuntimeException $exception)
{
    // Handle the runtime exception...
    $context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    // return Response::make('$exception->getMessage()', 404);
    return Response::view('exception.missing', array(), 404);
});

App::error(function(ErrorException $exception)
{
    // Handle the error exception...
    $context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    return Response::view('exception.missing', array(), 404);
});

App::error(function(HttpException $exception, $code)
{
    // Handle the http exception...
    $context = array(
        "errorCode" => $code,
    );
    Log::error($exception, $context);
    // return Response::make($exception->getMessage());
    return Response::view('exception.missing', array(), 404);
});

App::fatal(function($exception)
{
    // Handle the fatal exception...
    $context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    // return Response::make($exception->getMessage());
    return Response::view('exception.missing', array(), 404);
});

App::missing(function($exception)
{
    $context = array(
        "errorCode" => $exception->getCode(),
    );
    Log::error($exception, $context);
    return Response::view('exception.missing', array(), 404);
});