<?php
declare(strict_types=1);

/** @var CrafterDefaultPageState $state */
?>
<div class="row g-3">
	<div class="col-12 col-lg-4">
		<div class="card h-100">
			<div class="card-header">
				<h4 class="card-title">Developer module</h4>
			</div>
			<div class="card-body d-flex flex-column">
				<p><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CREATE_CLASS_OR_MODULE_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></p>
				<div class="mt-auto">
					<a class="btn btn-primary btn-block" href="crafter/newClass">
						<?php print htmlspecialchars((string)Pair\Helpers\Translator::do('START'), ENT_QUOTES, 'UTF-8') ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card h-100">
			<div class="card-header">
				<h4 class="card-title">Test area</h4>
			</div>
			<div class="card-body d-flex flex-column">
				<p>Test area for launching experimental PHP code lines, with DB interaction.</p>
				<div class="mt-auto">
					<a class="btn btn-success btn-block" href="crafter/playground">
						Playground
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		<div class="card h-100">
			<div class="card-header">
				<h4 class="card-title">Componenti Bootstrap</h4>
			</div>
			<div class="card-body d-flex flex-column">
				<p>
					Pagina autonoma con esempi di tipografia, form, bottoni, badge, alert e tooltip usati come riferimento UI.
				</p>
				<div class="mt-auto">
					<a class="btn btn-info btn-block" href="crafter/bootstrapReference">
						Apri riferimento Bootstrap
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
