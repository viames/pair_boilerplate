<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

?><div class="row">
	<div class="col-md-push-3 col-md-6">
		<div id="app-logo"></div>
		<div><h1 class="logo-name"><?php print PRODUCT_NAME ?></h1></div>
			<form class="form-horizontal mt" role="form" action="user/login" method="post">
			<fieldset>
				<legend><?php $this->_('LOGIN') ?></legend>
				<div class="form-group">
					<?php print $this->form->renderControl('username') ?>
				</div>
				<div class="form-group">
					<?php print $this->form->renderControl('password') ?>
				</div><?php
	
				print $this->form->renderControl('referer');
				print $this->form->renderControl('timezone');
	
				?><button type="submit" class="btn btn-primary block full-width m-b">Login</button>
			</fieldset>
		</form>
	</div>
</div>