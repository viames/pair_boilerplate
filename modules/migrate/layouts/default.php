<?php

$pendingSources = array_filter($this->pendingMigrationFiles, fn (array $files): bool => count($files) > 0);

if (count($pendingSources)) { ?>
<div class="card mb-4">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h4 class="card-title m-0"><?php $this->_('PENDING_MIGRATIONS') ?></h4>
		<a class="btn btn-sm btn-outline-primary" href="migrate/run">
			<i class="fal fa-play fa-fw"></i> <?php $this->_('RUN_ALL_MIGRATIONS') ?>
		</a>
	</div>
	<div class="card-body">
		<?php foreach ($pendingSources as $source => $files) { ?>
		<div class="<?php print 'app' === $source ? '' : 'mb-4' ?>">
			<h5 class="h6 mb-3"><?php print htmlspecialchars($this->sourceLabel((string)$source)) ?></h5>
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead>
						<tr>
							<th><?php $this->_('FILE') ?></th>
							<th class="text-end"><?php $this->_('ACTION') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($files as $file) { ?>
						<tr>
							<td><i class="fal fa-file-code fa-fw text-muted me-1"></i><?php print htmlspecialchars((string)$file) ?></td>
							<td class="text-end">
								<a class="btn btn-sm btn-outline-primary" href="<?php print htmlspecialchars($this->migrationRunFileUrl((string)$source, (string)$file)) ?>">
									<i class="fal fa-play fa-fw"></i> <?php $this->_('RUN_PENDING_MIGRATION') ?>
								</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>

<div class="card">
	<div class="card-header">
		<h4 class="card-title m-0"><?php $this->_('MIGRATIONS') ?></h4>
	</div>
	<div class="card-body">
		<?php if (count($this->migrations)) { ?>
		<div class="table-responsive">
			<table class="table table-hover align-middle">
				<thead>
					<tr>
						<th><?php $this->_('SOURCE') ?></th>
						<th><?php $this->sortable('DESCRIPTION', 5, 6) ?></th>
						<th class="text-center"><?php $this->sortable('RESULT', 7, 8) ?></th>
						<th class="d-none d-md-table-cell text-end"><?php $this->sortable('CREATED_AT', 1, 2) ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->groupedMigrations as $source => $filesBySource) {
						if (!count($filesBySource)) {
							continue;
						}
						foreach ($filesBySource as $file => $migrations) { ?>
						<tr>
							<td colspan="4" class="bg-body-tertiary fw-bold">
								<span class="badge text-bg-light border me-2"><?php print htmlspecialchars($this->sourceLabel((string)$source)) ?></span>
								<i class="fal fa-file-code fa-fw text-muted me-1"></i><?php print htmlspecialchars((string)$file) ?>
							</td>
						</tr>
						<?php foreach ($migrations as $migration) { ?>
						<tr>
							<td class="small text-muted"><?php print htmlspecialchars((string)$source) ?></td>
							<td class="ps-4">
								<?php if ($migration->description) { ?>
								<span class="text-muted"><?php $migration->printHtml('description') ?></span>
								<?php } else { ?>
								<i class="text-muted small"><?php $this->_('NO_DESCRIPTION') ?></i>
								<?php } ?>
							</td>
							<td class="text-center">
								<i class="fal fa-lg fa-<?php print $migration->result ? 'check text-success' : 'times text-danger' ?>"></i>
							</td>
							<td class="d-none d-md-table-cell text-end text-muted small"><?php $migration->printHtml('createdAt') ?></td>
						</tr>
						<?php } } } ?>
				</tbody>
			</table>
		</div>
		<?php $this->printPaginationBar();
		} else {
			$this->noData($this->lang('NO_MIGRATIONS_EXECUTED'));
		} ?>
	</div>
</div>
