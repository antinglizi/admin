@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 订单管理  @stop

@section('container')

<div id="content-right">
    <div class="container-fluid">
        <div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>订单管理</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>外卖业务</li>
					<li class="active">订单管理</li>
				</ol>
            </div>
        </div>
		<div class="connect-status">
			<p><strong>连接状态为：</strong><span id="message" class="font-green"></span></p>
			<audio id="notify" preload="auto">
	            <source src="{{ asset("assets/audio/notify.mp3") }}" type="audio/mpeg"/>
	            <source src="{{ asset("assets/audio/notify.mp3") }}" type="audio/wav"/>
	        </audio>
		</div>

		@include ('admin.template.alert')

		@if ( isset($headerShop) )
			@if ( isset($all) )
			<h1 class="font-red">{{ $all }}</h1>
			@else
			<div class="row">
	            <div class="col-lg-12">
	            	<ul class="nav nav-pills" role="tablist">
	            		<li role="presentation" class="active"><a href="#new" aria-controls="new" role="tab" data-toggle="pill" data-orderstatus="1">新订单<span id="new_order_count" class="badge">{{ count($mainNewOrders) }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#recved" aria-controls="recved" role="tab" data-toggle="pill" data-orderstatus="2">已接单<span id="recved_order_count" class="badge">{{ $mainRecvOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#deliverying" aria-controls="deliverying" role="tab" data-toggle="pill" data-orderstatus="3">配送中<span id="deliverying_order_count" class="badge">{{ $mainDeliveryOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#finished" aria-controls="finished" role="tab" data-toggle="pill" data-orderstatus="4">已完成<span id="finished_order_count" class="badge">{{ $mainFinishOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#canceled" aria-controls="canceled" role="tab" data-toggle="pill" data-orderstatus="5">已取消<span id="canceled_order_count" class="badge">{{ $mainCancelOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#refused" aria-controls="refused" role="tab" data-toggle="pill" data-orderstatus="6">已拒单<span id="refused_order_count" class="badge">{{ $mainRefuseOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            		<li role="presentation"><a href="#reminder" aria-controls="reminder" role="tab" data-toggle="pill" data-orderstatus="7">催单中<span id="reminder_order_count" class="badge">{{ $mainReminderOrdersCount }}</span><div class="order-new hidden"></div></a></li>
	            	</ul>
	            	<div class="tab-content">
		        		<div class="tab-pane fade in active panel panel-default" id="new">
		                    <div class="panel-heading">
		                        <h3 class="panel-title">新订单信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
		                    		<col class="w398"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w100"></col>
		                    		<col class=""></col>
		                    	</colgroup>
		                    	<thead>
		                    		<tr class="col-name">
		                    			<th>商品</th>
		                    			<th>单价（元）</th>
		                    			<th>数量</th>
		                    			<th>金额（元）</th>
		                    			<th>交易状态</th>
		                    			<th>交易操作</th>
		                    		</tr>
		                    	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="new_table" class="table table-bordered">
			                    	<colgroup>
			                    		<col class="w400"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w100"></col>
			                    		<col class=""></col>
			                    	</colgroup>
			                    	<thead>
			                    	</thead>
			                    	@include('admin.freshbiz.order.neworder', compact('mainNewOrders'))
			                    </table>
		                    </div>
	                    </div>
	            		<div class="tab-pane fade in panel panel-default" id="recved">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">已接单信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
	                    	<table class="table table-bordered">
		                    	<colgroup>
		                    		<col class="w398"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w100"></col>
		                    		<col class=""></col>
		                    	</colgroup>
		                    	<thead>
		                    		<tr class="col-name">
		                    			<th>商品</th>
		                    			<th>单价（元）</th>
		                    			<th>数量</th>
		                    			<th>金额（元）</th>
		                    			<th>交易状态</th>
		                    			<th>交易操作</th>
		                    		</tr>
		                    	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="recved_table" class="table table-bordered">
			                    	<colgroup>
			                    		<col class="w400"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w100"></col>
			                    		<col class=""></col>
			                    	</colgroup>
			                    	<thead>
			                    	</thead>
			                    </table>
		                    </div>
	            		</div>
	            		<div class="tab-pane fade in panel panel-default" id="deliverying">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">配送中信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
		                    		<col class="w398"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w100"></col>
		                    		<col class=""></col>
		                    	</colgroup>
		                    	<thead>
		                    		<tr class="col-name">
		                    			<th>商品</th>
		                    			<th>单价（元）</th>
		                    			<th>数量</th>
		                    			<th>金额（元）</th>
		                    			<th>交易状态</th>
		                    			<th>交易操作</th>
		                    		</tr>
		                    	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="deliverying_table" class="table table-bordered">
			                    	<colgroup>
			                    		<col class="w400"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w100"></col>
			                    		<col class=""></col>
			                    	</colgroup>
			                    	<thead>
			                    	</thead>
			                    </table>
		                    </div>
	            		</div>
	            		<div class="tab-pane fade in panel panel-default" id="finished">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">已完成信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
			                		<col class="w200"></col>
			                		<col class="w150"></col>
			                		<col class="w200"></col>
			                		<col class="w200"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class=""></col>
			                	</colgroup>
								<thead>
			                		<tr class="col-name">
			                			<th>订单号</th>
			                			<th>买家</th>
			                			<th>下单时间</th>
			                			<th>完成时间</th>
			                			<th>订单总额</th>
			                			<th>交易状态</th>
			                			<th>操作</th>
			                		</tr>
			                	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="finished_table" class="table table-bordered">
			                    	<colgroup>
				                		<col class="w200"></col>
				                		<col class="w150"></col>
				                		<col class="w200"></col>
				                		<col class="w200"></col>
				                		<col class="w100"></col>
				                		<col class="w100"></col>
				                		<col class=""></col>
				                	</colgroup>
									<thead>
				                	</thead>
			                    </table>
		                    </div>
	            		</div>
	            		<div class="tab-pane fade in panel panel-default" id="canceled">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">已取消信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
			                		<col class="w200"></col>
			                		<col class="w150"></col>
			                		<col class="w200"></col>
			                		<col class="w200"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class=""></col>
			                	</colgroup>
								<thead>
			                		<tr class="col-name">
			                			<th>订单号</th>
			                			<th>买家</th>
			                			<th>下单时间</th>
			                			<th>取消时间</th>
			                			<th>订单总额</th>
			                			<th>交易状态</th>
			                			<th>操作</th>
			                		</tr>
			                	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="canceled_table" class="table table-bordered">
			                    	<colgroup>
				                		<col class="w200"></col>
				                		<col class="w150"></col>
				                		<col class="w200"></col>
				                		<col class="w200"></col>
				                		<col class="w100"></col>
				                		<col class="w100"></col>
				                		<col class=""></col>
				                	</colgroup>
									<thead>
				                	</thead>
			                    </table>
		                    </div>
	            		</div>
	            		<div class="tab-pane fade in panel panel-default" id="refused">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">已拒单信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
			                		<col class="w200"></col>
			                		<col class="w150"></col>
			                		<col class="w200"></col>
			                		<col class="w200"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class=""></col>
			                	</colgroup>
								<thead>
			                		<tr class="col-name">
			                			<th>订单号</th>
			                			<th>买家</th>
			                			<th>下单时间</th>
			                			<th>拒绝时间</th>
			                			<th>订单总额</th>
			                			<th>交易状态</th>
			                			<th>操作</th>
			                		</tr>
			                	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="refused_table" class="table table-bordered">
			                    	<colgroup>
				                		<col class="w200"></col>
				                		<col class="w150"></col>
				                		<col class="w200"></col>
				                		<col class="w200"></col>
				                		<col class="w100"></col>
				                		<col class="w100"></col>
				                		<col class=""></col>
				                	</colgroup>
									<thead>
				                	</thead>
			                    </table>
		                    </div>
	            		</div>
	            		<div class="tab-pane fade in panel panel-default" id="reminder">
	            			<div class="panel-heading">
		                        <h3 class="panel-title">催单中信息</h3>
		                    </div>
		                    <div class="panel-body">
		                    </div>
		                    <table class="table table-bordered">
		                    	<colgroup>
		                    		<col class="w398"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w150"></col>
		                    		<col class="w100"></col>
		                    		<col class=""></col>
		                    	</colgroup>
		                    	<thead>
		                    		<tr class="col-name">
		                    			<th>商品</th>
		                    			<th>单价（元）</th>
		                    			<th>数量</th>
		                    			<th>金额（元）</th>
		                    			<th>交易状态</th>
		                    			<th>交易操作</th>
		                    		</tr>
		                    	</thead>
		                    </table>
		                    <div class="scroll">
			                    <table id="reminder_table" class="table table-bordered">
			                    	<colgroup>
			                    		<col class="w400"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w150"></col>
			                    		<col class="w100"></col>
			                    		<col class=""></col>
			                    	</colgroup>
			                    	<thead>
			                    	</thead>
			                    </table>
		                    </div>
	            		</div>
		            </div>
	            </div>
	        </div>
	        @include ('admin.freshbiz.order.refuseordertip')
			@endif
		@else
		<h1 class="font-red">{{ $error }}</h1>
		@endif
    </div>
</div>

@stop

@section('afterScript')
	@parent
    {{ HTML::script('assets/js/client.js') }}
    @if ( isset($headerShop) && !isset($all) )
    <script type="text/javascript">
        $(document).ready(function() {
            Client.init('{{ $host }}', {{ $port }}, '{{ $headerShop->wy_shop_id }}', 'test');
        });
    </script>
    @endif
@stop