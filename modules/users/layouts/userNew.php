<form action="users/userAdd" method="post">
	<div class="card">
		<div class="card-header">
			<h5><?php $this->_('NEW_USER') ?></h5>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('NAME') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('name') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('SURNAME') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('surname') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('EMAIL') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('email') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('ENABLED') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('enabled') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('LDAP_USER') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('ldapUser') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('USERNAME') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('username') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('PASSWORD') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('password') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('SHOW_PASSWORD') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('showPassword') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('LANGUAGE') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('localeId') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3"><?php $this->_('GROUP') ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('groupId') ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="form-group row">
			<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary"><i class="fa fa-asterisk"></i> <?php $this->_('INSERT') ?></button>
					<a href="users" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
				</div>
			</div>
		</div>
	</div>
</form>