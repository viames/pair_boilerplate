<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('CRAFTER') ?></h4>
			</div>
			<div class="card-body">
				<form action="crafter/moduleCreation" method="post" class="form-horizontal">
					<?php $this->form->printControl('tableName') ?>
					<fieldset>
						<legend><?php $this->_('NEW_MODULE_OPTIONS') ?></legend>
						<div class="form-group row">
							<div class="col-md-3"><?php $this->form->printLabel('objectName')?></div>
							<div class="col-md-3"><?php $this->form->printControl('objectName') ?></div>
							<div class="col-md-6 small"><?php $this->_('OBJECT_NAME_DESCRIPTION')?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $this->form->printLabel('moduleName')?></div>
							<div class="col-md-3"><?php $this->form->printControl('moduleName') ?></div>
							<div class="col-md-6 small"><?php $this->_('MODULE_NAME_DESCRIPTION')?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $this->form->printLabel('commonClass')?></div>
							<div class="col-md-3"><?php $this->form->printControl('commonClass') ?></div>
							<div class="col-md-6 small"><?php $this->_('COMMON_CLASS_DESCRIPTION')?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $this->form->printLabel('migration')?></div>
							<div class="col-md-3"><?php $this->form->printControl('migration') ?></div>
							<div class="col-md-6 small"><?php $this->_('MIGRATION_DESCRIPTION')?></div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php $this->_('GROUPS_ACL') ?></legend>
						<?php foreach ($this->groups as $group) { ?>
						<div class="form-group row">
							<div class="col-md-3"><?php $this->form->printLabel('group' . $group->id) ?></div>
							<div class="col-md-3"><?php $this->form->printControl('group' . $group->id) ?></div>
							<div class="col-md-6 small"><?php $this->printGroupDescription($group) ?></div>
						</div>
						<?php } ?>
					</fieldset>
					<fieldset>
						<div class="form-group row">
							<div class="col-md-push-3 col-md-9">
								<button type="submit" class="btn btn-primary"><?php $this->_('CREATE_MODULE') ?></button>
								<a href="crafter" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>