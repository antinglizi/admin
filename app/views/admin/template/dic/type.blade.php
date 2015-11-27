@if ( isset($error) )
	<option>{{ $error }}</option>
@else
	@foreach ( $types as $index => $type )
		@if ( isset($typeValue) )
			@if ( $typeValue == $type->wy_dic_item_id )
				<option value="{{ $type->wy_dic_item_id }}" selected>{{ $type->wy_dic_value }}</option>
			@else
				<option value="{{ $type->wy_dic_item_id }}">{{ $type->wy_dic_value }}</option>
			@endif
		@else
			<option value="{{ $type->wy_dic_item_id }}">{{ $type->wy_dic_value }}</option>
		@endif
	@endforeach
@endif