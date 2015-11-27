<div id="goods_changestatus_modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
	    <div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h4 class="modal-title">{{ $modalTitle }}</h4>
		    </div>
		    <div class="modal-body text-center">
		    	<p>你确定要<mark class="bg-danger"></mark>菜品{{ $modalBody }}吗？</p>
		    </div>
		    <div class="modal-footer">
		    	<div class="center-block">
		    		<button id="change_goods" type="button" class="btn btn-primary" data-shopid="" data-goodsid="" data-goodsstatus="">确定</button>
			    	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
		    	</div>
		    </div>
	    </div>
  	</div>
</div>