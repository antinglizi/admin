@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 店铺信息  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
		    <div class="col-lg-12">
		        <h1>店铺信息</h1>
		        <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>管理</li>
					@if ( isset($headerShop) )
					<li><a href="{{ route('shop.list', array("shopID" => $headerShop->wy_shop_id)) }}">店铺管理</a></li>
					@else
					<li><a href="{{ route('shop.list') }}">店铺管理</a></li>
					@endif
					<li class="active">店铺信息</li>
				</ol>
		    </div>
		</div>
		
		@include ('admin.template.alert')

		<div class="row">
		    <div class="col-lg-12">
		    	<div class="panel panel-default">
		    		<div class="panel-heading">
		                <h3 class="panel-title">{{ $shop->wy_shop_name }}</h3>
		            </div>
		            <div class="panel-body">
			            <ul id="shop_info" class="nav nav-pills" role="tablist">
				    		<li role="presentation"><a href="#shop_basic_info" aria-controls="shop_basic_info" role="tab" data-toggle="pill" data-index="0">基本信息</a></li>
				    		<li role="presentation"><a href="#shop_open_rule" aria-controls="shop_open_rule" role="tab" data-toggle="pill" data-index="1">营业规则</a></li>
				    		{{-- <li role="presentation"><a href="#shop_open_info" aria-controls="shop_open_info" role="tab" data-toggle="pill" data-index="2">营业信息</a></li> --}}
				    	</ul>
				    	<div class="tab-content">
							<div class="tab-pane fade panel panel-default" id="shop_basic_info">
								{{ Form::open(array('method' => 'POST', 'route' => 'shop.modify', 'name' => 'shop_basic_info_modify', 'id' =>'shop_basic_info_modify', 'class' => 'form-horizontal' )) }}
									<input type="hidden" name="shop_info_type" id="shop_info_type" value="0">
									<input type="hidden" name="shop_id" id="shop_id" value="{{ $shop->wy_shop_id }}">
									<div class="panel-body">
										<div class="form-group">
											<label for="shop_name" class="col-lg-3 control-label"></span>店铺名称</label>
											<div class="col-lg-5">
												<input type="text" name="shop_name" id="shop_name" class="form-control" value="{{ $shop->wy_shop_name }}" placeholder="店铺名称" disabled>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_icon_name" class="col-lg-3 control-label"><span class="required">*</span>店铺图片</label>
											<input type="hidden" name="shop_icon_name" id="shop_icon_name" value="{{ $shop->wy_shop_icon }}">
											<div class="col-lg-5">
												<div class="img-preview" id="img-preview">
													<span class="hidden">上传图片</span>
													<img src="http://image.10000times.com/newserver/showImg.php?img_id={{ $shop->wy_shop_icon }}">
												</div>
												{{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
	    										<div id="shop_icon_upload" class="fileinput-btn btn btn-primary">
	    											<span class="fa fa-upload"><span class="pl-5">更改</span></span>
	    											<input type="file" name="shop_icon" id="shop_icon">
	    										</div>
	    										<img src="{{ asset("assets/img/progressbar.gif") }}" id="img-loading" class="img-loading hidden">
											</div>
										</div>
										<div class="form-group">
											<label for="shop_type" class="col-lg-3 control-label"><span class="required">*</span>店铺类型</label>
											<div class="col-lg-2">
												<select class="form-control" name="shop_type" id="shop_type">
												{{ $shopType }}
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_phone" class="col-lg-3 control-label"><span class="required">*</span>店铺电话</label>
											<div class="col-lg-5">
												<input type="text" name="shop_phone" id="shop_phone" class="form-control" value="{{ $shop->wy_phone }}" placeholder="联系电话号码" required>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_region" class="col-lg-3 control-label"><span class="required">*</span>店铺地址</label>
											<div class="col-lg-2">
												<select class="form-control" name="shop_province" id="shop_province" data-regionlevel="1">
												@foreach ( $provinceValues as $index => $shopRegion )
													@if ( $shop->wy_province_id == $shopRegion->wy_region_id )
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}" selected>{{ $shopRegion->wy_region_name }}</option>
													@else
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}">{{ $shopRegion->wy_region_name }}</option>
													@endif
												@endforeach
												</select>
											</div>
											<div class="col-lg-2">
												<select class="form-control" name="shop_city" id="shop_city" data-regionlevel="2">
												@foreach ( $cityValues as $index => $shopRegion )
													@if ( $shop->wy_city_id == $shopRegion->wy_region_id )
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}" selected>{{ $shopRegion->wy_region_name }}</option>
													@else
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}">{{ $shopRegion->wy_region_name }}</option>
													@endif
												@endforeach
												</select>
											</div>
											<div class="col-lg-2">
												<select class="form-control" name="shop_district" id="shop_district" data-regionlevel="3">
												@foreach ( $districtValues as $index => $shopRegion )
													@if ( $shop->wy_district_id == $shopRegion->wy_region_id )
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}" selected>{{ $shopRegion->wy_region_name }}</option>
													@else
													<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}">{{ $shopRegion->wy_region_name }}</option>
													@endif
												@endforeach
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_addr" class="sr-only col-lg-3 control-label"><span class="required">*</span>店铺详细地址</label>
											<div class="col-lg-5">
												<input type="text" name="shop_addr" id="shop_addr" class="form-control" placeholder="店铺详细地址（最多为255个字符）" value="{{ $shop->wy_addr }}" required>
											</div>
											<div class="col-lg-2">
												<input type="hidden" name="shop_longitude" id="shop_longitude" class="form-control" value="{{ $shop->wy_longitude }}" required>
											</div>
											<div class="col-lg-2">
												<input type="hidden" name="shop_latitude" id="shop_latitude" class="form-control" value="{{ $shop->wy_latitude }}" required>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_map" class="sr-only col-lg-3 control-label"><span class="required">*</span>店铺地图坐标</label>
											<div class="col-lg-8">
												<button type="button" id="shop_detail_addr" class="btn btn-primary">获取店铺地图坐标</button>
												<span class="ml-10">如果定位有误，请拖动红色图标到准去位置</span>
												<div id="shop_map" class="map"></div>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_keywords" class="col-lg-3 control-label"></span>店铺关键字</label>
											<div class="col-lg-5">
												<input name="shop_keywords" id="shop_keywords" class="form-control" value="{{ $shop->wy_keywords }}" placeholder="店铺关键字，便于搜索（最多为128个字符）"></input>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_brief" class="col-lg-3 control-label"></span>店铺描述</label>
											<div class="col-lg-8">
												<textarea name="shop_brief" id="shop_brief" class="form-control" rows="5" placeholder="店铺的描述（最多为255个字符）">{{ $shop->wy_brief }}</textarea>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<input type="submit" class="btn btn-success bg-green center-block" value="修 改">
									</div>
								{{ Form::close() }}
							</div>
							<div class="tab-pane fade panel panel-default" id="shop_open_rule">
								{{ Form::open(array('method' => 'POST', 'route' => 'shop.modify', 'name' => 'shop_open_rule_modify', 'id' =>'shop_open_rule_modify', 'class' => 'form-horizontal' )) }}
									<input type="hidden" name="shop_info_type" id="shop_info_type" value="1">
									<input type="hidden" name="shop_id" id="shop_id" value="{{ $shop->wy_shop_id }}">
									<div class="panel-body">
										<div class="form-group">
											<label class="col-lg-3 control-label"><span class="required">*</span>营业时间</label> 
											<div class="col-lg-2">
												<input type="hidden" name="shop_open_begin" id="shop_open_begin" value="{{ $shop->wy_open_begin }}">
											</div>
											<div class="col-lg-1">
												<span class="label label-success center-block mt-5">至</span>
											</div>
											<div class="col-lg-2">
												<input type="hidden" name="shop_open_end" id="shop_open_end" value="{{ $shop->wy_open_end }}">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label"><span class="required">*</span>配送时间</label>
											<div class="col-lg-2">
												<input type="hidden" name="shop_delivery_begin" id="shop_delivery_begin" value="{{ $shop->wy_delivery_begin }}">
											</div>
											<div class="col-lg-1">
												<span class="label label-success center-block mt-5">至</span>
											</div>
											<div class="col-lg-2">
												<input type="hidden" name="shop_delivery_end" id="shop_delivery_end" value="{{ $shop->wy_delivery_end }}">
											</div>
										</div>
										<div class="form-group">
											<label for="shop_delivery_time" class="col-lg-3 control-label"><span class="required">*</span>承若送达时间</label>
											<div class="col-lg-3">
												<div class="input-group">
													<input type="text" name="shop_delivery_time" id="shop_delivery_time" class="form-control" value="{{ $shop->wy_send_up_time }}" placeholder="比如45分钟" required>
													<span class="input-group-addon">分钟</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_distance" class="col-lg-3 control-label"><span class="required">*</span>配送范围</label>
											<div class="col-lg-2">
												<div class="input-group">
													<input type="text" name="shop_distance" id="shop_distance" class="form-control" value="{{ $shop->wy_distance }}" placeholder="比如3公里" required>
													<span class="input-group-addon">公里</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="shop_delivery_fee" class="col-lg-3 control-label"><span class="required">*</span>配送费用</label>
											<div class="col-lg-4">
												<div class="radio">
													<label>
														<input type="radio" name="shop_delivery_free" id="shop_delivery_is_free" value="true" {{0 == floatval($shop->wy_express_fee) ? 'checked' : ''}}>
														<span>免配送费</span>
													</label>
												</div>
												<div class="radio">
													<label>
														<input type="radio" name="shop_delivery_free" id="shop_delivery_has_fee" value="false" {{ 0 == floatval($shop->wy_express_fee) ? '' : 'checked'}}>
														<div class="input-group">
															<input type="text" name="shop_delivery_fee" id="shop_delivery_fee" class="form-control" value="{{ 0 == floatval($shop->wy_express_fee) ? '' : $shop->wy_express_fee }}" placeholder="比如5元">
															<span class="input-group-addon">元配送费</span>
														</div>
													</label>
												</div>
												<div class="checkbox">
													<label>
													 	<input type="checkbox" name="shop_has_min_amount" id="shop_has_min_amount" value="true" {{ 0 == floatval($shop->wy_send_up_price) ? '' : 'checked'}}>
													 	<div class="input-group">
															<span class="input-group-addon">单笔满</span>
															<input type="text" name="shop_delivery_price" id="shop_delivery_price" class="form-control" value="{{ 0 == floatval($shop->wy_send_up_price) ? '' : $shop->wy_send_up_price }}" placeholder="比如20元">
															<span class="input-group-addon">元起送</span>
														</div>
													</label>
												</div>
												{{-- <div class="checkbox">
													<label>
													 	<input type="checkbox" name="shop_delivery_fee" id="shop_delivery_fee">
													 	<div class="input-group">
															<span class="input-group-addon">单笔超过</span>
															<input type="text" name="shop_delivery_fee" id="shop_delivery_fee" class="form-control" placeholder="30元">
															<span class="input-group-addon">元免配送费</span>
														</div>
													</label>
												</div> --}}
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<input type="submit" class="btn btn-success bg-green center-block" value="修 改">
									</div>
								{{ Form::close() }}
							</div>
							{{-- <div class="tab-pane fade panel panel-default" id="shop_open_info">
							{{ Form::open(array('method' => 'POST', 'route' => 'shop.modify', 'name' => 'shop_open_rule_modify', 'id' =>'shop_open_rule_modify', 'class' => 'form-horizontal' )) }}
									<input type="hidden" name="shop_info_type" id="shop_info_type" value="2">
									<input type="hidden" name="shop_id" id="shop_id" value="{{ $shop->wy_shop_id }}">
									<div class="panel-body">
									</div>

									<div class="panel-footer">
										<input type="submit" class="btn btn-primary center-block" value="修改">
									</div>
							{{ Form::close() }}
							</div> --}}
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
	{{ HTML::script('http://api.map.baidu.com/api?v=2.0&ak=hwGS60zBnBRh5YZWyhXWBRAK') }}
    {{ HTML::script('assets/js/shopinfo.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            ShopInfo.init();
        });
    </script>
@stop