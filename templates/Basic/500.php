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
		<title>{{title}}</title>
		<?php $app->printStyles() ?>
	</head>
	<body class="page500">
		<div class="wrapper">
			<div class="box">
				<h3 class="animated fadeInDown"><?php print Translator::do('INTERNAL_SERVER_ERROR') ?></h3>
				<div class="icon animated fadeInUp"></div>
			</div>
		</div>
		{{content}}
		<div id="messageArea"></div>
		<?php $app->printScripts() ?>
	</body>
</html>