<div class="card">
	<div class="card-header">
		<h4 class="card-title"><?php BoilerplateLayout::printText('EDIT_TEMPLATE'); ?></h4>
	</div>
	<div class="card-body">
		<form action="templates/change" method="post">
			<?php $state->form->printControl('id') ?>
			<fieldset>
				<div class="row mb-4">
					<div class="col-md-3"><?php $state->form->printLabel('name') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('name') ?></div>
				</div>
				<div class="row mb-4">
					<div class="col-md-3"><?php $state->form->printLabel('version') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('version') ?></div>
				</div>
				<div class="row mb-4">
					<div class="col-md-3"><?php $state->form->printLabel('appVersion') ?></div>
					<div class="col-md-9"><?php $state->form->printControl('appVersion') ?></div>
				</div>
				<div class="row mb-4">
					<label class="col-md-3 col-form-label"><?php BoilerplateLayout::printText('PALETTE'); ?></label>
					<div class="col-md-9">
						<p class="small text-body-secondary mb-2"><?php BoilerplateLayout::printText('PALETTE_HELP'); ?></p>
						<div class="mb-2">
							<button type="button" class="btn btn-outline-primary btn-sm" data-action="palette-add">
								<i class="fal fa-plus me-1"></i><?php BoilerplateLayout::printText('ADD_COLOR'); ?>
							</button>
						</div>
						<div class="template-palette-editor" data-role="palette-editor" data-default-color="<?php print htmlspecialchars($state->paletteDefaultColor) ?>">
							<div data-role="palette-list"><?php

								foreach ($state->paletteColors as $color) { ?>
								<div class="d-flex align-items-center gap-2 mb-2" data-role="palette-item">
									<input
										type="color"
										class="form-control form-control-color flex-shrink-0"
										value="<?php print htmlspecialchars($color) ?>"
										data-role="palette-picker"
										title="<?php print htmlspecialchars($color) ?>"
									>
									<input
										type="text"
										class="form-control"
										name="palette[]"
										value="<?php print htmlspecialchars($color) ?>"
										data-role="palette-text"
										placeholder="#57A0E5"
										autocomplete="off"
										spellcheck="false"
									>
									<button type="button" class="btn btn-outline-danger btn-sm" data-action="palette-remove" title="<?php print htmlspecialchars(BoilerplateLayout::translate('REMOVE_COLOR')) ?>">
										<i class="fal fa-times"></i>
									</button>
								</div><?php
								}

							?></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="row mb-4">
				<div class="col-md-9 offset-md-3">
					<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('CHANGE'); ?></button>
					<a href="templates/default" class="btn btn-secondary"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
