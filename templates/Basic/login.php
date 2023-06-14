<!DOCTYPE html>
<html lang="<?php print $this->langCode ?>">
	<head>
		<base href="<?php print BASE_HREF ?>" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php print $this->pageTitle ?></title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php print $this->templatePath ?>css/iziToast.min.css">
		<link rel="stylesheet" href="<?php print $this->templatePath ?>css/custom.css">
		<?php print $this->pageStyles ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="page-content-wrapper">
				<div class="container-fluid">
					<div id="messageArea"></div>
					<?php print $this->pageContent ?>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
		<script src="<?php print $this->templatePath ?>js/iziToast.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.1/pwstrength-bootstrap.min.js"></script>
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<script src="<?php print $this->templatePath ?>js/custom.js" type="text/javascript"></script>
		<?php print $this->pageScripts ?>
	</body>    
</html>