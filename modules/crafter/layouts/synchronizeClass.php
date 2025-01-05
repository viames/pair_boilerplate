<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('CRAFTER') ?></h4>
			</div><?php
		
			if (count($this->unmappedTables)) {
			
				?><div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('TABLE_NAME') ?></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody><?php 
						
							foreach ($this->unmappedTables as $t) {
						
								?><tr>
									<td><?php print $t ?></td>
									<td><a href="crafter/classWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php $this->_('CREATE_CLASS') ?></a></td>
									<td><a href="crafter/moduleWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php $this->_('CREATE_MODULE') ?></a></td>
								</tr><?php 
								
							}
							
						?></tbody>
					</table>
				</div><?php
			
			} else {
				
				print Pair\Helpers\Utilities::printNoDataMessageBox();
				
			}
			
		?></div>
	</div>
</div>

