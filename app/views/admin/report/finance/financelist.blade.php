@if ( !empty(Input::all()) )
{{ $mainOrders->appends(Input::all())->links('admin.template.pagination.simple') }}
@else
{{ $mainOrders->links('admin.template.pagination.simple') }}
@endif
<table id="orderreport_list_table" class="table table-bordered">
    <colgroup>
        <col class="w150"></col>
        <col class="w150"></col>
        <col class="w150"></col>
        <col class="w150"></col>
        <col class="w100"></col>
        <col class="w150"></col>
    </colgroup>
    <thead>
        <tr class="col-name">
            <th>订单号</th>
            <th>订单时间</th>
            <th>消费金额</th>
            <th>实际金额</th>
            <th>订单状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    	@foreach ( $mainOrders as $index => $mainOrder )
		<tr class="text-center" data-mainorderid="{{ $mainOrder->wy_main_order_id }}" data-shopid="{{ $mainOrder->wy_shop_id }}">
			<td>{{ $mainOrder->wy_order_number }}</td>
			<td>{{ $mainOrder->wy_generate_time }}</td>
			<td>{{ $mainOrder->wy_consumption_money }}</td>
			<td>{{ $mainOrder->wy_actual_money }}</td>
			<td><span class="label label-warning">{{ $mainOrder->wy_order_state_name }}</span></td>
			<td>
				<a class="btn btn-success btn-xs bg-green" href="{{ route('finance.list.info', array("mainOrderID" => $mainOrder->wy_main_order_id, "shopID" => $mainOrder->wy_shop_id)) }}" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a>
			</td>
		</tr>
		@endforeach
    </tbody>
</table>
@if ( !empty(Input::all()) )
{{ $mainOrders->appends(Input::all())->links('admin.template.pagination.slider') }}
@else
{{ $mainOrders->links('admin.template.pagination.slider') }}
@endif