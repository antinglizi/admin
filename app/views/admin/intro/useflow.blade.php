@extends('layouts.admin.admin')

@section('title') @parent 首页  @stop

@section('container')
	
	<div id="content-right">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-lg-12">
	                <h1 class="page-header">您暂时还没有店铺，请快快去增加店铺吧！</h1>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-lg-12">
	                <div class="panel panel-default">
	                    <div class="panel-heading">
	                        <h3 class="panel-title">生鲜业务开启流程</h3>
	                    </div>
	                    <div class="panel-body">        
	                    	<div class="useflow">
	                    		<ul>
		                    		<li>
		                    			<div class="circle">
		                    			<strong>增加店铺</strong>
		                    			</div>
		                    			<span class="fa fa-hand-o-right fa-lg"></span>
		                    		</li>
		                    		<li>
		                    			<div class="circle">
		                    			<strong>填写信息</strong>
		                    			</div>
		                    			<span class="fa fa-hand-o-right fa-lg"></span>
		                    		</li>
		                    		<li>
		                    			<div class="circle">
		                    			<strong>上传资料</strong>
		                    			</div>
		                    			<span class="fa fa-hand-o-right fa-lg"></span>
		                    		</li>
		                    		<li>
		                    			<div class="circle">
		                    			<strong>平台审核</strong>
		                    			</div>
		                    			<span class="fa fa-jpy fa-2x"></span>
		                    		</li>
		                    		<li>
		                    			<a class="btn btn-success bg-green" href="{{ route('shop.list') }}">马上进入店铺管理</a>
		                    		</li>
	                    		</ul>
	                    		<div class="info">
	                    			<p>需要准备的材料：清晰度较高的经营许可证，卫生许可证，营业执照的照片，菜品照片</p>
	                    			<p>平台审核时间：一般审核需要3~5个工作日</p>
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@stop