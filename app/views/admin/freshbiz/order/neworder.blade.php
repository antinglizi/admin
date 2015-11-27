@foreach ( $mainNewOrders as $mainIndex => $mainOrder )
	<tbody data-mainorderid="{{ $mainOrder->wy_main_order_id }}" data-mainorderstatus="{{ $mainOrder->wy_order_state }}" data-shoopid="{{ $mainOrder->wy_shop_id }}">
		<tr class="sep-row"></tr>
		<tr class="order-header">
			<td colspan="4">
    			<ul>
                    <li>
                        <div>
                            <strong>订单编号：</strong>
                            <span id="order_number">{{ $mainOrder->wy_order_number }}</span>
                        </div>
                        <div>
                            <strong>收&nbsp;&nbsp;货&nbsp;&nbsp;人：</strong>
                            <span>{{ $mainOrder->wy_recv_name }}</span>
                        </div>
                        <div>
                            <strong>配送地址：</strong>
                            <span>{{ $mainOrder->wy_recv_addr }}</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <strong>下单时间：</strong>
                            <span>{{ $mainOrder->wy_generate_time }}</span>
                        </div>
                        <div>
                            <strong>联系电话：</strong>
                            <span>{{ $mainOrder->wy_recv_phone }}</span>
                        </div>
                        <div>
                            <strong>买家留言：</strong>
                            <span>{{ $mainOrder->wy_user_note }}</span>
                        </div>
                    </li>   
                </ul>
			</td>
			<td colspan="2">
				<ul>
                    <li>
                        <div>
                            <strong>消费金额（元）：</strong>
                            <span>{{ $mainOrder->wy_consumption_money }}</span>
                        </div>
                        <div>
                            <strong>优惠金额（元）：</strong>
                            <span>{{ $mainOrder->wy_consumption_money - $mainOrder->wy_actual_money }}</span>
                        </div>
                        <div>
                            <strong>订单总额（元）：</strong>
                            <span>{{ $mainOrder->wy_actual_money }}</span>
                        </div>
                    </li>            
                </ul>
			</td>
		</tr>
		@foreach ( $mainOrder->subOrders as $subIndex => $subOrder )
			<tr class="text-center">
    			<td>{{ $subOrder->wy_goods_name }}</td>
    			<td>{{ $subOrder->wy_goods_unit_price }}</td>
    			<td>{{ $subOrder->wy_goods_amount }}</td>
    			<td>{{ $subOrder->wy_goods_total_price }}</td>
    			@if ( 0 == $subIndex )
        			<td rowspan="{{ count($mainOrder->subOrders) }}"><span class="label label-warning">{{ $mainOrder->wy_order_state_name }}</span></td>
        			<td rowspan="{{ count($mainOrder->subOrders) }}">
        				<div>
        					<button id="recved_order" class="btn btn-success btn-sm bg-green"><span class="fa fa-check"><span class="pl-5">接单</span></span></button>
        				</div>
						<div class="mt-15">
							<button id="refused_order" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#refused_order_modal"><span class="fa fa-times"><span class="pl-5">拒单</span></span></button>
						</div>
        			</td>
        		@endif
    		</tr>
		@endforeach
	</tbody>
@endforeach