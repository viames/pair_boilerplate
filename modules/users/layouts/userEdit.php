<?php use Pair\Helpers\Translator; ?>
<form action="users/userChange" method="post">
	<div class="card">
		<div class="card-header">
			<h5><?php print Translator::do('USER_EDIT'); ?></h5>
		</div>
		<div class="card-body">
			<?php $state->form->printControl('id'); ?>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('NAME'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('name'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('SURNAME'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('surname'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('EMAIL'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('email'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('ENABLED'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('enabled'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('USERNAME'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('username'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('PASSWORD'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('password'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('SHOW_PASSWORD'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('showPassword'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('LANGUAGE'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('localeId'); ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php print Translator::do('GROUP'); ?></label>
				<div class="col-md-9"><?php $state->form->printControl('groupId'); ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="form-group row">
				<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php print Translator::do('CHANGE'); ?></button>
					<a href="users" class="btn btn-secondary"><i class="fa fa-times"></i> <?php print Translator::do('CANCEL'); ?></a>
					<?php if ($state->canDelete) { ?>
						<a href="users/userDelete/<?php print $state->userId; ?>" class="btn btn-link confirm-delete float-right"><i class="fa fa-trash"></i> <?php print Translator::do('DELETE'); ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</form>
