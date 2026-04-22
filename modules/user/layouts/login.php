<?php

use Pair\Core\Env;
use Pair\Helpers\Translator;

?>
<div class="row">
	<div class="col-3 order-1 d-none d-xs-none d-sm-block"></div>
	<div class="col-xs-12 col-sm-6 order-2">
		<div id="app-logo"></div>
		<div><h1 class="logo-name"><?php print Env::get('APP_NAME'); ?></h1></div>
		<form class="form-horizontal mt" role="form" action="user/login" method="post">
			<fieldset>
				<legend><?php print Translator::do('LOGIN'); ?></legend>
				<div class="form-group row">
					<?php $state->form->printControl('username'); ?>
				</div>
				<div class="form-group row">
					<?php $state->form->printControl('password'); ?>
				</div>
				<?php $state->form->printControl('timezone'); ?>
				<button type="submit" class="btn btn-primary block full-width m-b"><?php print Translator::do('LOGIN'); ?></button>
			</fieldset>
		</form>
	</div>
	<div class="col-3 order-3 d-none d-xs-none d-sm-block"></div>
</div>
