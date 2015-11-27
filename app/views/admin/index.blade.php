@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 主页  @stop

@section('container')

	<div id="content-right">
	    <div class="container-fluid">
	        <div id="alert_msg" class="row">
	            <div class="col-lg-12">
	                <h1 class="page-header">公告</h1>
	            </div>
	        </div>
			
			@include ('admin.template.alert')

	        <div class="row">
	            <div class="col-lg-12">
	                <div class="panel panel-default">
	                    <div class="panel-heading">
	                        <h3 class="panel-title">今日生鲜交易</h3>
	                    </div>
	                    <div class="panel-body">
	                    	<div class="freshbiz-trade">
	                    		<ul>
	                    			<li class="col1">
	                    				<div class="info-left">
	                    					<h3>{{ $totalTurnover }}<span class="unit">元</span></h3>
	                    					<p><strong>今日营业额</strong></p>
	                    				</div>
										<div class="info-right">
											<span class="fa fa-jpy fa-4x icon"></span>
										</div>
	                    			</li>
	                    			<li class="col2">
	                    				<div class="info-left">
	                    					<h3>{{ $totalOrderCount }}<span class="unit">笔</span></h3>
	                    					<p><strong>今日订单数</strong></p>
	                    				</div>
										<div class="info-right">
											<span class="fa fa-line-chart fa-4x icon"></span>
										</div>                   				
	                    			</li>
	                    			<li class="col3">
	                    				<div class="info-left">
	                    					<h3>{{ $newOrderCount }}<span class="unit">笔</span></h3>
	                    					<p><strong>今日待接单</strong></p>
	                    				</div>
										<div class="info-right">
											<span class="fa fa-comments fa-4x icon"></span>
										</div>
	                    			</li>
	                    		</ul>
	                    	</div>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-lg-12">
	                <div class="panel panel-default">
	                    <div class="panel-heading">
	                        <h3 class="panel-title">平台公告</h3>
	                    </div>
	                    <div class="panel-body">
	                    	<div class="announce">
	                    		<ul class="list-group">
									@foreach ( $announces as $index => $announce )
									<li class="list-group-item">
										<a href="javascript:void(0)" data-announceid=" {{ $announce->wy_announce_id }}">
											{{ $announce->wy_announce_title }}
											<span class="announce-date">{{ $announce->wy_announce_date }}</span>
										</a>
									</li>
									@endforeach
								</ul>
	                    	</div>
							@if ( isset($headerShop) )
	                    	{{ $announces->appends(array('shopID' => $headerShop->wy_shop_id))->links('admin.template.pagination.slider') }}
	                    	@else
	                    	{{ $announces->links('admin.template.pagination.slider') }}
	                    	@endif
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

@stop