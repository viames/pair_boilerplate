<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Utilities;

?>
<div class="col-lg-12">
	<div class="ibox">
    		<div class="ibox-title">
	            	<h5><?php $this->_('RULES') ?></h5>
			<div class="ibox-tools">
				<a class="btn btn-primary btn-xs" href="users/groupNew"><i class="fa fa-plus-circle"></i> <?php $this->_('NEW_RULE') ?></a>
			</div>
		</div>
		<div class="ibox-content"><?php
		
		if (count($this->rules)) {
			
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="text-center"><?php $this->_('MODULE') ?></th>
							<th class="text-center"><?php $this->_('ACTION') ?></th>
							<th class="text-center"><?php $this->_('ADMIN_ONLY') ?></th>
							<th class="text-center"><?php $this->_('EDIT') ?></th>
						</tr>
					</thead>
					<tbody><?php
 
					foreach ($this->rules as $o) {
						
						?>
						<tr>
							<td><?php print htmlspecialchars($o->name) ?></td>
							<td class="text-center"><?php print htmlspecialchars($o->action) ?></td>
							<td class="text-center"><?php print $o->adminIcon ?></td>
							<td class="text-center"><a href="rules/edit/<?php print $o->id ?>"><i class="fa fa-pencil fa-lg"></i></a></td>
						</tr><?php 
				
			}
			
					?>
					</tbody>
				</table>
			</div><?php
		
			print $this->getPaginationBar();
		
		} else {

			Utilities::printNoDataMessageBox();

		}
		
		?>
		</div>
	</div>
</div>