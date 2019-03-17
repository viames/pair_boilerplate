<form action="tokens/add" method="post">
	<div class="card">
		<div class="card-body">
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->form->printLabel('code') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('code') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->form->printLabel('description') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('description') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->form->printLabel('value') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('value') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->form->printLabel('enabled') ?></label>
				<div class="col-md-9"><?php $this->form->printControl('enabled') ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="form-group row">
			<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary"><i class="fal fa-asterisk"></i> <?php $this->_('INSERT') ?></button>
					<a href="tokens" class="btn btn-secondary"><i class="fal fa-times"></i> <?php $this->_('CANCEL') ?></a>
				</div>
			</div>
		</div>
	</div>
</form>