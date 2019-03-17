<div class="row">
	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('NEW_LOCALE') ?></h4>
			</div>
			<div class="panel-body">
				<form action="locales/add" method="post">
					<fieldset>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('LANGUAGE') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('languageId') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('OFFICIAL_LANGUAGE') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('officialLanguage') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('COUNTRY') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('countryId') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('DEFAULT_COUNTRY') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('defaultCountry') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('APP_DEFAULT') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('appDefault') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><i class="fa fa-asterisk"></i> <?php $this->_('INSERT') ?></button>
							<a href="locales" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>