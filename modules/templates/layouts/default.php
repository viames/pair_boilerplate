<?php

use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div class="float-end">
					<a href="templates/new" class="btn btn-primary btn-sm"><?php BoilerplateLayout::printText('NEW_TEMPLATE'); ?></a>
				</div>
				<h4 class="card-title"><?php BoilerplateLayout::printText('TEMPLATE_PLUGINS'); ?></h4>
			</div>
			<div class="card-body">
				<?php if (count($state->templates)) { ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle">
						<thead>
							<tr>
								<th><?php BoilerplateLayout::printText('NAME'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('DEFAULT'); ?></th>
								<th class="d-none d-lg-table-cell text-end"><?php BoilerplateLayout::printText('COMPATIBLE'); ?></th>
								<th class="d-none d-lg-table-cell"><?php BoilerplateLayout::printText('PALETTE'); ?></th>
								<th class="d-none d-xl-table-cell text-end"><?php BoilerplateLayout::printText('RELEASE_DATE'); ?></th>
								<th class="d-none d-xl-table-cell text-end"><?php BoilerplateLayout::printText('INSTALL_DATE'); ?></th>
								<th class="text-center"><?php BoilerplateLayout::printText('DOWNLOAD'); ?></th>
								<?php if ($state->deletionAllowed) { ?>
								<th class="text-center"><?php BoilerplateLayout::printText('DELETE'); ?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($state->templates as $template) { ?>
							<tr>
								<td>
									<a href="<?php print htmlspecialchars($template->editUrl) ?>">
										<?php print htmlspecialchars($template->name . ' v' . $template->version) ?>
									</a>
								</td>
								<td class="text-center"><?php print $template->isDefaultIcon ?></td>
								<td class="d-none d-lg-table-cell text-end"><?php print $template->compatible ?></td>
								<td class="d-none d-lg-table-cell"><?php print $template->paletteSamples ?></td>
								<td class="d-none d-xl-table-cell text-end small"><?php print htmlspecialchars((string)$template->dateReleasedText) ?></td>
								<td class="d-none d-xl-table-cell text-end small"><?php print htmlspecialchars((string)$template->dateInstalledText) ?></td>
								<td class="text-center">
									<a href="<?php print htmlspecialchars($template->downloadUrl) ?>">
										<span class="fal fa-lg fa-download"></span>
									</a>
								</td>
								<?php if ($state->deletionAllowed) { ?>
								<td class="text-center">
									<a href="<?php print htmlspecialchars($template->deleteUrl) ?>" class="text-danger confirm-delete">
										<span class="fal fa-lg fa-trash"></span>
									</a>
								</td>
								<?php } ?>
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
	</div>
</div>
