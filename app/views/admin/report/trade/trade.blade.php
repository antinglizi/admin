@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 交易报表  @stop

@section('beforeStyle')
	@parent
	{{ HTML::style('assets/lib/bootstrap-datetimepicker-2.3.4/css/bootstrap-datetimepicker.min.css') }}
@stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
	    <div id="alert_msg" class="row">
	        <div class="col-lg-12">
                <h1>交易报表</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>统计报表</li>
					<li class="active">交易报表</li>
				</ol>
            </div>
	    </div>
	
		@include ('admin.template.alert')

		<div class="row">
			<div class="col-lg-12">
				<ul class="nav nav-pills" role="tablist">
	        		<li role="presentation" class="active"><a href="#order_list" aria-controls="order_list" role="tab" data-toggle="pill">订单明细</a></li>
	        		<li role="presentation"><a href="#biz_repport" aria-controls="biz_repport" role="tab" data-toggle="pill">营业报表</a></li>
	        	</ul>
	        	<div class="tab-content">
		            <div class="tab-pane fade in panel panel-default active order-list" id="order_list">
	                    <div class="panel-body">
	                    	<div>
	                    		<form class="form-inline">
									@if ( isset($headerShop) )
										<input type="hidden" id="shop_id" name="shop_id" value="{{ $headerShop->wy_shop_id }}">
									@endif
									<div class="form-group">
										<label class="w80">订单号</label>
										<input type="text" class="form-control" id="order_number" name="order_number">
		                    		</div>
		                    		<div class="form-group ml-20">
										<label class="w50">买家</label>
										<input type="text" class="form-control" id="buyer_name" name="buyer_name">
		                    		</div>
		                    		<div class="form-group ml-20">
										<label class="w80">订单状态</label>
										<select class="form-control" id="order_status" name="order_status">
											<option value="-1">请选择</option>
											{{ $orderStatus }}
										</select>
		                    		</div>
		                    		<div class="form-group ml-20">
										<label class="w80">催单状态</label>
										<select class="form-control" id="reminder_flag" name="reminder_flag">
											<option value="-1">请选择</option>
											@foreach ( $reminderFlags as $index => $reminderFlag )
											<option value="{{ $reminderFlag->wy_dic_item_id }}">{{ $reminderFlag->wy_dic_value }}</option>
											@endforeach
										</select>
		                    		</div>
		                    	</form>
	                    	</div>
	                    	<div class="mt-15">
	                    		<form class="form-inline">
		                    		<div class="form-group">
		                    			<label class="w80">下单时间</label>
		                    			<div class="input-group time">
		                    				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		                    				<input type="text" class="form-control" id="start_time" name="start_time">
		                    			</div>
		                    		</div>
		                    		<div class="form-group">
										<span class="txt">至</span>
		                    		</div>
		                    		<div class="form-group">
		                    			<div class="input-group time">
		                    				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		                    				<input type="text" class="form-control" id="end_time" name="end_time">
		                    			</div>
		                    		</div>
		                    		<button id="search_order" class="btn btn-success bg-green ml-20"><span class="fa fa-search"><span class="pl-5">查 询</span></span></button>
		                    		<button id="clear_search_conditions" class="btn btn-danger ml-20"><span class="fa fa-undo"><span class="pl-5">清空查询条件</span></span></button>
		                    	</form>
	                    	</div>
	                    </div>
						<div id="orderdetail_container">
							@include('admin.report.trade.tradelist', compact('mainOrders'))
						</div>
		            </div>
					<div class="tab-pane fade panel panel-default biz-report" id="biz_repport">
	                    <div class="panel-body">
	                    	<div class="report-form">
	                    		<div>
	                    			<form class="form-inline">
			                    		@if ( isset($headerShop) )
											<input type="hidden" id="shop_id" name="shop_id" value="{{ $headerShop->wy_shop_id }}">
										@endif
			                    		<div class="form-group">
			                    			<label class="w80">订单日期</label>
			                    			<div class="input-group date">
			                    				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
			                    				<input type="text" class="form-control" id="start_date" name="start_date" readonly>
			                    			</div>
			                    		</div>
			                    		<div class="form-group">
											<label class="txt">至</label>
			                    		</div>
			                    		<div class="form-group">
			                    			<div class="input-group date">
			                    				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
			                    				<input type="text" class="form-control" id="end_date" name="end_date" readonly>
			                    			</div>
			                    		</div>
			                    	</form>
	                    		</div>
	                    		<div class="mt-15">
	                    			<form class="form-inline">
	                    				<div class="form-group">
											<label class="w80">订单状态</label>
											<select class="form-control" id="order_status" name="order_status">
												<option value="-1">请选择</option>
												{{ $orderStatus }}
											</select>
			                    		</div>
			                    		<div class="form-group ml-20">
											<label class="w80">催单状态</label>
											<select class="form-control" id="reminder_flag" name="reminder_flag">
												<option value="-1">请选择</option>
												@foreach ( $reminderFlags as $index => $reminderFlag )
												<option value="{{ $reminderFlag->wy_dic_item_id }}">{{ $reminderFlag->wy_dic_value }}</option>
												@endforeach
											</select>
			                    		</div>
			                    		<button id="search_bizreport" class="btn btn-success bg-green ml-20">生 成</button>
	                    			</form>
	                    		</div>
	                    	</div>
	                    	<div class="report-wrap">
	                    		<ul class="nav nav-pills" role="tablist">
					        		<li role="presentation" class="active"><a href="#order_money" aria-controls="order_money" role="tab" data-toggle="pill">订单金额</a></li>
					        		<li role="presentation"><a href="#order_amount" aria-controls="order_amount" role="tab" data-toggle="pill">订单笔数</a></li>
					        	</ul>
								<div class="tab-content">
									<div class="tab-pane fade in active" id="order_money">
										<div class="chart-title">
											<h3>订单金额趋势图</h3>
											<h5></h5>
											<div class="sum">
												<strong>总计：</strong>
												<span id="money_sum">0</span>
												<span class="unit">元</span>
											</div>
										</div>
										<div id="order_money_chart" class="chart-container"></div>
									</div>
									<div class="tab-pane fade" id="order_amount">
										<div class="chart-title">
											<h3>订单笔数趋势图</h3>
											<h5></h5>
											<div class="sum">
												<strong>总计：</strong>
												<span id="amount_sum">0</span>
												<span class="unit">笔</span>
											</div>
										</div>
										<div id="order_amount_chart" class="chart-container"></div>
									</div>
								</div>
	                    	</div>
	                    	<div id="orderreport_container">
	                    		<?php
	                    			$paginator = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
	                    			echo $paginator->links('admin.template.pagination.simple');
	                    		?>
	                    		<table id="orderreport_list_table" class="table table-bordered">
		                    		<colgroup>
				                		<col class="w150"></col>
				                		<col class="w150"></col>
				                		<col class="w100"></col>
				                		<col class="w150"></col>
				                		<col class="w100"></col>
				                		<col class="w100"></col>
				                		<col class="w150"></col>
				                	</colgroup>
									<thead>
				                		<tr class="col-name">
				                			<th>订单号</th>
				                			<th>店铺</th>
				                			<th>买家</th>
				                			<th>订单时间</th>
				                			<th>订单总额</th>
				                			<th>订单状态</th>
				                			<th>催单状态</th>
				                			<th>操作</th>
				                		</tr>
				                	</thead>
		                    	</table>
		                    	<?php
	                    			$paginator = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
	                    			echo $paginator->links('admin.template.pagination.slider');
	                    		?>
	                    	</div>
	                    </div>
		            </div>
	        	</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('afterScript')
	@parent
	{{ HTML::script('assets/js/vendor/moment-2.10.3.min.js') }}
	{{ HTML::script('assets/lib/bootstrap-datetimepicker-2.3.4/js/bootstrap-datetimepicker.min.js') }}
	{{ HTML::script('assets/lib/bootstrap-datetimepicker-2.3.4/js/bootstrap-datetimepicker.zh-CN.js') }}
	{{ HTML::script('assets/lib/echarts-2.2.3/echarts.js') }}
	{{ HTML::script('assets/js/report.js') }}
	<script type="text/javascript">
        $(document).ready(function() {
            Report.initTrade();
        });
    </script>
@stop
