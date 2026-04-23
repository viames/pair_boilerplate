<?php
declare(strict_types=1);

/** @var CrafterModuleWizardPageState $state */
?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CRAFTER'), ENT_QUOTES, 'UTF-8') ?></h4>
			</div>
			<div class="card-body">
				<form action="crafter/moduleCreation" method="post" class="form-horizontal">
					<?php $state->form->printControl('tableName') ?>
					<fieldset>
						<legend><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('NEW_MODULE_OPTIONS'), ENT_QUOTES, 'UTF-8') ?></legend>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('objectName')?></div>
							<div class="col-md-3"><?php $state->form->printControl('objectName') ?></div>
							<div class="col-md-6 small"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('OBJECT_NAME_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('moduleName')?></div>
							<div class="col-md-3"><?php $state->form->printControl('moduleName') ?></div>
							<div class="col-md-6 small"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('MODULE_NAME_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('commonClass')?></div>
							<div class="col-md-3"><?php $state->form->printControl('commonClass') ?></div>
							<div class="col-md-6 small"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('COMMON_CLASS_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></div>
						</div>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('migration')?></div>
							<div class="col-md-3"><?php $state->form->printControl('migration') ?></div>
							<div class="col-md-6 small"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('MIGRATION_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></div>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('GROUPS_ACL'), ENT_QUOTES, 'UTF-8') ?></legend>
						<?php foreach ($state->groups as $group) { ?>
						<div class="form-group row">
							<div class="col-md-3"><?php $state->form->printLabel('group' . $group->id) ?></div>
							<div class="col-md-3"><?php $state->form->printControl('group' . $group->id) ?></div>
							<div class="col-md-6 small"><?php $state->printGroupDescription($group) ?></div>
						</div>
						<?php } ?>
					</fieldset>
					<fieldset>
						<div class="form-group row">
							<div class="col-md-push-3 col-md-9">
								<button type="submit" class="btn btn-primary"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CREATE_MODULE'), ENT_QUOTES, 'UTF-8') ?></button>
								<a href="crafter" class="btn btn-secondary"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CANCEL'), ENT_QUOTES, 'UTF-8') ?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>