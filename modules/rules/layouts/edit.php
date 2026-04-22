<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('EDIT_RULE'); ?></h4>
			</div>
			<div class="card-body">
				<form action="rules/change" method="post" class="form-horizontal" data-parsley-validate>
					<fieldset>
						<?php $state->form->printControl('id') ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php BoilerplateLayout::printText('MODULE'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('moduleId') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php BoilerplateLayout::printText('ACTION'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('actionField') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php BoilerplateLayout::printText('ADMIN_ONLY'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('adminOnly') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-push-3 col-md-9">
								<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('CHANGE'); ?></button>
								<a href="rules/default" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
								<a href="rules/delete/<?php print $state->ruleId ?>" class="btn btn-link confirm-delete float-right text-danger"><?php BoilerplateLayout::printText('DELETE'); ?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
