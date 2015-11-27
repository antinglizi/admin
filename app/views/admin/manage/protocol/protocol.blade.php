@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 协议签署  @stop

@section('container')

<div id="content-right">
	<div class="container-fluid">
		<div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>协议签署</h1>
                <ol class="breadcrumb">
					<li><a href="{{ route('admin.index') }}">主页</a></li>
					<li>管理</li>
					<li class="active">协议签署</li>
				</ol>
            </div>
        </div>

		@include ('admin.template.alert')

        <div class="row">
	        <div class="col-lg-12">
	        	<div class="panel panel-default">
	        		<div class="panel-heading">
                        <h3 class="panel-title">协议信息</h3>
                    </div>
                    <div class="panel-body">
                    	<table id="protocol_list_table" class="table table-bordered">
		                	<colgroup>
		                		<col class="w50"></col>
		                		<col class="w200"></col>
		                		<col class=""></col>
		                		<col class="w100"></col>
		                		<col class="w150"></col>
		                	</colgroup>
		                	<thead>
		                		<tr class="col-name">
		                			<th>序号</th>
		                			<th>协议名称</th>
		                			<th>协议简介</th>
		                			<th>协议状态</th>
		                			<th>操作</th>
		                		</tr>
		                	</thead>
		                	<tbody class="text-center" style="white-space:normal">
		                		<tr>
		                			<td>1</td>
		                			<td>文蚁用户注册协议</td>
		                			<td style="text-align:left">旨在明确商户的基本权利、义务以及责任，商户签署后应切实遵守本协议、合法合规诚实守信经营，如违反本协议的文蚁净菜有权采取一切必要处罚措施。</td>
		                			<td>
										<span class="label label-warning">已签约</span>
		                			</td>
		                			<td>
										@if ( isset($headerShop) )
										<a class="btn btn-success btn-xs bg-green" href="{{ route('protocol.list.info', array("shopID" => $headerShop->wy_shop_id)) }}" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">查看协议</span></span></a>
										@else
										<a class="btn btn-success btn-xs bg-green" href="{{ route('protocol.list.info') }}" target="_blank"><span class="fa fa-search-plus"><span class="pl-5">查看协议</span></span></a>
										@endif
		                			</td>
		                		</tr>
		                	</tbody>
		                </table>
                    </div>
	            </div>
	        </div>
    	</div>
    </div>
</div>

@stop
