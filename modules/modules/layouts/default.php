<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

use Pair\Utilities;

if (count($this->modules)) {
	
	?><div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left">Plugin moduli</h5>
				<a class="btn btn-primary btn-sm float-right" href="modules/new"><i class="fa fa-plus-circle"></i> Nuovo modulo</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->_('NAME') ?></th>
								<th>Compatibile</th>
								<th><?php $this->_('RELEASE_DATE') ?></th>
								<th><?php $this->_('INSTALL_DATE') ?></th>
								<th><?php $this->_('DOWNLOAD') ?></th>
								<th><?php $this->_('DELETE') ?></th>
							</tr>
						</thead>
						<tbody><?php
		
						foreach ($this->modules as $module) {
					
							?><tr>
								<td><?php print htmlspecialchars($module->name . ' v' . $module->version) ?></td>
								<td class="text-center"><?php print $module->compatible ?></td>
								<td class="small text-center"><?php print $module->dateReleased->format($this->lang('DATE_FORMAT')) ?></td>
								<td class="small text-center"><?php print $module->dateInstalled->format($this->lang('DATE_FORMAT')) ?></td>
								<td class="text-center"><?php print $module->downloadIcon ?></td>
								<td class="text-center"><?php print $module->deleteIcon ?></td>
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
