@extends('layouts.admin.admin')

@section('title') @parent 系统错误  @stop

@section('container')
	
	<div id="content-right">
		<div class="container">
			<h1 class="font-red">{{ $error }}</h1>
		</div>
	</div>
@stop