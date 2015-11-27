@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 店铺管理  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>店铺管理</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>管理</li>
					<li class="active">店铺管理</li>
				</ol>
            </div>
        </div>

        @include ('admin.template.alert')
	
    	<div class="row">
	        <div class="col-lg-12">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
                        <h3 class="panel-title">店铺信息</h3>
                    </div>
                    <div class="panel-body">
                    	<div class="pull-right">
                    		<button class="btn btn-success bg-green" data-toggle="modal" data-target="#shop_add_modal">
								<span class="fa fa-plus-circle fa-lg"><span class="pl-5 ">增加店铺</span></span>
							</button>
                    	</div>
                    </div>
                    <table class="table table-bordered">
	                	<colgroup>
	                		<col class="w150"></col>
	                		<col class="w400"></col>
	                		<col class="w200"></col>
	                		<col class="w100"></col>
	                		<col class="w100"></col>
	                		<col class=""></col>
	                	</colgroup>
	                	<thead>
	                		<tr class="col-name">
	                			<th>店铺</th>
	                			<th>地址</th>
	                			<th>电话</th>
	                			<th>类型</th>
	                			<th>审核状态</th>
	                			<th>操作</th>
	                		</tr>
	                	</thead>
                	</table>
                    <div class="scroll">
                    	<table id="shop_list_table" class="table table-bordered">
		                	<colgroup>
		                		<col class="w150"></col>
		                		<col class="w400"></col>
		                		<col class="w200"></col>
		                		<col class="w100"></col>
		                		<col class="w100"></col>
		                		<col class=""></col>
		                	</colgroup>
		                	@foreach ( $shops as $index => $shop )
		                	<tbody data-shopid="{{ $shop->wy_shop_id }}"
		                	data-shopname="{{ $shop->wy_shop_name }}">
								<tr class="sep-row"></tr>
								<tr class="order-header">
									<td colspan="2">
										<strong>店铺名称：</strong>
										<span class="font-green">{{ $shop->wy_shop_name }}</span>
									</td>
									<td colspan="2">
										<strong>建店时间：</strong>
										<span>{{ $shop->wy_start_time}}</span>
									</td>
									<td colspan="2">
										@if ( 4 == $shop->wy_audit_state )
										<div class="shop-status">
											<strong>营业状态：</strong>
											<select class="form-control display-inline w120" data-shopstatus="{{ $shop->wy_state }}">
											@foreach ( $shopStatuses as $index => $shopStatus )
												@if ( $shop->wy_state == $shopStatus->wy_dic_item_id )
													<option value="{{ $shopStatus->wy_dic_item_id }}" selected>{{ $shopStatus->wy_dic_value }}</option>
												@else
													<option value="{{ $shopStatus->wy_dic_item_id }}">{{ $shopStatus->wy_dic_value }}</option>
												@endif
											@endforeach
											</select>
										</div>
										@endif
									</td>
								</tr>
								<tr class="text-center">
									<td>
										<img src="http://image.10000times.com/newserver/showImg.php?img_id={{ $shop->wy_shop_icon }}" alt="{{ $shop->wy_shop_name }}" class="img-rounded img-size"></img>
									</td>
									<td>{{ $shop->wy_addr }}</td>
									<td>{{ $shop->wy_phone }}</td>
									<td>{{ $shop->wy_shop_type_name }}</td>
									<td><span class="label label-warning">{{ $shop->wy_audit_state_name }}</span></td>
									<td>
										<div>
				        					@if ( isset($headerShop) )
				        					<a class="btn btn-success btn-sm bg-green" href="{{ route('shop.info', array("shopInfoID" => $shop->wy_shop_id, "shopID" => $headerShop->wy_shop_id)) }}">
											<span class="fa fa-search-plus"><span class="pl-5">店铺信息</span></span></a>
											@else
											<a class="btn btn-success btn-sm bg-green" href="{{ route('shop.info', array("shopInfoID" => $shop->wy_shop_id)) }}">
											<span class="fa fa-search-plus"><span class="pl-5">店铺信息</span></span></a>
											@endif
				        				</div>
										<div class="mt-15">
											<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#shop_delete_modal"><span class="fa fa-trash"><span class="pl-5">删除店铺</span></span></button>
										</div>
									</td>
								</tr>
		                	</tbody>
		                	@endforeach
		                </table>
                    </div>
	            </div>
	        </div>
		</div>
    </div>
    
    @include ('admin.template.infomodal')
    @include ('admin.manage.shop.shopadd')
	@include ('admin.manage.shop.shopdelete')

</div>

@stop

@section('afterScript')
	@parent
	{{ HTML::script('http://api.map.baidu.com/api?v=2.0&ak=hwGS60zBnBRh5YZWyhXWBRAK') }}
    {{ HTML::script('assets/js/shop.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            Shop.init();            
        });
    </script>
@stop