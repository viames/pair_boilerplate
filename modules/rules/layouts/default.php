<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('RULES') ?></h4>
			</div>
			<div class="card-body">
				<div style="overflow:hidden">
					<a href="rules/new" class="btn btn-primary btn-sm float-right"><?php $this->_('NEW_RULE') ?></a>
				</div>
				<hr>
				<?php
		
				if (count($this->rules)) {
	
				?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('MODULE') ?></th>
								<th><?php $this->_('ACTION') ?></th>
								<th><?php $this->_('ADMIN_ONLY') ?></th>
							</tr>
						</thead>
						<tbody><?php
						
						foreach ($this->rules as $o) {
					
							?><tr>
								<td><a href="rules/edit/<?php print $o->id ?>"><?php print $o->name ?></a></td>
								<td class="text-center"><?php print $o->action ?></td>
								<td class="text-center"><?php print $o->adminIcon ?></td>
							</tr><?php 
							
						}
						
						?></tbody>
					</table>
				</div><?php
					
					print $this->getPaginationBar();
					
				} else {
				
					Pair\Support\Utilities::printNoDataMessageBox();
				
				}
		
			?>
			</div>
		</div>
	</div>
</div>