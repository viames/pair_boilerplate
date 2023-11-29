<?php

use Pair\Utilities;

?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left"><?php $this->_('RULES') ?></h5>
				<a class="btn btn-primary btn-sm float-right" href="rules/new"><i class="fa fa-plus-circle"></i> <?php $this->_('NEW_RULE') ?></a>
			</div>
			<div class="card-body"><?php
		
		if (count($this->rules)) {
			
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="text-center"><?php $this->_('MODULE') ?></th>
							<th class="text-center"><?php $this->_('ACTION') ?></th>
							<th class="text-center"><?php $this->_('ADMIN_ONLY') ?></th>
						</tr>
					</thead>
					<tbody><?php
 
					foreach ($this->rules as $o) {
						
						?>
						<tr>
							<td><a href="rules/edit/<?php print $o->id ?>"><?php print htmlspecialchars($o->name) ?></a></td>
							<td class="text-center"><?php print htmlspecialchars((string)$o->action) ?></td>
							<td class="text-center"><?php print $o->adminIcon ?></td>
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
</div>