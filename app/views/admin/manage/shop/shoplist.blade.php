<li><a href="#" data-shopname="" data-shopid="">全部店铺</a>
</li>
<li class="divider"></li>
@foreach ( $shops as $index => $shop )
<li><a href="#" data-shopname="{{ $shop->wy_shop_name }}" data-shopid="{{ $shop->wy_shop_id }}">{{ $shop->wy_shop_name }}</a>
</li>
<li class="divider"></li>
@endforeach