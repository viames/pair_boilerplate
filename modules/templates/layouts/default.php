<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div class="float-end">
					<a href="templates/new" class="btn btn-primary btn-sm"><?php $this->_('NEW_TEMPLATE') ?></a>
				</div>
				<h4 class="card-title"><?php $this->_('TEMPLATE_PLUGINS') ?></h4>
			</div>
			<div class="card-body">
				<?php if (count($this->templates)) { ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle">
						<thead>
							<tr>
								<th><?php $this->_('NAME') ?></th>
								<th class="text-center"><?php $this->_('DEFAULT') ?></th>
								<th class="d-none d-lg-table-cell text-end"><?php $this->_('COMPATIBLE') ?></th>
								<th class="d-none d-lg-table-cell"><?php $this->_('PALETTE') ?></th>
								<th class="d-none d-xl-table-cell text-end"><?php $this->_('RELEASE_DATE') ?></th>
								<th class="d-none d-xl-table-cell text-end"><?php $this->_('INSTALL_DATE') ?></th>
								<th class="text-center"><?php $this->_('DOWNLOAD') ?></th>
								<?php if ($this->deletionAllowed) { ?>
								<th class="text-center"><?php $this->_('DELETE') ?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->templates as $template) { ?>
							<tr>
								<td>
									<a href="<?php print htmlspecialchars($template->editUrl) ?>">
										<?php print htmlspecialchars($template->name . ' v' . $template->version) ?>
									</a>
								</td>
								<td class="text-center"><?php print $template->html('isDefault') ?></td>
								<td class="d-none d-lg-table-cell text-end"><?php print $template->compatible ?></td>
								<td class="d-none d-lg-table-cell"><?php print $template->paletteSamples ?></td>
								<td class="d-none d-xl-table-cell text-end small"><?php print $template->html('dateReleased') ?></td>
								<td class="d-none d-xl-table-cell text-end small"><?php print $template->html('dateInstalled') ?></td>
								<td class="text-center">
									<a href="<?php print htmlspecialchars($template->downloadUrl) ?>">
										<span class="fal fa-lg fa-download"></span>
									</a>
								</td>
								<?php if ($this->deletionAllowed) { ?>
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
				<?php $this->printPaginationBar();
				} else {
					$this->noData();
				} ?>
			</div>
		</div>
	</div>
</div>
