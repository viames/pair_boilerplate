<div class="card">
	<div class="card-header">
		<h5><?php $this->_('EDIT_LANGUAGE_FILE', array($this->locale->getEnglishNames(), ucfirst($this->module->name))) ?></h5>
	</div>
	<div class="card-body">
		<form action="translator/change" method="post" class="form-horizontal"><?php
		
		print $this->form->renderControl('locale');
		print $this->form->renderControl('module');
		
		foreach ($this->defStrings as $key=>$value) {
		
			?><div class="form-group row">
				<label class="col-md-3"><?php print htmlspecialchars($this->isDefault ? $key : $value) ?></label>
				<div class="col-md-9"><?php print $this->form->renderControl($key) ?></div>
			</div><?php 
					
		}

			?>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?php $this->_('CHANGE') ?></button>
					<a class="btn btn-secondary" href="<?php print $this->referer ?>"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>