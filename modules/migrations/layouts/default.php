<div class="card">
	<div class="card-header">
		<h4 class="card-title"><?php $this->_('MIGRATIONS') ?></h4>
	</div>
	<div class="card-body">
		<div style="overflow:hidden">
			<a href="migrations/migrate" class="btn btn-primary btn-sm float-right modal-processing">Avvia migrazione</a>
		</div>
		<hr>
		<?php if (count($this->migrations)) { ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?php $this->sortable('FILE', 3, 4) ?></th>
						<th class="d-none d-lg-table-cell"><?php $this->_('QUERY_INDEX') ?></th>
						<th><?php $this->sortable('DESCRIPTION', 5, 6) ?></th>
						<th><?php $this->sortable('RESULT', 7, 8) ?></th>
						<th><?php $this->sortable('AFFECTED_ROWS', 9, 10) ?></th>
						<th><?php $this->sortable('EXECUTION_TIME', 11, 12) ?></th>
						<th class="d-none d-lg-table-cell"><?php $this->sortable('CREATED_AT', 1, 2) ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->migrations as $o) { ?>
					<tr>
						<td><?php $o->printHtml('file') ?></td>
						<td class="d-none d-lg-table-cell text-right"><?php $o->printHtml('queryIndex') ?></td>
						<td><?php $o->printHtml('description') ?></td>
						<td class="text-center"><?php $o->printHtml('result') ?></td>
						<td class="text-right"><?php $o->printHtml('affectedRows') ?></td>
						<td class="text-right"><?php $o->printHtml('executionTime') ?></td>
						<td class="d-none d-lg-table-cell text-right"><?php $o->printHtml('createdAt') ?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
		</div>
		<?php print $this->getPaginationBar();
				
		} else {
				
			Pair\Support\Utilities::printNoDataMessageBox();
				
		} ?>	
	</div>
</div>