<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('EDIT_LANGUAGE_FILE', array($this->locale->getEnglishNames(), ucfirst($this->module->name))) ?></h4>
			</div>
			<div class="card-body">
				<form action="translator/change" method="post" class="form-horizontal">
				<fieldset>
				<?php
				
				$this->form->printControl('locale');
				$this->form->printControl('module');
				
				foreach ($this->defStrings as $key=>$value) {
				
					?><div class="form-group">
						<label class="col-md-3 control-label"><?php print htmlspecialchars($this->isDefault ? $key : $value) ?></label>
						<div class="col-md-9"><?php $this->form->printControl($key) ?></div>
					</div><?php 
							
				}
		
					?>
					<div class="form-group">
						<div class="col-md-push-3 col-md-9">
							<button class="btn btn-primary" type="submit"><?php $this->_('CHANGE') ?></button>
							<a class="btn btn-secondary" href="<?php print $this->parent ?>"><?php $this->_('CANCEL') ?></a>
						</div>
					</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>