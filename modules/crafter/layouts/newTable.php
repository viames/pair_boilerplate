<?php

use Pair\Helpers\Utilities;

?><div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('CRAFTER') ?></h4>
			</div><?php
		
			if (count($this->unmappedClasses)) {
			
				?><div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('TABLE_NAME') ?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody><?php 
						
							foreach ($this->unmappedClasses as $t) {
						
								?><tr>
									<td><?php print $t ?></td>
									<td><a href="crafter/createTable/<?php print $t ?>" class="btn btn-primary btn-sm"><?php $this->_('CREATE_TABLE') ?></a></td>
								</tr><?php 
								
							}
							
						?></tbody>
					</table>
				</div><?php
			
			} else {
				
				print Utilities::printNoDataMessageBox();
				
			}
			
		?></div>
	</div>
</div>

