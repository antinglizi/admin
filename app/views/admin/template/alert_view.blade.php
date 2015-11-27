@if ( isset($success) )
	<div class="alert alert-success alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ $success }}</strong>
	</div>  
@elseif ( isset($error) )
	<div class="alert alert-danger alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ $error }}</strong>
	</div>
@elseif ( isset($warning) )
	<div class="alert alert-warning alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ $warning }}</strong>
	</div>
@elseif ( isset($info) )
	<div class="alert alert-info alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ $info }}</strong>
	</div>
@endif