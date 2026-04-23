<?php
declare(strict_types=1);

/** @var CrafterClassWizardPageState $state */
?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CRAFTER'), ENT_QUOTES, 'UTF-8') ?></h4>
			</div>
			<div class="card-body">
				<div class="container">
					<form action="crafter/classCreation" method="post" class="form-horizontal"> 
						<fieldset>
							<div class="form-group row">
								<label class="col-md-3 control-label"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('OBJECT_NAME'), ENT_QUOTES, 'UTF-8') ?></label>
								<div class="col-md-3"><?php $state->form->printControl('objectName') ?></div>
								<div class="col-md-6 small"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('OBJECT_NAME_DESCRIPTION'), ENT_QUOTES, 'UTF-8') ?></div>
							</div>
							<?php $state->form->printControl('tableName') ?>
							<div class="form-group row">
								<div class="col-md-push-3 col-md-9">
									<button type="submit" class="btn btn-primary"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CREATE_CLASS'), ENT_QUOTES, 'UTF-8') ?></button>
									<a href="crafter" class="btn btn-secondary"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CANCEL'), ENT_QUOTES, 'UTF-8') ?></a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>