@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 评价管理  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>评价管理</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>管理</li>
					<li class="active">评价管理</li>
				</ol>
            </div>
        </div>

        @include ('admin.template.alert')

		@if ( isset($headerShop) )
			@if ( isset($all) )
			<h1 class="font-red">{{ $all }}</h1>
			@else
	        <div class="row">
		        <div class="col-lg-12">
		        	<ul class="nav nav-pills" role="tablist">
		        		<li role="presentation" class="active"><a href="#user_rate" aria-controls="user_rate" role="tab" data-toggle="pill">来自用户的评价</a></li>
		        	</ul>
		        	<div class="tab-content">
		        		<div class="tab-pane fade in active panel panel-default" id="user_rate">
			        		<div class="panel-heading">
		                        <h3 class="panel-title">店铺评分</h3>
		                    </div>
		                    <div class="panel-body">
		                    	<div>
		                    		<span>整体评价：<strong class="num-1">{{ $shop->wy_comprehensive_evaluation }}</strong> 分</span>
		                    		<div class="mt-15">
		                    			<span>配送服务：<strong class="num-1">{{ $shop->wy_service_score }}</strong> 分</span>
		                    			<span class="ml-10">商品质量：<strong class="num-1">{{ $shop->wy_goods_score }}</strong> 分</span>
		                    		</div>
		                    	</div>
		                    	{{ $rates->appends(array('shopID' => $headerShop->wy_shop_id))->links('admin.template.pagination.simple') }}
			                    <div class="rate">
			                    	<table id="rate_list_table" class="table table-bordered">
					                	<thead>
					                		<tr class="col-name">
					                			<th>评价信息</th>
					                		</tr>
					                	</thead>
					                	@foreach ( $rates as $index => $rate )
					                	<tbody data-commentid="{{ $rate->wy_comment_id }}" data-shopid="{{ $headerShop->wy_shop_id }}" data-mainorderid="{{ $rate->wy_main_order_id }}">
					                		<tr class="rate-user">
												<td colspan="3">
													<span><strong>订单编号：</strong>{{ $rate->wy_order_number }}</span>
													<span class="ml-20"><strong>评价人：</strong>{{ $rate->wy_user_name }}</span>
													<span class="ml-20"><strong>评价时间：</strong>{{ $rate->wy_time }}</span>
												</td>
											</tr>
											<tr>
												<td>
													<div class="content">
														<strong>评价内容：</strong>
														<span>{{ $rate->wy_content }}</span>
													</div>
												</td>
											</tr>
					                	</tbody>
					                	@endforeach
					                </table>
			                    </div>
			                    {{ $rates->appends(array('shopID' => $headerShop->wy_shop_id))->links('admin.template.pagination.slider') }}
		                    </div>
		            	</div>
		        	</div>
		        </div>
	    	</div>
	    	@endif
	    @else
	    <h1 class="font-red">{{ $error }}</h1>
	    @endif
    </div>
</div>

@stop