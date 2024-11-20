<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('NEW_RULE') ?></h4>
			</div>
			<div class="card-body">
				<form action="rules/add" method="post" class="form-horizontal" data-parsley-validate>
					<fieldset>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('MODULE')?></label>
							<div class="col-md-9"><?php $this->form->printControl('moduleId') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('ACTION')?></label>
							<div class="col-md-9"><?php $this->form->printControl('actionField') ?></div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php $this->_('ADMIN_ONLY')?></label>
							<div class="col-md-9"><?php $this->form->printControl('adminOnly') ?></div>
						</div> 
						<div class="form-group">
							<div class="col-md-push-3 col-md-9">
								<button type="submit" class="btn btn-primary" value="add" name="action_add"><?php $this->_('INSERT') ?></button>
								<a href="rules/default" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>