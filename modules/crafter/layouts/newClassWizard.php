<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('CRAFTER') ?></h4>
			</div>
			<div class="card-body">
				<div class="container">
					<form action="crafter/classCreation" method="post" class="form-horizontal"> 
						<fieldset>
							<div class="form-group row">
								<label class="col-md-3 control-label"><?php $this->_('OBJECT_NAME')?></label>
								<div class="col-md-3"><?php $this->form->printControl('objectName') ?></div>
								<div class="col-md-6 small"><?php $this->_('OBJECT_NAME_DESCRIPTION')?></div>
							</div>
							<?php $this->form->printControl('tableName') ?>
							<div class="form-group row">
								<div class="col-md-push-3 col-md-9">
									<button type="submit" class="btn btn-primary"><?php $this->_('CREATE_CLASS') ?></button>
									<a href="crafter" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>