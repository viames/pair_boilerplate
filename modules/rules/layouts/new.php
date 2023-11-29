<form action="rules/add" method="post">
	<div class="card">
		<div class="card-body">
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->_('MODULE')?></label>
				<div class="col-md-9"><?php $this->form->printControl('moduleId') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->_('ACTION')?></label>
				<div class="col-md-9"><?php $this->form->printControl('actionField') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->_('ADMIN_ONLY')?></label>
				<div class="col-md-9"><?php $this->form->printControl('adminOnly') ?></div>
			</div>
			</div> 
		<div class="card-footer">
		<div class="row">
			<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
			<div class="col-9 order-2">
				<button type="submit" class="btn btn-primary" value="add"><?php $this->_('INSERT') ?></button>
				<a href="rules/default" class="btn btn-link"><?php $this->_('CANCEL') ?></a>
				</div>
			</div>
		</div>
	</div>
</form>