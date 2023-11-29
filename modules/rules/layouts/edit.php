<?php

?><form action="rules/change" method="post">
	<div class="card">
		<div class="card-body">
			<?php $this->form->printControl('id') ?>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->_('MODULE')?></label>
				<div class="col-md-9"><?php $this->form->printControl('moduleId') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 col-form-label"><?php $this->_('ACTION')?></label>
				<div class="col-md-9"><?php $this->form->printControl('actionField') ?></div>
			</div>
			<div class="row">
				<label class="col-md-3 col-form-label"><?php $this->_('ADMIN_ONLY')?></label>
				<div class="col-md-9"><?php $this->form->printControl('adminOnly') ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary" value="edit"><?php $this->_('CHANGE') ?></button>
					<a href="rules/default" class="btn btn-link"><?php $this->_('CANCEL') ?></a><?php
					if ($this->rule->isDeletable()) {
						?><a href="rules/delete/<?php print $this->rule->id ?>" class="btn btn-link confirm-delete float-right"><?php $this->_('DELETE') ?></a><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</form>