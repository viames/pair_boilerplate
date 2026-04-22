<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('CRAFTER'); ?></h4>
			</div>
			<div class="card-body">
				<form action="crafter/moduleCreation" method="post" class="form-horizontal">
					<?php $state->form->printControl('tableName') ?>
					<fieldset>
						<legend><?php BoilerplateLayout::printText('NEW_MODULE_OPTIONS'); ?></legend>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('objectName')?></div>
							<div class="col-md-3"><?php $state->form->printControl('objectName') ?></div>
							<div class="col-md-6 small"><?php BoilerplateLayout::printText('OBJECT_NAME_DESCRIPTION'); ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('moduleName')?></div>
							<div class="col-md-3"><?php $state->form->printControl('moduleName') ?></div>
							<div class="col-md-6 small"><?php BoilerplateLayout::printText('MODULE_NAME_DESCRIPTION'); ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('commonClass')?></div>
							<div class="col-md-3"><?php $state->form->printControl('commonClass') ?></div>
							<div class="col-md-6 small"><?php BoilerplateLayout::printText('COMMON_CLASS_DESCRIPTION'); ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('migration')?></div>
							<div class="col-md-3"><?php $state->form->printControl('migration') ?></div>
							<div class="col-md-6 small"><?php BoilerplateLayout::printText('MIGRATION_DESCRIPTION'); ?></div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php BoilerplateLayout::printText('GROUPS_ACL'); ?></legend>
						<?php foreach ($state->groups as $group) { ?>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('group' . $group->id) ?></div>
							<div class="col-md-3"><?php $state->form->printControl('group' . $group->id) ?></div>
							<div class="col-md-6 small"><?php print $state->groupDescription($group) ?></div>
						</div>
						<?php } ?>
					</fieldset>
					<fieldset>
						<div class="form-group row">
							<div class="col-md-push-3 col-md-9">
								<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('CREATE_MODULE'); ?></button>
								<a href="crafter" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
