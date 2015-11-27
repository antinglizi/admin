@if ( isset($error) )
	<option> {{ $error }}</option>
@else
	@foreach ( $shopRegions as $index => $shopRegion )
		<option value="{{ $shopRegion->wy_region_id }}" data-parentid="{{ $shopRegion->wy_region_parentid }}" data-shortname="{{ $shopRegion->wy_region_shortname }}">{{ $shopRegion->wy_region_name }}</option>
	@endforeach
@endif