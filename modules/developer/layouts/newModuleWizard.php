<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('DEVELOPER') ?></h4>
			</div>
			<div class="card-body">
				<div class="container">
					<form action="developer/moduleCreation" method="post" class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label class="col-md-3 col-form-label"><?php $this->_('OBJECT_NAME')?></label>
								<div class="col-md-3"><?php $this->form->printControl('objectName') ?></div>
								<div class="col-md-6 small"><?php $this->_('OBJECT_NAME_DESCRIPTION')?></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-form-label"><?php $this->_('MODULE_NAME')?></label>
								<div class="col-md-3"><?php $this->form->printControl('moduleName') ?></div>
								<div class="col-md-6 small"><?php $this->_('MODULE_NAME_DESCRIPTION')?></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-form-label"><?php $this->_('COMMON_CLASS')?></label>
								<div class="col-md-3"><?php $this->form->printControl('commonClass') ?></div>
								<div class="col-md-6 small"><?php $this->_('COMMON_CLASS_DESCRIPTION')?></div>
							</div>
							<?php $this->form->printControl('tableName') ?>
							<div class="form-group">
								<div class="col-md-push-3 col-md-9">
									<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php $this->_('CREATE_MODULE') ?></button>
									<a href="developer" class="btn btn-default"><i class="fa fa-times"></i> <?php $this->_('CANCEL')?></a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>