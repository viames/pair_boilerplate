<?php

use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('RULES'); ?></h4>
			</div>
			<div class="card-body">
				<div style="overflow:hidden">
					<a href="rules/new" class="btn btn-primary btn-sm float-right"><?php BoilerplateLayout::printText('NEW_RULE'); ?></a>
				</div>
				<hr>
				<?php if (count($state->rules)) { ?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php BoilerplateLayout::printText('MODULE'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('ACTION'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('ADMIN_ONLY'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($state->rules as $o) { ?>
							<tr>
								<td><a href="rules/edit/<?php print $o->id ?>"><?php print $o->name ?></a></td>
								<td class="text-center"><?php print htmlspecialchars((string)$o->action) ?></td>
								<td class="text-center"><?php print $o->adminIcon ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<?php print $state->paginationBar; ?>
				<?php } else { ?>
					<?php Utilities::showNoDataAlert(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
