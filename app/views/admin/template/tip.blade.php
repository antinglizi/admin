@extends('layouts.admin.admin')

@section('title') @parent 未授权操作  @stop

@section('container')
	
	<div id="content-right">
		<div class="container">
			<h1 class="font-red">{{ $message }}</h1>
		</div>
	</div>
@stop