<form action="users/userChange" method="post">
	<div class="card">
		<div class="card-header">
			<h5><?php $this->_('USER_EDIT') ?></h5>
		</div>
		<div class="card-body">
			<?php $this->form->printControl('id') ?>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('NAME') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('name') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('SURNAME') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('surname') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('EMAIL') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('email') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('ENABLED') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('enabled') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('USERNAME') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('username') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('PASSWORD') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('password') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('SHOW_PASSWORD') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('showPassword') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('LANGUAGE') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('localeId') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('GROUP') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('groupId') ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="form-group row">
			<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php $this->_('CHANGE')?></button>
					<a href="users" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a><?php
					if ($this->user->isDeletable()) {
						?><a href="users/userDelete/<?php print $this->user->id ?>" class="btn btn-link confirm-delete float-right"><i class="fa fa-trash"></i> <?php $this->_('DELETE') ?></a><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</form>