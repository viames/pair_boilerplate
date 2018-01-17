<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

?><form action="rules/change" method="post">
	<div class="card">
		<div class="card-body">
			<?php print $this->form->renderControl('id') ?>
			<div class="form-group row">
				<label class="col-md-3 control-label"><?php $this->_('MODULE')?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('module') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 control-label"><?php $this->_('ACTION')?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('actionAcl') ?></div>
			</div>
			<div class="form-group row">
				<label class="col-md-3 control-label"><?php $this->_('ADMIN_ONLY')?></label>
				<div class="col-md-9"><?php print $this->form->renderControl('adminOnly') ?></div>
			</div>
		</div>
		<div class="card-footer">
			<div class="form-group row">
				<div class="col-3 order-1 d-none d-sm-none d-md-block"></div>
				<div class="col-9 order-2">
					<button type="submit" class="btn btn-primary" value="edit"><i class="fa fa-save"></i> <?php $this->_('CHANGE') ?></button>
					<a href="rules/default" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_('CANCEL') ?></a>
					<button type="submit" class="btn btn-link confirm-delete float-right" value="delete" name="action"><i class="fa fa-trash"></i> <?php $this->_('DELETE') ?></button>
				</div>
			</div>
		</div>
	</div>
</form>