<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('NEW_LANGUAGE') ?></h4>
			</div>
			<div class="card-body">
				<form action="languages/add" method="post" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('ENGLISH_NAME') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('englishName') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('NATIVE_NAME') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('nativeName') ?></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><?php $this->_('ISO_639_1') ?></div>
							<div class="col-md-9"><?php $this->form->printControl('code') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><?php $this->_('INSERT') ?></button>
							<a href="languages" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>