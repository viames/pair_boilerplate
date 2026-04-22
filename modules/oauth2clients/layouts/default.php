<?php

use Pair\Helpers\Utilities;

?>
<div class="card">
	<div class="card-header">
		<div class="float-end">
			<a class="p-1 btn btn-sm btn-outline-primary mt-1 float-right" href="oauth2clients/new"><i class="fal fa-plus-large fa-fw"></i></a>
		</div>
		<h4 class="card-title"><?php BoilerplateLayout::printText('OAUTH2CLIENTS'); ?></h4>
	</div>
	<div class="card-body">
		<?php if (count($state->oauth2Clients)) { ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?php BoilerplateLayout::printSortable('ID', 1, 2); ?></th>
						<th><?php BoilerplateLayout::printSortable('SECRET', 3, 4); ?></th>
						<th><?php BoilerplateLayout::printSortable('ENABLED', 5, 6); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($state->oauth2Clients as $o) { ?>
					<tr>
						<td><a href="oauth2clients/edit/<?php print $o->id ?>"><?php $o->printHtml('id') ?></a></td>
						<td class="small text-muted"><?php $o->printHtml('secret') ?></td>
						<td class="text-center"><?php $o->printHtml('enabled') ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php print $state->paginationBar;
			
	} else {
			
		Utilities::showNoDataAlert();
			
	} ?>
	</div>
</div>
