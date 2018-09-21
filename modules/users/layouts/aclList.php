<?php

use Pair\Utilities;

?><div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left"><?php $this->_('ACCESS_LIST_OF_GROUP', $this->group->name) ?></h5><?php
           	
			// show button if acl is not full
			if ($this->missingAcl) {
				?>
				<a class="btn btn-primary btn-sm float-right" href="users/aclNew/<?php print $this->group->id ?>"><button type="button"><i class="fa fa-plus-circle"></i> <?php $this->_('ADD') ?></button></a><?php
			}
			
			?>
			</div>
			<div class="card-body">
				<div class="table-responsive"><?php

			if (count($this->acl)) {

					?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('MODULE_NAME') ?></th>
								<th><?php $this->_('MODULE_ACTION') ?></th>
								<th><?php $this->_('DELETE') ?></th>
							</tr>
						</thead>
						<tbody><?php
		
				foreach ($this->acl as $item) { ?>
							<tr>
								<td class="lft"><?php print ucfirst($item->moduleName) ?></td>
								<td class="text-center"><?php print $item->action ? ucfirst($item->action) : 'full access' ?></td>
								<td class="text-center"><?php
								// avoid deletion of default ACL
								if (!$item->default) {
									?><a class="btn btn-secondary btn-sm" href="users/aclDelete/<?php print $item->id ?>"><i class="fa fa-times"></i></a><?php
								} else {
									?><i class="fa fa-times disabled"></i><?php ;
								}
								?>
								</td>
							</tr><?php
				} ?>
					</tbody>
				</table><?php

			} else {

				Utilities::printNoDataMessageBox();

			}

				?></div>
			</div>
		</div>
	</div>
</div>