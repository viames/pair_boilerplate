<div class="col-lg-12">
	<div class="card">
		<div class="card-header">
			<h5 class="float-left">Aggiungi ACL al gruppo <?php print htmlspecialchars($this->group->name) ?></h5>
		</div>
		<div class="card-body">
			<div class="table-responsive"><?php

if (count($this->rules)) {
		
		?><form action="users/aclAdd" method="post">
	
		<?php $this->form->printControl('groupId') ?>
	
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width:100px">
						<div class="selectAllRows"><?php $this->_('SELECT_ALL') ?></div>
						<div class="deselectAllRows hidden"><?php $this->_('DESELECT_ALL') ?></div>
					</th>
					<th><?php $this->_('MODULE_NAME') ?></th>
					<th><?php $this->_('MODULE_ACTION') ?></th>
				</tr>
			</thead>
			<tbody><?php
		
			foreach ($this->rules as $rule) { ?>
	
				<tr>
					<td class="cnt"><input type="checkbox" value="<?php print $rule->id ?>" name="aclChecked[]" /></td>
					<td class="lft"><?php print $rule->moduleName ?></td>
					<td class="cnt"><?php print $rule->action ?></td>
				</tr><?php
				
			}
		
				?></tbody>
		</table>
		<div class="buttonBar">
			<button type="submit" class="btn btn-primary" value="addAcl" name="action"><i class="fa fa-asterisk"></i> <?php $this->_('ADD') ?></button>
			<a href="users/aclList/<?php print $this->group->id ?>" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
		</div>
	</form><?php

} else {

	Pair\Helpers\Utilities::printNoDataMessageBox();

}

			?></div>
		</div>
	</div>
</div>
