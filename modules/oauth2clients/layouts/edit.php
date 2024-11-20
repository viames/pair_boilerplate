<div class="card">
	<div class="card-header">
		<h4 class="card-title">Client ID: <?php print $this->oauth2Client->printHtml('id') ?></h4>
	</div>
	<div class="card-body">
		<form action="oauth2clients/change" method="post" class="form-horizontal">
			<?php $this->form->printControl('id') ?>
			<fieldset>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php $this->form->printLabel('secret') ?></label>
					<div class="col-md-9"><?php $this->form->printControl('secret') ?></div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php $this->form->printLabel('enabled') ?></label>
					<div class="col-md-9"><?php $this->form->printControl('enabled') ?></div>
				</div>
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button type="submit" class="btn btn-primary"><?php $this->_('CHANGE') ?></button>
					<a href="oauth2clients" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a><?php
					if ($this->oauth2Client->isDeletable()) { ?>
					<a href="oauth2clients/delete/<?php print $this->oauth2Client->id ?>" class="btn btn-link confirm-delete float-right"><?php $this->_('DELETE') ?></a><?php
					} ?>
				</div>
			</div>
		</form>
	</div>
</div>