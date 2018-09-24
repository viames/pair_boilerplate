<div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('DEVELOPER') ?></h4>
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
									<td><a href="developer/newClassWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><i class="fa fa-magic"></i> <?php $this->_('CREATE_CLASS') ?></a></td>
									<td><a href="developer/newModuleWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><i class="fa fa-magic"></i> <?php $this->_('CREATE_MODULE') ?></a></td>
								</tr><?php 
								
							}
							
						?></tbody>
					</table>
				</div><?php
			
			} else {
				
				print Pair\Utilities::printNoDataMessageBox();
				
			}
			
		?></div>
	</div>
</div>

