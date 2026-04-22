<?php use Pair\Helpers\Translator; ?>
<div class="ibox float-e-margins">
	<div class="card-header">
		<h5><?php print Translator::do('GROUP_EDIT'); ?> "<?php print htmlspecialchars($state->groupName); ?>"</h5>
	</div>
	<div class="card-body">
		<form action="users/groupChange" method="post" class="form-horizontal">
			<fieldset>
				<?php $state->form->printControl('id'); ?>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('NAME'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('name'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('IS_DEFAULT'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('default'); ?></div>
				</div>
				<?php if ($state->hasModules) { ?>
					<div class="form-group row">
						<label class="col-sm-2"><?php print Translator::do('DEFAULT_MODULE'); ?></label>
						<div class="col-sm-10"><?php $state->form->printControl('defaultAclId'); ?></div>
					</div>
				<?php } ?>
				<div class="buttonBar">
					<button type="submit" class="btn btn-primary" value="edit" name="action"><i class="fa fa-save"></i> <?php print Translator::do('CHANGE'); ?></button>
					<a href="groups" class="btn btn-secondary"><i class="fa fa-times"></i> <?php print Translator::do('CANCEL'); ?></a>
					<?php if ($state->canDelete) { ?>
						<a href="users/groupDelete/<?php print $state->groupId; ?>" class="btn btn-link confirm-delete float-right"><i class="fa fa-trash"></i> <?php print Translator::do('DELETE'); ?></a>
					<?php } ?>
				</div>
			</fieldset>
		</form>
	</div>
</div>
