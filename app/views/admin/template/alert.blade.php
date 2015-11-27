@if ( Session::has('success') )
	<div class="alert alert-success alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ Session::get('success') }}</strong>
	</div>
@elseif ( !empty($errors->all()) )
	<div class="alert alert-danger alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong>
	    	@foreach ( $errors->all() as $error )
        		{{ $error }}
        	@endforeach
        </strong>
	</div>   
@elseif ( Session::has('error') )
	<div class="alert alert-danger alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ Session::get('error') }}</strong>
	</div>
@elseif ( Session::has('warning') )
	<div class="alert alert-warning alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ Session::get('warning') }}</strong>
	</div>
@elseif ( Session::has('info') )
	<div class="alert alert-info alert-dismissible" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <strong> {{ Session::get('info') }}</strong>
	</div>
@endif