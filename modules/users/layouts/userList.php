<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Utilities;

if (count($this->users)) {

	?><div class="col-lg-12">
		<div class="card">
	    	<div class="card-header">
            	<h5 class="float-left"><?php print $this->_('USER_LIST') ?></h5>
				<a class="btn btn-primary btn-sm float-right" href="users/userNew"><i class="fa fa-plus-circle"></i> Nuovo utente</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('NAME') ?></th>
								<th><?php $this->_('USERNAME') ?></th>
								<th><?php $this->_('EMAIL') ?></th>
								<th><?php $this->_('GROUP') ?></th>
								<th><?php $this->_('ENABLED') ?></th>
								<th><?php $this->_('LAST_LOGIN') ?></th>
							</tr>
						</thead>
						<tbody><?php
		
						foreach ($this->users as $user) {
	
							?><tr>
								<td><a href="users/userEdit/<?php print $user->id ?>"><?php print htmlspecialchars($user->fullName) ?></a></td>
								<td><?php print $user->username ?></td>
								<td class="cnt"><?php print $user->email ?></td>
								<td class="small cnt"><?php print $user->groupName ?></td>
								<td class="text-center"><?php print $user->enabledIcon ?></td>
								<td class="small text-center"><?php print Utilities::getTimeago($user->lastLogin) ?></td>
							</tr><?php
			
						}
		
					?></tbody>
					</table>
				</div>
			</div>
		</div>
	</div><?php

	print $this->getPaginationBar();

} else {

	Utilities::printNoDataMessageBox();

}