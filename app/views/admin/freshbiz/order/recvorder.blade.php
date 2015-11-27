@foreach ( $mainRecvOrders as $mainIndex => $mainOrder )
	<tbody data-mainorderid="{{ $mainOrder->wy_main_order_id }}" data-mainorderstatus="{{ $mainOrder->wy_order_state }}" data-shoopid="{{ $mainOrder->wy_shop_id }}">
		<tr class="sep-row"></tr>
		<tr class="order-header">
			<td colspan="3">
                <ul>
                    <li>
                        <div>
                            <strong>订单编号：</strong>
                            <span>{{ $mainOrder->wy_order_number }}</span>
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
                            <strong>接单时间：</strong>
                            <span>{{ $mainOrder->wy_confirm_time }}</span>
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
            <td>
                <div class="print">
                     <a class="btn btn-default btn-sm" href="{{ route('order.print', array($mainOrder->wy_shop_id, $mainOrder->wy_main_order_id)) }}" target="_blank"><span class="fa fa-print"></span>打印发货单</a>
                </div>
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
        					<button id="deliverying_order" class="btn btn-success btn-sm bg-green"><span class="fa fa-paper-plane"><span class="pl-5">配送</span></span></button>
        				</div>
        			</td>
        		@endif
    		</tr>
		@endforeach
	</tbody>
@endforeach