<div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('COUNTRIES') ?></h4>
			</div>
			<div class="panel-body">
				<a href="countries/new" class="btn btn-primary"><i class="fa fa-plus"></i> <?php $this->_('NEW_COUNTRY') ?></a>
				<hr><?php
	
				if (count($this->countries)) {
	
					?><div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
								<th><?php $this->_('ENGLISH_NAME') ?></th>
								<th><?php $this->_('NATIVE_NAME') ?></th>
								<th><?php $this->_('CODE') ?></th>
								</tr>
							</thead>
							<tbody><?php
							
							foreach ($this->countries as $o) {
								?>
								<tr>
									<td><a href="countries/edit/<?php print $o->id ?>"><?php print htmlspecialchars($o->englishName) ?></a></td>
									<td><?php print htmlspecialchars($o->nativeName) ?></td>
									<td><?php print htmlspecialchars($o->code) ?></td>
								</tr><?php
							} ?>
							</tbody>
						</table>
					</div><?php
	
					print $this->getPaginationBar();
						
				} else {
						
					Pair\Utilities::printNoDataMessageBox();
						
				}
			
			?></div>
		</div>
	</div>
</div>