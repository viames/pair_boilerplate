<div class="card">
	<div class="card-header">
		<h4 class="card-title">Client ID: <?php print htmlspecialchars((string)$state->oauth2Client->id) ?></h4>
	</div>
	<div class="card-body">
		<form action="oauth2clients/change" method="post" class="form-horizontal">
			<?php $state->form->printControl('id') ?>
			<fieldset>
				<div class="form-group">
					<div class="col-md-3"><?php $state->form->printLabel('secret') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('secret') ?></div>
				</div>
				<div class="form-group">
					<div class="col-md-3"><?php $state->form->printLabel('enabled') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('enabled') ?></div>
				</div>
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('CHANGE'); ?></button>
					<a href="oauth2clients" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a><?php
					if ($state->oauth2Client->isDeletable()) { ?>
					<a href="oauth2clients/delete/<?php print $state->oauth2Client->id ?>" class="btn btn-link confirm-delete float-right"><?php BoilerplateLayout::printText('DELETE'); ?></a><?php
					} ?>
				</div>
			</div>
		</form>
	</div>
</div>
