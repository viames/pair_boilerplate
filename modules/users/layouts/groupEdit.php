<div class="ibox float-e-margins">
	<div class="card-header">
		<h5><?php $this->_('GROUP_EDIT') ?> "<?php print $this->group->name ?>"</h5>
	</div>
	<div class="card-body">
		<form action="users/groupChange" method="post" class="form-horizontal">
			<fieldset>

				<?php $this->form->printControl('id') ?>

				<div class="form-group row">
					<label class="col-sm-2"><?php $this->_('NAME') ?></label>
					<div class="col-sm-10"><?php $this->form->printControl('name') ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php $this->_('IS_DEFAULT') ?></label>
					<div class="col-sm-10"><?php $this->form->printControl('default') ?></div>
				</div><?php
		
			if ($this->group->modules) { 

				?><div class="form-group row">
					<label class="col-sm-2"><?php $this->_('DEFAULT_MODULE') ?></label>
					<div class="col-sm-10"><?php $this->form->printControl('defaultAclId') ?></div>
				</div><?php
		
			}
		
			?><div class="buttonBar">
				<button type="submit" class="btn btn-primary" value="edit" name="action"><i class="fa fa-save"></i> <?php $this->_('CHANGE') ?></button>
				<a href="groups" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a><?php
	
				if ($this->group->isDeletable()) {
					?><a href="users/groupDelete/<?php print $this->group->id ?>" class="btn btn-link confirm-delete float-right"><i class="fa fa-trash"></i> <?php $this->_('DELETE') ?></a><?php				
				}
	
			?></div>
			</fieldset>
		</form>
	</div>
</div>