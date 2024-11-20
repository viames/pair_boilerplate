<div class="card">
	<div class="card-header">
		<h4 class="card-title"><?php $this->_('OAUTH2CLIENTS') ?></h4>
	</div>
	<div class="card-body">
		<div style="overflow:hidden">
			<a href="oauth2clients/new" class="btn btn-primary btn-sm float-end"><?php $this->_('NEW_OAUTH2CLIENT') ?></a>
		</div>
		<hr>
		<?php if (count($this->oauth2Clients)) { ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?php $this->sortable('ID', 1, 2) ?></th>
						<th><?php $this->sortable('SECRET', 3, 4) ?></th>
						<th><?php $this->sortable('ENABLED', 5, 6) ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->oauth2Clients as $o) { ?>
					<tr>
						<td><a href="oauth2clients/edit/<?php print $o->id ?>"><?php $o->printHtml('id') ?></a></td>
						<td class="small text-muted"><?php $o->printHtml('secret') ?></td>
						<td class="text-center"><?php $o->printHtml('enabled') ?></td>
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