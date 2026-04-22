<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('EDIT_LANGUAGE_FILE', array($state->locale->getEnglishNames(), ucfirst($state->module->name))); ?></h4>
			</div>
			<div class="card-body">
				<form action="translator/change" method="post" class="form-horizontal">
				<fieldset>
				<?php
				
				$state->form->printControl('locale');
				$state->form->printControl('module');
				
				foreach ($state->defStrings as $key=>$value) {
				
					?><div class="form-group">
						<label class="col-md-3 control-label"><?php print htmlspecialchars($state->isDefault ? $key : $value) ?></label>
						<div class="col-md-9"><?php $state->form->printControl($key) ?></div>
					</div><?php 
							
				}
		
					?>
					<div class="form-group">
						<div class="col-md-push-3 col-md-9">
							<button class="btn btn-primary" type="submit"><?php BoilerplateLayout::printText('CHANGE'); ?></button>
							<a class="btn btn-secondary" href="<?php print $state->parent ?>"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
						</div>
					</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
