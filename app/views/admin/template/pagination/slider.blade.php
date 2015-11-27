<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<ul class="pagination">
	<?php echo $presenter->render(); ?>
	<span class="text">
		<?php echo '共<b>' . (int) ceil($paginator->getTotal() / $paginator->getPerPage()) . '</b>页'; ?>
	</span>
</ul>
