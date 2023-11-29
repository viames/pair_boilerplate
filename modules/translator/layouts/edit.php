<div class="card">
	<div class="card-header">
		<h5><?php $this->_('EDIT_LANGUAGE_FILE', [$this->locale->getEnglishNames(), ucfirst($this->module->name)]) ?></h5>
	</div>
	<div class="card-body">
		<form action="translator/change" method="post" class="form-horizontal"><?php
		
		$this->form->printControl('locale');
		$this->form->printControl('module');
		
		foreach ($this->defStrings as $key=>$value) {
		
			?><div class="form-group row">
				<label class="col-md-3 col-form-label"><?php print htmlspecialchars($this->isDefault ? $key : $value) ?></label>
				<div class="col-md-9"><?php $this->form->printControl($key) ?></div>
			</div><?php 
					
		}

			?>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button class="btn btn-primary" type="submit"><?php $this->_('CHANGE') ?></button>
					<a class="btn btn-link" href="<?php print $this->parent ?>"><?php $this->_('CANCEL') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>