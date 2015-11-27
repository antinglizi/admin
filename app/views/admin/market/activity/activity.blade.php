@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 活动报名  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>活动报名</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>营销中心</li>
					<li class="active">活动报名</li>
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
	                        <h3 class="panel-title">活动信息</h3>
	                    </div>
	                    <div class="panel-body">
	                    </div>
						<table class="table table-bordered">
		                	<colgroup>
		                		<col class="w50"></col>
		                		<col class="w150"></col>
		                		<col class="w450"></col>
		                		<col class="w100"></col>
		                		<col class="w100"></col>
		                		<col class="w100"></col>
		                		<col class=""></col>
		                	</colgroup>
		                	<thead>
		                		<tr class="col-name">
		                			<th>序号</th>
		                			<th>活动名称</th>
		                			<th>活动介绍</th>
		                			<th>开始日期</th>
		                			<th>结束日期</th>
		                			<th>状态</th>
		                			<th>操作</th>
		                		</tr>
		                	</thead>
			            </table>   	
	                    <div class="scroll">
	                    	<table id="activity_list_table" class="table table-bordered">
			                	<colgroup>
			                		<col class="w50"></col>
			                		<col class="w150"></col>
			                		<col class="w450"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class="w100"></col>
			                		<col class=""></col>
			                	</colgroup>
			                	@foreach ( $activities as $index => $activity )
			                	<tbody data-shopid="{{ $headerShop->wy_shop_id }}" data-activityid="{{ $activity->wy_activity_id }}" data-activityname="{{ $activity->wy_activity_name }}">
			                		<tr class="text-center">
										<td>{{ $index+1 }}</td>
										<td>{{ $activity->wy_activity_name }}</td>
										<td>{{ $activity->wy_brief }}</td>
										<td>{{ $activity->wy_activity_start }}</td>
										<td>{{ $activity->wy_activity_end }}</td>
										<td>
											<span class="label label-warning">{{ $activity->wy_activity_status_name }}</span>
										</td>
										<td>
											@if ( 0 == $activity->wy_activity_status )
					        				<div>
					        					<button id="participate_activity" class="btn btn-success btn-sm bg-green">
												<span class="fa fa-plus-circle"><span class="pl-5">报名</span></span></button>
					        				</div>
											@else
											<div>
												<button id="cancel_activity" class="btn btn-danger btn-sm"><span class="fa fa-minus-circle"><span class="pl-5">取消</span></span></button>
											</div>
											@endif
										</td>
									</tr>
			                	</tbody>
			                	@endforeach
			                </table>
	                    </div>
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
	@endif
</div>

@stop

@section('afterScript')
    @parent
    {{ HTML::script('assets/js/market.js') }}
    @if ( isset($headerShop) && !isset($all) )
    <script type="text/javascript">
        $(document).ready(function() {
            Market.initActivity();
        });
    </script>
    @endif
@stop