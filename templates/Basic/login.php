<?php

use Pair\Core\Application;

$app = Application::getInstance();

?><!DOCTYPE html>
<html lang="<?php print $app->langCode ?>">
	<head>
		<base href="<?php print BASE_HREF ?>" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php print $app->pageTitle ?></title>
		<?php $app->printStyles() ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="page-content-wrapper">
				<div class="container-fluid">
					<div id="messageArea"></div>
					<?php print $app->pageContent ?>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
		<?php $app->printScripts() ?>
	</body>    
</html>