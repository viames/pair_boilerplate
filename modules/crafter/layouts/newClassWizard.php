<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('CRAFTER'); ?></h4>
			</div>
			<div class="card-body">
				<div class="container">
					<form action="crafter/classCreation" method="post" class="form-horizontal"> 
						<fieldset>
							<div class="form-group row">
								<label class="col-md-3 control-label"><?php BoilerplateLayout::printText('OBJECT_NAME'); ?></label>
								<div class="col-md-3"><?php $state->form->printControl('objectName') ?></div>
								<div class="col-md-6 small"><?php BoilerplateLayout::printText('OBJECT_NAME_DESCRIPTION'); ?></div>
							</div>
							<?php $state->form->printControl('tableName') ?>
							<div class="form-group row">
								<div class="col-md-push-3 col-md-9">
									<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('CREATE_CLASS'); ?></button>
									<a href="crafter" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
