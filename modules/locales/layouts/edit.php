<?php use Pair\Helpers\Translator; ?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print Translator::do('EDIT_LOCALE'); ?></h4>
			</div>
			<div class="card-body">
				<form action="locales/change" method="post" class="form-horizontal">
					<?php $state->form->printControl('id'); ?>
					<fieldset>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('LANGUAGE'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('languageId'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('OFFICIAL_LANGUAGE'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('officialLanguage'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('COUNTRY'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('countryId'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('DEFAULT_COUNTRY'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('defaultCountry'); ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php print Translator::do('APP_DEFAULT'); ?></label>
							<div class="col-md-9"><?php $state->form->printControl('appDefault'); ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><?php print Translator::do('CHANGE'); ?></button>
							<a href="locales" class="btn btn-secondary"><?php print Translator::do('CANCEL'); ?></a>
							<?php if ($state->canDelete) { ?>
								<a href="locales/delete/<?php print $state->localeId; ?>" class="btn btn-link confirm-delete float-right text-danger"><?php print Translator::do('DELETE'); ?></a>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
