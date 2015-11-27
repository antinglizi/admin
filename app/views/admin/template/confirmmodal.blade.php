<div id="{{ $modalID }}" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
	    <div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h4 class="modal-title">{{ $modalTitle }}</h4>
		    </div>
		    <div class="modal-body text-center">
		    	<p>你确定要删除<mark class="bg-danger"></mark>吗？</p>
		    	<p>删除店铺将无法恢复</p>
		    </div>
		    <div class="modal-footer">
		    	<div class="center-block">
		    		<button id="delete_shop" type="button" class="btn btn-primary" data-userid="" data-shopid="">确定</button>
			    	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
		    	</div>
		    </div>
	    </div>
  	</div>
</div>