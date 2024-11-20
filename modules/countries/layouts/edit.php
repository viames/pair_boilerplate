<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('EDIT_COUNTRY') ?></h4>
			</div>
			<div class="card-body">
				<form action="countries/change" method="post" class="form-horizontal">
					<?php $this->form->printControl('id') ?>
					<fieldset>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('ISO_3166_1') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('code') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('ENGLISH_NAME') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('englishName') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('NATIVE_NAME') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('nativeName') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><?php $this->_('CHANGE') ?></button>
							<a href="countries" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a><?php
							if ($this->country->isDeletable()) { ?>
							<a href="countries/delete/<?php print $this->country->id ?>" class="btn btn-link confirm-delete float-right text-danger"><?php $this->_('DELETE') ?></a><?php
							} ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>