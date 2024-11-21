<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('EDIT_LOCALE') ?></h4>
			</div>
			<div class="card-body">
				<form action="locales/change" method="post" class="form-horizontal">
					<?php $this->form->printControl('id') ?>
					<fieldset>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('LANGUAGE') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('languageId') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('OFFICIAL_LANGUAGE') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('officialLanguage') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('COUNTRY') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('countryId') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('DEFAULT_COUNTRY') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('defaultCountry') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('APP_DEFAULT') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('appDefault') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><?php $this->_('CHANGE') ?></button>
							<a href="locales" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a><?php
							if ($this->locale->isDeletable() and !$this->locale->appDefault) { ?>
							<a href="locales/delete/<?php print $this->locale->id ?>" class="btn btn-link confirm-delete float-right text-danger"><?php $this->_('DELETE') ?></a><?php
							} ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>