<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Utilities;

?><div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('DEVELOPER') ?></h4>
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
									<td><a href="developer/createTable/<?php print $t ?>" class="btn btn-primary btn-sm"><i class="fa fa-database"></i> <?php $this->_('CREATE_TABLE') ?></a></td>
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

