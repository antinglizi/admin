@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 订单详情  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid order-detail">
        <div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>订单详情</h1>
            </div>
        </div>

        @if ( isset($error) )
		@include ('admin.template.alert_view')		
        @else
        <div class="row">
	        <div class="col-lg-12">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
                        <h3 class="panel-title">订单信息</h3>
                    </div>
                    <div class="panel-body">
                    	<div class="order-flow">
                    		<ul>
                    			@foreach ( $orderStatFlows as $index => $orderStatFlow )
                    			@if ( 1 == $orderStatFlow )
                    			<li>
                    				<div class="txt-up">提交订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_generate_time }}</div>
                    			</li>
								@elseif ( 2 ==  $orderStatFlow )
                    			<li>
                    				<div class="proc"></div>
                    			</li>
                    			<li>
                    				<div class="txt-up">确认订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_confirm_time }}</div>
                    			</li>
                    			@elseif ( 3 ==  $orderStatFlow )
                    			<li>
                    				<div class="proc"></div>
                    			</li>
                    			<li>
                    				<div class="txt-up">配送订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_send_time }}</div>
                    			</li>
                    			@elseif ( 4 ==  $orderStatFlow )
                    			<li>
                    				<div class="proc"></div>
                    			</li>
                    			<li>
                    				<div class="txt-up">完成订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_arrive_time }}</div>
                    			</li>
                    			@elseif ( 5 ==  $orderStatFlow )
                    			<li>
                    				<div class="proc"></div>
                    			</li>
                    			<li>
                    				<div class="txt-up">取消订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_cancel_time }}</div>
                    			</li>
                    			@elseif ( 6 ==  $orderStatFlow )
                    			<li>
                    				<div class="proc"></div>
                    			</li>
                    			<li>
                    				<div class="txt-up">拒绝订单</div>
                    				<div class="node"></div>
                    				<div class="txt-down">{{ $mainOrder->wy_refuse_time }}</div>
                    			</li>
                    			@endif
                    			@endforeach
                    		</ul>
                    	</div>
                    	<table class="table order-table">
                    		<tbody>
                    			<tr>
                    				<td>
                    					<strong>订&nbsp;&nbsp;单&nbsp;&nbsp;号：</strong>
                    					<span>{{ $mainOrder->wy_order_number }}</span>
                    				</td>
                    				<td>
                    					<strong>下单时间：</strong>
                    					<span>{{ $mainOrder->wy_generate_time }}</span>
                    				</td>
                    			</tr>
                    			<tr>
                    				<td>
                    					<strong>订单状态：</strong>
                    					<span class="font-green">{{ $mainOrder->wy_order_state_name }}</span>
                    				</td>
                    				@if ( 1 == $mainOrder->wy_order_state )
                    				<td>
                    					<strong>下单时间：</strong>
                    					<span>{{ $mainOrder->wy_generate_time }}</span>
                    				</td>
                    				@elseif ( 2 == $mainOrder->wy_order_state )
                    				<td>
                    					<strong>接单时间：</strong>
                    					<span>{{ $mainOrder->wy_confirm_time }}</span>
                    				</td>
                    				@elseif ( 3 == $mainOrder->wy_order_state )
                    				<td>
                    					<strong>配送时间：</strong>
                    					<span>{{ $mainOrder->wy_send_time }}</span>
                    				</td>
									@elseif ( 4 == $mainOrder->wy_order_state )
									<td>
                    					<strong>完成时间：</strong>
                    					<span>{{ $mainOrder->wy_arrive_time }}</span>
                    				</td>
									@elseif ( 5 == $mainOrder->wy_order_state )
									<td>
                    					<strong>取消时间：</strong>
                    					<span>{{ $mainOrder->wy_cancel_time }}</span>
                    				</td>
									@elseif ( 6 == $mainOrder->wy_order_state )
									<td>
                    					<strong>拒单时间：</strong>
                    					<span>{{ $mainOrder->wy_refuse_time }}</span>
                    				</td>
                    				@endif
                    			</tr>
                    			<tr>
                    				<td>
                    					<strong>消费金额：</strong>
                    					<span>{{ $mainOrder->wy_consumption_money }}元</span>
                    				</td>
                    				<td>
                    					<strong>实际金额：</strong>
                    					<span>{{ $mainOrder->wy_actual_money }}元</span>
                    				</td>
                    			</tr>
                    			<tr>
                    				<td>
                    					<strong>优惠金额：</strong>
                    					<span>{{ $mainOrder->wy_consumption_money - $mainOrder->wy_actual_money }}元</span>
                    				</td>
                    				<td>
                    					<strong>店铺名称：</strong>
                    					<span>{{ $mainOrder->wy_shop_name }}</span>
                    				</td>
                    			</tr>
                    			@if ( 1 == $mainOrder->wy_reminder_flag )
                    			<tr>
                    				<td>
                    					<strong>催单状态：</strong>
                    					<span>{{ $mainOrder->wy_reminder_flag_name }}</span>
                    				</td>
                    				<td>
                    					<strong>催单时间：</strong>
                    					<span>{{ $mainOrder->wy_reminder_time }}</span>
                    				</td>
                    			</tr>
                    			@endif
                    		</tbody>
                    	</table>
                    </div>
	            </div>
	        </div>
    	</div>
    	<div class="row">
	        <div class="col-lg-12">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
                        <h3 class="panel-title">收货人信息</h3>
                    </div>
                    <div class="panel-body">
						<table class="table order-table">
                    		<tbody>
                    			<tr>
                    				<td>
                    					<strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong>
                    					<span>{{ $mainOrder->wy_recv_name }}</span>
                    				</td>
                    				<td>
                    					<strong>电话号码：</strong>
                    					<span>{{ substr_replace($mainOrder->wy_recv_phone, '****', 3, 4) }}</span>
                    				</td>
                    			</tr>
                    			<tr>
                    				<td>
                    					<strong>详细地址：</strong>
                    					<span>{{ $mainOrder->wy_recv_addr }}</span>
                    				</td>
                    				<td>
                    					<strong>买家留言：</strong>
                    					<span>{{ $mainOrder->wy_user_note }}</span>
                    				</td>
                    			</tr>
                    		</tbody>
                    	</table>
                    </div>
	            </div>
	        </div>
    	</div>
    	<div class="row">
	        <div class="col-lg-12">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
                        <h3 class="panel-title">菜品信息</h3>
                    </div>
                    <div class="panel-body">
						<table class="table table-bordered">
							<colgroup>
	                    		<col class=""></col>
	                    		<col class="w150"></col>
	                    		<col class="w150"></col>
	                    		<col class="w150"></col>
	                    	</colgroup>
	                    	<thead>
	                    		<tr class="col-name">
	                    			<th>商品</th>
	                    			<th>单价（元）</th>
	                    			<th>数量</th>
	                    			<th>金额（元）</th>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		@foreach ( $mainOrder->subOrders as $subIndex => $subOrder )
								<tr class="text-center">
					    			<td>{{ $subOrder->wy_goods_name }}</td>
					    			<td>{{ $subOrder->wy_goods_unit_price }}</td>
					    			<td>{{ $subOrder->wy_goods_amount }}</td>
					    			<td>{{ $subOrder->wy_goods_total_price }}</td>
					    		</tr>
								@endforeach
	                    	</tbody>
						</table>
                    </div>
	            </div>
	        </div>
    	</div>
    	@endif
    </div>
</div>

@stop