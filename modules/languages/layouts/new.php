<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('NEW_LANGUAGE') ?></h4>
			</div>
			<div class="card-body">
				<form action="languages/add" method="post">
					<fieldset>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('ENGLISH_NAME') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('englishName') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('NATIVE_NAME') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('nativeName') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('ISO_639_1') ?></label>
							<div class="col-md-9"><?php $this->form->printControl('code') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><i class="fa fa-asterisk"></i> <?php $this->_('INSERT') ?></button>
							<a href="languages" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>