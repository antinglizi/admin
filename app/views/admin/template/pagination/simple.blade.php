<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
	$trans = $environment->getTranslator();
?>

<ul class="pager">
	<span class="text">
		<b><?php echo $paginator->getCurrentPage(); ?></b>
		<span>/</span>
		<span>
			<?php echo (int) ceil($paginator->getTotal() / $paginator->getPerPage()) . '页'; ?>
		</span>
	</span>
	<span class="text">
		<?php echo '共<b>' . $paginator->getTotal() . '</b>项'; ?>
	</span>
	<span class="text">
		<?php echo '本页从<b>' . $paginator->getFrom() . '</b>项' . '到<b>' . $paginator->getTo() . '</b>项'; ?>
	</span>
	<?php
		echo $presenter->getPrevious($trans->trans('pagination.previous'));

		echo $presenter->getNext($trans->trans('pagination.next'));
	?>
</ul>
