<div class="card">
	<div class="card-header">
		<h4 class="card-title"><?php BoilerplateLayout::printText('NEW_OAUTH2CLIENT'); ?></h4>
	</div>
	<div class="card-body">
		<form action="oauth2clients/add" method="post" class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<div class="col-md-3"><?php $state->form->printLabel('id') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('id') ?></div>
				</div>
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
					<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('INSERT'); ?></button>
					<a href="oauth2clients" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
