<div id="shop_add_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
	    <div class="modal-content">
		    {{ Form::open(array('method' => 'POST', 'route' => 'shop.add', 'name' => 'shop_add', 'id' =>'shop_add', 'class' => 'form-horizontal')) }}
			    <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    <h3 class="modal-title">店铺增加</h3>
			    </div>
			    <div class="modal-body">
			    	<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">基本信息</h4>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label for="shop_name" class="col-lg-3 control-label"><span class="required">*</span>店铺名称</label>
								<div class="col-lg-5">
									<input type="text" name="shop_name" id="shop_name" class="form-control" placeholder="店铺名称（最多为64个字符）" value="{{ Input::old('shop_name')}}" required>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_icon_name" class="col-lg-3 control-label"><span class="required">*</span>店铺图片</label>
								<input type="hidden" name="shop_icon_name" id="shop_icon_name" value="{{ Input::old('shop_icon_name')}}">
								<div class="col-lg-5">
									@if ( !empty(Input::old('shop_icon_name')) )
										<div class="img-preview" id="img-preview">
											<span class="hidden">上传图片</span>
											<img src="http://image.10000times.com/newserver/showImg.php?img_id={{ Input::old('shop_icon_name') }}">
										</div>
										{{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
										<div id="shop_icon_upload" class="fileinput-btn btn btn-primary">
											<span class="fa fa-upload"><span class="pl-5">更改</span></span>
											<input type="file" name="shop_icon" id="shop_icon" value="{{ Input::old('shop_icon')}}">
										</div>
									@else
										<div class="img-preview" id="img-preview">
											<span>上传图片</span>
											<img src="" class="hidden">
										</div>
										{{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
										<div id="shop_icon_upload" class="fileinput-btn btn btn-primary">
											<span class="fa fa-upload"><span class="pl-5">上传</span></span>
											<input type="file" name="shop_icon" id="shop_icon" value="{{ Input::old('shop_icon')}}" required>
										</div>
									@endif
									<img src="{{ asset("assets/img/progressbar.gif") }}" id="img-loading" class="img-loading hidden">
								</div>
							</div>
							<div class="form-group">
								<label for="shop_type" class="col-lg-3 control-label"><span class="required">*</span>店铺类型</label>
								<div class="col-lg-2">
									<select class="form-control" name="shop_type" id="shop_type" data-value="{{ Input::old('shop_type')}}">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_phone" class="col-lg-3 control-label"><span class="required">*</span>店铺电话</label>
								<div class="col-lg-5">
									<input type="text" name="shop_phone" id="shop_phone" class="form-control" placeholder="联系电话号码" value="{{ Input::old('shop_phone')}}" required>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_region" class="col-lg-3 control-label"><span class="required">*</span>店铺地址</label>
								<div class="col-lg-2">
									<select class="form-control" name="shop_province" id="shop_province" data-regionlevel="1" data-value="{{ Input::old('shop_province')}}">
									</select>
								</div>
								<div class="col-lg-2">
									<select class="form-control" name="shop_city" id="shop_city" data-regionlevel="2" data-value="{{ Input::old('shop_city')}}">
									</select>
								</div>
								<div class="col-lg-2">
									<select class="form-control" name="shop_district" id="shop_district" data-regionlevel="3" data-value="{{ Input::old('shop_district')}}">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_addr" class="sr-only col-lg-3 control-label"><span class="required">*</span>店铺详细地址</label>
								<div class="col-lg-5">
									<input type="text" name="shop_addr" id="shop_addr" class="form-control" placeholder="店铺详细地址（最多为255个字符）" value="{{ Input::old('shop_addr')}}" required>
								</div>
								<div class="col-lg-2">
									<input type="hidden" name="shop_longitude" id="shop_longitude" class="form-control" value="{{ Input::old('shop_longitude')}}" required>
								</div>
								<div class="col-lg-2">
									<input type="hidden" name="shop_latitude" id="shop_latitude" class="form-control" value="{{ Input::old('shop_latitude')}}" required>
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
									<input name="shop_keywords" id="shop_keywords" class="form-control" placeholder="店铺关键字，便于搜索（最多为128个字符）" value="{{ Input::old('shop_keywords')}}"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_brief" class="col-lg-3 control-label"></span>店铺描述</label>
								<div class="col-lg-8">
									<textarea name="shop_brief" id="shop_brief" class="form-control" rows="5" placeholder="店铺的描述（最多为255个字符）">{{ Input::old('shop_brief') }}</textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">营业规则</h4>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>营业时间</label> 
								<div class="col-lg-2">
									<input type="hidden" name="shop_open_begin" id="shop_open_begin" value="{{ empty(Input::old('shop_open_begin')) ? '10:00' : Input::old('shop_open_begin') }}">
								</div>
								<div class="col-lg-1">
									<span class="label label-success center-block mt-5">至</span>
								</div>
								<div class="col-lg-2">
									<input type="hidden" name="shop_open_end" id="shop_open_end" value="{{ empty(Input::old('shop_open_end')) ? '21:00' : Input::old('shop_open_end') }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>配送时间</label>
								<div class="col-lg-2">
									<input type="hidden" name="shop_delivery_begin" id="shop_delivery_begin" value="{{ empty(Input::old('shop_delivery_begin')) ? '10:30' : Input::old('shop_delivery_begin') }}">
								</div>
								<div class="col-lg-1">
									<span class="label label-success center-block mt-5">至</span>
								</div>
								<div class="col-lg-2">
									<input type="hidden" name="shop_delivery_end" id="shop_delivery_end" value="{{ empty(Input::old('shop_delivery_end')) ? '20:30' : Input::old('shop_delivery_end') }}">
								</div>
							</div>
							<div class="form-group">
								<label for="shop_delivery_time" class="col-lg-3 control-label"><span class="required">*</span>承若送达时间</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="text" name="shop_delivery_time" id="shop_delivery_time" class="form-control" placeholder="比如45分钟" value="{{ Input::old('shop_delivery_time') }}" required>
										<span class="input-group-addon">分钟</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_distance" class="col-lg-3 control-label"><span class="required">*</span>配送范围</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="text" name="shop_distance" id="shop_distance" class="form-control" placeholder="比如3公里" value="{{ Input::old('shop_distance') }}" required>
										<span class="input-group-addon">公里</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="shop_delivery_fee" class="col-lg-3 control-label"><span class="required">*</span>配送费用</label>
								<div class="col-lg-4">
									<div class="radio">
										<label>
											<input type="radio" name="shop_delivery_free" id="shop_delivery_is_free" value="true" {{ 'false' != Input::old('shop_delivery_free') ? 'checked' : '' }} >
											<span>免配送费</span>
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="shop_delivery_free" id="shop_delivery_has_fee" value="false" {{ 'false' == Input::old('shop_delivery_free') ? 'checked' : '' }}>
											<div class="input-group">
												<input type="text" name="shop_delivery_fee" id="shop_delivery_fee" class="form-control" placeholder="比如5元" value="{{ Input::old('shop_delivery_fee') }}">
												<span class="input-group-addon">元配送费</span>
											</div>
										</label>
									</div>
									<div class="checkbox">
										<label>
										 	<input type="checkbox" name="shop_has_min_amount" id="shop_has_min_amount" value="true" {{ 'true' == Input::old('shop_has_min_amount') ? 'checked' : '' }}>
										 	<div class="input-group">
												<span class="input-group-addon">单笔满</span>
												<input type="text" name="shop_delivery_price" id="shop_delivery_price" class="form-control" placeholder="比如20元" value="{{ Input::old('shop_delivery_price') }}">
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
					</div>
					{{-- <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">营业信息</h4>
						</div>
  							<div class="panel-body">
							<div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>经营许可证</label>
								<div class="col-lg-5 fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
										<div class="text-center" style="padding : 5px; width : 100%; height : 100%; background-color: #F5F5F5; border : 1px solid #BBBBBB">添加经营许可照片</div>
										</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
									</div>
									<div>
										<div class="btn-file">
											<input type="file" name="shop_icon" id="shop_icon" required>
										</div>
										<button type="button" class="btn btn-primary fileinput-new" data-trigger="fileinput">选择</button>
										<button type="button" class="btn btn-primary fileinput-exists" data-trigger="fileinput">更改</button>
										<button type="button" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">删除</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>营业执照</label>
								<div class="col-lg-5 fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
										<div class="text-center" style="padding : 5px; width : 100%; height : 100%; background-color: #F5F5F5; border : 1px solid #BBBBBB">添加营业执照照片</div>
										</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
									</div>
									<div>
										<div class="btn-file">
											<input type="file" name="shop_icon" id="shop_icon" required>
										</div>
										<button type="button" class="btn btn-primary fileinput-new" data-trigger="fileinput">选择</button>
										<button type="button" class="btn btn-primary fileinput-exists" data-trigger="fileinput">更改</button>
										<button type="button" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">删除</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>签署协议</label>
								<div class="col-lg-5 fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
										<div class="text-center" style="padding : 5px; width : 100%; height : 100%; background-color: #F5F5F5; border : 1px solid #BBBBBB">添加协议照片</div>
										</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
									</div>
									<div>
										<div class="btn-file">
											<input type="file" name="shop_icon" id="shop_icon" required>
										</div>
										<button type="button" class="btn btn-primary fileinput-new" data-trigger="fileinput">选择</button>
										<button type="button" class="btn btn-primary fileinput-exists" data-trigger="fileinput">更改</button>
										<button type="button" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">删除</button>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
			    </div>
			    <div class="modal-footer">
			    	<input type="submit" class="btn btn-success bg-green center-block" value="提 交">
			    </div>
		    {{ Form::close() }}
	    </div>
  	</div>
</div>