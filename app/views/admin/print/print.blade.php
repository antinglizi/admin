@extends('layouts.base')

@section('title') @parent 文蚁后台管理系统—打印发货单 @stop

@section('beforeStyle')
	@parent
	{{ HTML::style('assets/lib/bootstrap-3.3.2/css/bootstrap.min.css') }}
	{{ HTML::style('assets/css/vendor/font-awesome.min.css') }}
	{{ HTML::style('assets/css/main.css') }}
	{{ HTML::style('assets/css/print.css') }}
@stop

@section('body')

	<div id="order-print-area" class="container order-print">
		<div class="order-print-header">
    	</div>
		<div class="order-print-area">
        	<h3>{{ $shop->wy_shop_name }} 发货单</h3>
    		<table class="user-info">
        		<tbody>
        			<tr>
        				<td><strong>收货人：</strong>{{ $mainOrder->wy_recv_name }}</td>
        				<td></td>
        				<td><strong>电话：</strong>{{ $mainOrder->wy_recv_phone }}</td>
        			</tr>
        			<tr>
        				<td colspan="3"><strong>地址：</strong>{{ $mainOrder->wy_recv_addr }}</td>
        			</tr>
        		</tbody>
        	</table>
        	<table class="table order-info">
        		<thead>
					<tr>
						<td></td>
        				<td><strong>订单号：</strong>{{ $mainOrder->wy_order_number }}</td>
        				<td></td>
        				<td colspan="2"><strong>下单时间：</strong>{{ $mainOrder->wy_book_time }}</td>
        			</tr>
        			<tr>
        				<th>序号</th>
        				<th>商品名称</th>
        				<th>单价（元）</th>
        				<th>数量</th>
        				<th>小计</th>
        			</tr>
        		</thead>
        		<tbody>
        			@foreach ( $mainOrder->subOrders as $subIndex => $subOrder )
						<tr>
	        				<td>{{ $subIndex + 1 }}</td>
		        			<td>{{ $subOrder->wy_goods_name }}</td>
		        			<td>¥{{ $subOrder->wy_goods_unit_price }}</td>
		        			<td>{{ $subOrder->wy_goods_amount }}</td>
		        			<td>¥{{ $subOrder->wy_goods_total_price }}</td>
	        			</tr>
        			@endforeach
        			<tr>
        				<th></th>
	        			<th colspan="2">合计</th>
	        			<th>{{ $mainOrder->goodsTotalAmout }}</th>
	        			<th>¥{{ $mainOrder->wy_actual_money }}</th>
        			</tr>
        		</tbody>
        		<tfoot>
        			<tr>
        				<th colspan="5">
        					<span>总计：¥{{ $mainOrder->wy_actual_money }}</span>
        					<span>运费：¥0.00</span>
        					<span>优惠：¥0</span>
        					<span>订单总额：¥{{ $mainOrder->wy_actual_money }}</span>
        				</th>
        			</tr>
        		</tfoot>
        	</table>            
		</div>
		<div class="order-print-footer">
			
		</div>
		<div class="order-print-control hidden-print">
			<a id="order-print" href="javascript:void(0)" class="btn btn-success"><span class="fa fa-print fa-2x">打印</span></a>
		</div>
	</div>

@stop

@section('afterScript')
	@parent
	{{ HTML::script('assets/js/plugins.js') }}
	{{ HTML::script('assets/js/vendor/jquery-1.11.2.min.js') }}
	{{ HTML::script('assets/lib/bootstrap-3.3.2/js/bootstrap.min.js') }}
	{{ HTML::script('assets/js/vendor/jQuery.print.js') }}
	{{-- {{ HTML::script('assets/js/vendor/jQuery.printarea.js') }} --}}
	<script type="text/javascript">
        $(document).ready(function() {
            $('#order-print').click( function(event){
            	event.preventDefault();
				$('#order-print-area').print();
            });
            
        });
    </script>
@stop
