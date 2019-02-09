<div class="row">
	<div class="col-3 order-1 d-none d-xs-none d-sm-block"></div>
	<div class="col-xs-12 col-sm-6 order-2">
		<div id="app-logo"></div>
		<div><h1 class="logo-name"><?php print PRODUCT_NAME ?></h1></div>
			<form class="form-horizontal mt" role="form" action="user/login" method="post">
			<fieldset>
				<legend><?php $this->_('LOGIN') ?></legend>
				<div class="form-group row">
					<?php print $this->form->renderControl('username') ?>
				</div>
				<div class="form-group row">
					<?php print $this->form->renderControl('password') ?>
				</div><?php
	
				print $this->form->renderControl('timezone');
	
				?><button type="submit" class="btn btn-primary block full-width m-b"><?php $this->_('LOGIN') ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-3 order-3 d-none d-xs-none d-sm-block"></div>
</div>