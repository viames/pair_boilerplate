<?php 

use Pair\Core\Application;
use Pair\Helpers\Translator;

$app = Application::getInstance();
$translator = Translator::getInstance();

?><!DOCTYPE html>
<html lang="<?php print $app->langCode ?>">
	<head>
		<base href="<?php print BASE_HREF ?>" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php print $app->pageTitle ?></title>
		<?php print $app->pageStyles ?>
		<link rel="stylesheet" href="css/toastr.css">
		<link rel="stylesheet" href="css/custom.css">
	</head>
	<body class="page404">
		<div id="messageArea"></div>
		<div class="wrapper">
			<div class="box">
				<h3 class="animated fadeInDown"><?php print Translator::do('NOT_FOUND', '') ?></h3>
				<div class="icon animated fadeInUp"></div>
			</div>
		</div>
		<?php print $app->log ?>
		<?php print $app->pageScripts ?>
		<script src="js/toastr.js" type="text/javascript"></script>
		<script src="js/custom.js" type="text/javascript"></script>
	</body>
</html>