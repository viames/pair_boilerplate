<?php 

use Pair\Translator;

$translator = Translator::getInstance();

?><!DOCTYPE html>
<html lang="<?php print $this->langCode ?>">
	<head>
		<base href="<?php print BASE_HREF ?>" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php print $this->pageTitle ?></title>
		<?php print $this->pageStyles ?>
		<link rel="stylesheet" href="css/toastr.css">
		<link rel="stylesheet" href="css/custom.css">
	</head>
	<body class="page404">
		<div id="messageArea"></div>
		<div class="wrapper">
			<div class="box">
				<h3 class="animated fadeInDown"><?php print $translator->get('NOT_FOUND', '') ?></h3>
				<div class="icon animated fadeInUp"></div>
			</div>
		</div>
		<?php print $this->log ?>
		<?php print $this->pageScripts ?>
		<script src="js/toastr.js" type="text/javascript"></script>
		<script src="js/custom.js" type="text/javascript"></script>
	</body>
</html>