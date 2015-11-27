@extends('layouts.admin.admin')

@section('title') @parent 发布菜品  @stop

@section('container')

<div id="content-right">
    <div class="container-fluid">
        <div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>发布菜品</h1>
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.index') }}">主页</a></li>
                    <li>生鲜业务</li>
                    <li class="active">发布菜品</li>
				</ol>
            </div>
        </div>
        
        @include ('admin.template.alert')
        @include ('admin.template.alert_view')

		<div class="row">
            <div class="col-lg-12">
        		<div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">菜品信息</h3>
                    </div>
                    {{ Form::open(array('method' => 'POST', 'route' => 'goods.add', 'name' => 'goods_add', 'id' =>'goods_add', 'class' => 'form-horizontal')) }}
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="shop_id" class="col-lg-3 control-label"><span class="required">*</span>被选店铺</label>
                                <div class="col-lg-8">
                                    <input type="hidden" id="shop_id" name="shop_id" value="{{ htmlentities(Input::old('shop_id')) }}">
                                    <div id="shop_selected" class="shop-selected hidden clearfix">
                                        <ul>
                                        </ul>        
                                    </div>
                                    <a id="shop_select" class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#shop_show" aria-expanded="false" aria-controls="shop_show"><span>店铺选择<span class="fa fa-angle-double-down fa-lg pl-5"></span></span></a>
                                    <div class="collapse panel panel-default mb-0" id="shop_show">
                                        <div class="panel-body shop-show">
                                            <ul>
                                                @foreach ( $shops as $index => $shop )
                                                <li>
                                                    <input type="checkbox" value="{{ $shop->wy_shop_id }}">
                                                    <span class="ml-10"> {{ $shop->wy_shop_name }} </span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="panel-footer">
                                            <button type="button" id="shop_select_submit" class="btn btn-primary btn-sm center-block">确定</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_name" class="col-lg-3 control-label"><span class="required">*</span>菜品名称</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_name" id="goods_name" class="form-control" placeholder="菜品名称（最多为32个字符）" value="{{ Input::old('goods_name') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_icon_name" class="col-lg-3 control-label"><span class="required">*</span>菜品图片</label>
                                <input type="hidden" name="goods_icon_name" id="goods_icon_name" value="{{ Input::old('goods_icon_name')}}">
                                <div class="col-lg-5">
                                    @if ( !empty(Input::old('goods_icon_name')) )
                                        <div class="img-preview" id="img-preview">
                                            <span class="hidden">上传图片</span>
                                            <img src="http://image.10000times.com/newserver/showImg.php?img_id={{ Input::old('goods_icon_name') }}">
                                        </div>
                                        {{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
                                        <div id="goods_icon_upload" class="fileinput-btn btn btn-primary">
                                            <span class="fa fa-upload"><span class="pl-5">更改</span></span>
                                            <input type="file" name="goods_icon" id="goods_icon" value="{{ Input::old('goods_icon')}}">
                                        </div>
                                    @else
                                    <div class="img-preview" id="img-preview">
                                        <span>上传图片</span>
                                        <img src="" class="hidden">
                                    </div>
                                    {{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
                                    <div id="goods_icon_upload" class="fileinput-btn btn btn-primary">
                                        <span class="fa fa-upload"><span class="pl-5">上传</span></span>
                                        <input type="file" name="goods_icon" id="goods_icon" value="{{ Input::old('goods_icon')}}" required>
                                    </div>
                                    @endif
                                    <img src="{{ asset("assets/img/progressbar.gif") }}" id="img-loading" class="img-loading hidden">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_type" class="col-lg-3 control-label"><span class="required">*</span>菜品类型</label>
                                <div class="col-lg-2">
                                    <select class="form-control" name="goods_type" id="goods_type" data-value="{{ Input::old('goods_type') }}">
                                    @include ('admin.template.dic.type', array('types' => $types, 'typeValue' => Input::old('goods_type')));
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_price" class="col-lg-3 control-label"><span class="required">*</span>菜品价格</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_price" id="goods_price" class="form-control" placeholder="菜品价格" value="{{ Input::old('goods_price') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_stock" class="col-lg-3 control-label"><span class="required">*</span>菜品库存</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_stock" id="goods_stock" class="form-control" placeholder="菜品库存" value="{{ Input::old('goods_stock') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_brief" class="col-lg-3 control-label"></span>菜品描述</label>
                                <div class="col-lg-8">
                                    <textarea name="goods_brief" id="goods_brief" class="form-control" rows="5" placeholder="菜品的描述（最多为255个字符）">{{ Input::old('goods_brief') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" class="btn btn-success bg-green center-block" value="提 交">
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('afterScript')
    @parent
    {{ HTML::script('assets/js/goods.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            Goods.init();
        });
    </script>
@stop