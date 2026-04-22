<?php

use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('MODULE_PLUGINS'); ?></h4>
			</div>
			<div class="card-body">
				<div style="overflow:hidden">
					<a href="modules/new" class="btn btn-primary btn-sm float-right"><?php BoilerplateLayout::printText('NEW_MODULE'); ?></a>
				</div>
				<hr>
				<?php if (count($state->modules)) { ?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php BoilerplateLayout::printText('NAME'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('COMPATIBLE'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('RELEASE_DATE'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('INSTALL_DATE'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('DOWNLOAD'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('DELETE'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($state->modules as $module) { ?>
							<tr>
								<td><?php print htmlspecialchars($module->name . ' v' . $module->version) ?></td>
								<td class="text-center"><?php print $module->compatible ?></td>
								<td class="text-center small"><?php print $module->formatDateTime('dateReleased') ?></td>
								<td class="text-center small"><?php print $module->formatDateTime('dateInstalled') ?></td>
								<td class="text-center"><?php print $module->downloadIcon ?></td>
								<td class="text-center"><?php print $module->deleteIcon ?></td>
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
