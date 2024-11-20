<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('EDIT_RULE') ?></h4>
			</div>
			<div class="card-body">
				<form action="rules/change" method="post" class="form-horizontal" data-parsley-validate>
					<fieldset>
						<?php $this->form->printControl('id') ?>
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
								<button type="submit" class="btn btn-primary"><?php $this->_('CHANGE')?></button>
								<a href="rules/default" class="btn btn-secondary"><?php $this->_('CANCEL') ?></a>
								<a href="rules/delete/<?php print $this->ruleId ?>" class="btn btn-link confirm-delete float-right text-danger"><?php $this->_('DELETE')?></a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>