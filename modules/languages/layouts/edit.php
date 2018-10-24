<div class="row">
	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('EDIT_LANGUAGE') ?></h4>
			</div>
			<div class="panel-body">
				<form action="languages/change" method="post">
					<?php print $this->form->renderControl('id') ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('CODE') ?></label>
							<div class="col-md-9"><?php print $this->form->renderControl('code') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('NATIVE_NAME') ?></label>
							<div class="col-md-9"><?php print $this->form->renderControl('nativeName') ?></div>
						</div>
						<div class="form-group row">
							<label class="col-md-3"><?php $this->_('ENGLISH_NAME') ?></label>
							<div class="col-md-9"><?php print $this->form->renderControl('englishName') ?></div>
						</div>
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php $this->_('CHANGE') ?></button>
							<a href="languages" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a><?php
							if ($this->language->isDeletable()) { ?>
							<a href="languages/delete/<?php print $this->language->id ?>" class="btn btn-link confirm-delete pull-right float-right"><i class="fa fa-trash"></i> <?php $this->_('DELETE') ?></a><?php
							} ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>