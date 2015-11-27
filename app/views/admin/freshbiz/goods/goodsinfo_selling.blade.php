@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 出售中菜品  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>出售中菜品</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>外卖业务</li>
					<li class="active">出售中菜品</li>
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
		        	<div class="panel panel-default">
		        		<div class="panel-heading">
	                        <h3 class="panel-title">菜品信息</h3>
	                    </div>
	                    <div class="panel-body">
	                    </div>
	                    {{ $goods->appends(array('shopID' => $headerShop->wy_shop_id))->links('admin.template.pagination.simple') }}
	                    <div>
	                    	<table id="goods_list_table" class="table table-bordered">
			                	<colgroup>
			                		<col class=""></col>
			                		<col class="w150"></col>
			                		<col class="w100"></col>
			                		<col class="w200"></col>
			                		<col class="w150"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class="w150"></col>
			                	</colgroup>
			                	<thead>
			                		<tr class="col-name">
			                			<th>菜品名称</th>
			                			<th>菜品图片</th>
			                			<th>价格（元）</th>
			                			<th>销量/库存</th>
			                			<th>上架时间</th>
			                			<th>推荐次数</th>
			                			<th>类型</th>
			                			<th>操作</th>
			                		</tr>
			                	</thead>
			                	@foreach ( $goods as $index => $good )
			                	<tbody data-shopid="{{ $headerShop->wy_shop_id }}" data-goodsid="{{ $good->wy_goods_id }}" data-goodsname="{{ $good->wy_goods_name }}" data-goodsstatus="2">
			                		<tr class="text-center">
										<td>{{ $good->wy_goods_name }}</td>
										<td>
											<img src="http://image.10000times.com/newserver/showImg.php?img_id={{ $good->wy_goods_icon }}" alt="{{ $good->wy_goods_name }}" class="img-rounded img-size"></img>
										</td>
										<td>{{ $good->wy_goods_sale_price }}</td>
										<td>{{ $good->wy_sale_count }}/{{ $good->wy_stock }}</td>
										<td>{{ $good->wy_onsale_time }}</td>
										<td><span class="fa fa-thumbs-o-up font-green"><span class="pl-5">{{ $good->wy_recommend_times }}</span></span></td>
										<td>{{ $good->wy_goods_type_name }}</td>
										<td>
											<div>
					        					<a class="btn btn-success btn-xs bg-green" href="{{ route('goods.list.selling.info', array("goodsID" => $good->wy_goods_id, "shopID" => $headerShop->wy_shop_id)) }}">
												<span class="fa fa-search-plus"><span class="pl-5">菜品信息</span></span></a>
					        				</div>
					        				<div class="mt-15">
												<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#goods_changestatus_modal"><span class="fa fa-arrow-circle-o-down"><span class="pl-5">菜品下架</span></span></button>
											</div>
											<div class="mt-15">
												<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#goods_delete_modal"><span class="fa fa-trash"><span class="pl-5">删除菜品</span></span></button>
											</div>
										</td>
									</tr>
			                	</tbody>
			                	@endforeach
			                </table>
	                    </div>
	                    {{ $goods->appends(array('shopID' => $headerShop->wy_shop_id))->links('admin.template.pagination.slider') }}
		            </div>
		        </div>
	    	</div>
	    	@endif
	    @else
	    <h1 class="font-red">{{ $error }}</h1>
	    @endif
    </div>

    @if ( isset($headerShop) && !isset($all) )
    @include ('admin.template.infomodal')
    @include ('admin.freshbiz.goods.goodschangestatus', array('modalTitle' => '菜品下架', 'modalBody' => '下架'))
	@include ('admin.freshbiz.goods.goodsdelete')
	@endif
</div>

@stop

@section('afterScript')
    @parent
    {{ HTML::script('assets/js/goods.js') }}
    @if ( isset($headerShop) && !isset($all) )
    <script type="text/javascript">
        $(document).ready(function() {
            Goods.initGoodsInfoList();
        });
    </script>
    @endif
@stop