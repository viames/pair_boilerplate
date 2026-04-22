<?php

use Pair\Helpers\Translator;

?>
<div class="card">
	<div class="card-header">
		<h5><?php print Translator::do('USER_EDIT', $state->fullName); ?></h5>
	</div>
	<div class="card-body">
		<form action="user/profileChange" method="post" class="form-horizontal">
			<fieldset>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('NAME'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('name'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('SURNAME'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('surname'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('USERNAME'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('username'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('PASSWORD'); ?></label>
					<div class="col-sm-10">
						<input class="autocompleteFix" style="display:none" type="password" name="password" />
						<?php $state->form->printControl('password'); ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('SHOW_PASSWORD'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('showPassword'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('EMAIL'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('email'); ?></div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2"><?php print Translator::do('LANGUAGE'); ?></label>
					<div class="col-sm-10"><?php $state->form->printControl('localeId'); ?></div>
				</div>
				<div class="col-sm-10 col-sm-offset-2">
					<button type="submit" class="btn btn-primary" value="edit" name="action"><i class="fa fa-save"></i> <?php print Translator::do('CHANGE'); ?></button>
					<a href="user/profile" class="btn btn-secondary"><i class="fa fa-times"></i> <?php print Translator::do('CANCEL'); ?></a>
				</div>
			</fieldset>
		</form>
	</div>
</div>
