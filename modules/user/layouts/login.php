<div class="row">
	<div class="col-3 order-1 d-none d-xs-none d-sm-block"></div>
	<div class="col-xs-12 col-sm-6 order-2">
		<div id="app-logo"></div>
		<div><h1 class="logo-name"><?php print Pair\Core\Config::get('PRODUCT_NAME') ?></h1></div>
			<form class="form-horizontal mt" role="form" action="user/login" method="post">
			<fieldset>
				<legend><?php $this->_('LOGIN') ?></legend>
				<div class="form-group row">
					<?php $this->form->printControl('username') ?>
				</div>
				<div class="form-group row">
					<?php $this->form->printControl('password') ?>
				</div><?php
	
				$this->form->printControl('timezone');
	
				?><button type="submit" class="btn btn-primary block full-width m-b"><?php $this->_('LOGIN') ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-3 order-3 d-none d-xs-none d-sm-block"></div>
</div>