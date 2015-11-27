@extends('layouts.admin.admin', compact('headerShop','disableChange'))

@section('title') @parent 菜品信息  @stop

@section('container')

<div id="content-right">
    <div class="container-fluid">
        <div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>菜品信息</h1>
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.index') }}">主页</a></li>
                    <li>生鲜业务</li>
                    <li><a href="{{ route('goods.list.selling', array("shopID" => $headerShop->wy_shop_id)) }}">出售中菜品</a></li>
                    <li class="active">菜品信息</li>
				</ol>
            </div>
        </div>
        
        @include ('admin.template.alert')

		<div class="row">
            <div class="col-lg-12">
        		<div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $good->wy_goods_name }}</h3>
                    </div>
                    {{ Form::open(array('method' => 'POST', 'route' => 'goods.modify', 'name' => 'goods_modify', 'id' =>'goods_modify', 'class' => 'form-horizontal')) }}
                        <input type="hidden" name="shop_id" id="shop_id" value="{{ $headerShop->wy_shop_id }}">
                        <input type="hidden" name="goods_id" id="goods_id" value="{{ $good->wy_goods_id }}">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="goods_name" class="col-lg-3 control-label">菜品名称</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_name" id="goods_name" class="form-control" placeholder="菜品名称" value="{{ $good->wy_goods_name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_icon_name" class="col-lg-3 control-label"><span class="required">*</span>菜品图片</label>
                                <input type="hidden" name="goods_icon_name" id="goods_icon_name" value="{{ $good->wy_goods_icon }}">
                                <div class="col-lg-5">
                                    <div class="img-preview" id="img-preview">
                                        <span class="hidden">上传图片</span>
                                        <img src="http://image.10000times.com/newserver/showImg.php?img_id={{ $good->wy_goods_icon }}">
                                    </div>
                                    {{-- <button type="button" id="shop_icon_delete" class="btn btn-danger hidden"><span class="fa fa-trash"><span class="pl-5">删除</span></span></button> --}}
                                    <div id="goods_icon_upload" class="fileinput-btn btn btn-primary">
                                        <span class="fa fa-upload"><span class="pl-5">更改</span></span>
                                        <input type="file" name="goods_icon" id="goods_icon">
                                    </div>
                                    <img src="{{ asset("assets/img/progressbar.gif") }}" id="img-loading" class="img-loading hidden">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_type" class="col-lg-3 control-label"><span class="required">*</span>菜品类型</label>
                                <div class="col-lg-2">
                                    <select class="form-control" name="goods_type" id="goods_type">
                                    {{ $goodsType }}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_price" class="col-lg-3 control-label"><span class="required">*</span>菜品价格</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_price" id="goods_price" class="form-control" placeholder="菜品价格" value="{{ $good->wy_goods_sale_price }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_stock" class="col-lg-3 control-label"><span class="required">*</span>菜品库存</label>
                                <div class="col-lg-5">
                                    <input type="text" name="goods_stock" id="goods_stock" class="form-control" placeholder="菜品库存" value="{{ $good->wy_stock }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_brief" class="col-lg-3 control-label"></span>菜品描述</label>
                                <div class="col-lg-8">
                                    <textarea name="goods_brief" id="goods_brief" class="form-control" rows="5" placeholder="菜品的描述（最多为255个字符）">{{ $good->wy_brief }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" class="btn btn-success bg-green center-block" value="修 改">
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
            Goods.initGoodsInfo();
        });
    </script>
@stop