<?php 

use Pair\Core\Application;
use Pair\Helpers\Translator;

$app = Application::getInstance();
$translator = Translator::getInstance();

?><!DOCTYPE html>
<html lang="{{langCode}}" dir="ltr">
	<head>
		<base href="<?php print BASE_HREF ?>" />
		<meta charset="utf-8" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<title><?php print $app->pageTitle ?></title>
		<?php $app->printStyles() ?>
	</head>
	<body class="page404">
		<div class="wrapper">
			<div class="box">
				<h3 class="animated fadeInDown"><?php print Translator::do('RESOURCE_NOT_FOUND') ?></h3>
				<div class="icon animated fadeInUp"></div>
			</div>
		</div>
		<h1>404</h1>
		<div id="messageArea"></div>
		<?php $app->printScripts() ?>
	</body>
</html>