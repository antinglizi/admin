@foreach ( $mainRefuseOrders as $index => $mainOrder )
    <tbody data-mainorderid="{{ $mainOrder->wy_main_order_id }}" data-mainorderstatus="{{ $mainOrder->wy_order_state }}" data-shopid="{{ $mainOrder->wy_shop_id }}">
        <tr class="text-center">
            <td>{{ $mainOrder->wy_order_number }}</td>
            <td>{{ $mainOrder->wy_recv_name }}</td>
            <td>{{ $mainOrder->wy_generate_time }}</td>
            <td>{{ $mainOrder->wy_refuse_time }}</td>
            <td>{{ $mainOrder->wy_actual_money }}</td>
            <td><span class="label label-warning">{{ $mainOrder->wy_order_state_name }}</span></td>
            <td>
                <a class="btn btn-success btn-xs bg-green" href="{{ route('order.list.info', array("mainOrderID" => $mainOrder->wy_main_order_id, "shopID" => $mainOrder->wy_shop_id)) }}" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">订单详情</span></span></a>
            </td>
        </tr>
    </tbody>
@endforeach