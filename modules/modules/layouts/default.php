<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print $this->_('MODULE_PLUGINS') ?></h4>
			</div>
			<div class="card-body">
				<div style="overflow:hidden">
					<a href="modules/new" class="btn btn-primary btn-sm float-right"><?php $this->_('NEW_MODULE') ?></a>
				</div>
				<hr><?php
			
			if (count($this->modules)) {
			
				?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('NAME') ?></th>
								<th><?php $this->_('COMPATIBLE') ?></th>
								<th><?php $this->_('RELEASE_DATE') ?></th>
								<th><?php $this->_('INSTALL_DATE') ?></th>
								<th><?php $this->_('DOWNLOAD') ?></th>
								<th><?php $this->_('DELETE') ?></th>
							</tr>
						</thead>
						<tbody><?php
		
						foreach ($this->modules as $module) {
					
							?>
							<tr>
								<td><?php print htmlspecialchars($module->name . ' v' . $module->version) ?></td>
								<td class="text-center"><?php print $module->compatible ?></td>
								<td class="text-center small"><?php print $module->formatDateTime('dateReleased', $this->lang('DATE_FORMAT')) ?></td>
								<td class="text-center small"><?php print $module->formatDateTime('dateInstalled', $this->lang('DATE_FORMAT')) ?></td>
								<td class="text-center"><?php print $module->downloadIcon ?></td>
								<td class="text-center"><?php print $module->deleteIcon ?></td>
							</tr><?php 
							
						}
		
						?>
						</tbody>
					</table>
				</div><?php

				print $this->getPaginationBar();
				
			} else {
				
				Pair\Helpers\Utilities::printNoDataMessageBox();
				
			}
			
			?>
			</div>
		</div>
	</div>
</div>