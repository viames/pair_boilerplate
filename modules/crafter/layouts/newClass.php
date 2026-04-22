<?php

use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('CRAFTER'); ?></h4>
			</div><?php
		
			if (count($state->unmappedTables)) {
			
				?><div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php BoilerplateLayout::printText('TABLE_NAME'); ?></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody><?php 
						
							foreach ($state->unmappedTables as $t) {
						
								?><tr>
									<td><?php print $t ?></td>
									<td><a href="crafter/newClassWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php BoilerplateLayout::printText('CREATE_CLASS'); ?></a></td>
									<td><a href="crafter/newModuleWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php BoilerplateLayout::printText('CREATE_MODULE'); ?></a></td>
								</tr><?php 
								
							}
							
						?></tbody>
					</table>
				</div><?php
			
			} else {
				
				Utilities::showNoDataAlert();
				
			}
			
		?></div>
	</div>
</div>
