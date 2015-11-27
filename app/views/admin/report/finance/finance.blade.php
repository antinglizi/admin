@extends('layouts.admin.admin', compact('headerShop'))

@section('title') @parent 财务报表  @stop

@section('beforeStyle')
    @parent
    {{ HTML::style('assets/lib/bootstrap-datetimepicker-2.3.4/css/bootstrap-datetimepicker.min.css') }}
@stop

@section('container')

<div id="content-right">
    <div class="container-fluid">
        <div id="alert_msg" class="row">
            <div class="col-lg-12">
                <h1>财务报表</h1>
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.index') }}">主页</a></li>
                    <li>统计报表</li>
                    <li class="active">财务报表</li>
                </ol>
            </div>
        </div>

        @include ('admin.template.alert')
        
		<div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-pills" role="tablist">
                    <li role="presentation" class="active"><a href="#finance_report" aria-controls="finance_repport" role="tab" data-toggle="pill">财务报表</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane panel panel-default fade in active finance-data" id="finance_report">
                        <div class="panel-body">
                            <div class="report-form">
                                <form class="form-inline">
                                    @if ( isset($headerShop) )
                                        <input type="hidden" id="shop_id" name="shop_id" value="{{ $headerShop->wy_shop_id }}">
                                    @endif
                                    <div class="form-group">
                                        <label class="w80">财务日期</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control" id="start_date" name="start_date" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="txt">至</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control" id="end_date" name="end_date" readonly>
                                        </div>
                                    </div>
                                    <button id="generate_financedata" class="btn btn-success bg-green ml-20">生 成</button>
                                </form>
                            </div>
                            <div class="report-wrap">
                                <div class="chart-title">
                                    <h3>财务数据趋势图</h3>
                                    <h5></h5>
                                    <div class="sum">
                                        <strong>订单总数：</strong>
                                        <span id="amount_sum">0</span>
                                        <span class="unit">笔</span>
                                        <strong>消费金额：</strong>
                                        <span id="consume_money_sum">0</span>
                                        <span class="unit">元</span>
                                         <strong>实际金额：</strong>
                                        <span id="actual_money_sum">0</span>
                                        <span class="unit">元</span>
                                    </div>
                                </div>
                                <div id="finance_data_chart" class="chart-container"></div>
                            </div>
                            <div id="orderreport_container">
                                <?php
                                    $paginator = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
                                    echo $paginator->links('admin.template.pagination.simple');
                                ?>
                                <table id="orderreport_list_table" class="table table-bordered">
                                    <colgroup>
                                        <col class="w150"></col>
                                        <col class="w150"></col>
                                        <col class="w150"></col>
                                        <col class="w150"></col>
                                        <col class="w100"></col>
                                        <col class="w150"></col>
                                    </colgroup>
                                    <thead>
                                        <tr class="col-name">
                                            <th>订单号</th>
                                            <th>订单时间</th>
                                            <th>消费金额</th>
                                            <th>实际金额</th>
                                            <th>订单状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                </table>
                                <?php
                                    $paginator = Paginator::make(array(), DEFAULT_0, PERPAGE_COUNT_10);
                                    echo $paginator->links('admin.template.pagination.slider');
                                ?>
                            </div>
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
    {{ HTML::script('assets/js/vendor/moment-2.10.3.min.js') }}
    {{ HTML::script('assets/lib/bootstrap-datetimepicker-2.3.4/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('assets/lib/bootstrap-datetimepicker-2.3.4/js/bootstrap-datetimepicker.zh-CN.js') }}
    {{ HTML::script('assets/lib/echarts-2.2.3/echarts.js') }}
    {{ HTML::script('assets/js/report.js') }}
    <script type="text/javascript">
        $(document).ready(function() {
            Report.initFinance();
        });
    </script>
@stop