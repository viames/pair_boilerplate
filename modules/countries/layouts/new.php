<?php use Pair\Helpers\Translator; ?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print Translator::do('NEW_COUNTRY'); ?></h4>
			</div>
			<div class="card-body">
				<form action="countries/add" method="post" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('ENGLISH_NAME'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('englishName'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('NATIVE_NAME'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('nativeName'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('ISO_3166_1'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('code'); ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><?php print Translator::do('INSERT'); ?></button>
							<a href="countries" class="btn btn-secondary"><?php print Translator::do('CANCEL'); ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
