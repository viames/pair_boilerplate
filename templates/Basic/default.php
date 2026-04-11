<!DOCTYPE html>
<html lang="{{langCode}}" dir="ltr">
	<head>
		<base href="{{baseHref}}" />
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="theme-color" content="#323b44" />
		<meta name="robots" content="noindex, nofollow" />
		<link rel="manifest" href="manifest.json" />
		<link rel="icon" type="image/png" href="images/pair-logo.png" />
		<link rel="apple-touch-icon" href="images/pair-logo.png" />
		<link rel="stylesheet" href="css/simple-sidebar.css" />
		{{styles}}
		<title>{{title}}</title>
		<style>
			body {
				background: #f3f5f8;
			}
			#sidebar-wrapper .nav {
				flex-direction: column;
			}
			#page-content-wrapper {
				min-width: 0;
			}
		</style>
	</head>
	<body>
		<div class="d-flex min-vh-100" id="wrapper">
			<aside class="border-end bg-white" id="sidebar-wrapper">
				<div class="sidebar-heading border-bottom bg-light">
					<a href="{{baseHref}}" class="text-decoration-none fw-semibold text-dark">Pair Boilerplate</a>
				</div>
				<nav class="p-2">
					<ul class="nav gap-1">
						{{sideMenu}}
					</ul>
				</nav>
			</aside>
			<div class="d-flex flex-column flex-grow-1" id="page-content-wrapper">
				<header class="border-bottom bg-white px-3 py-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div class="d-flex align-items-center gap-3">
							<button class="btn btn-outline-secondary btn-sm" id="sidebarToggle" type="button">Menu</button>
							<div>
								<div class="small text-uppercase text-muted">Admin</div>
								<h1 class="h4 mb-0">{{heading}}</h1>
							</div>
						</div>
						<div class="small text-muted">{{breadcrumb}}</div>
					</div>
				</header>
				<main class="container-fluid py-4 flex-grow-1">
					<div id="messageArea"></div>
					{{content}}
				</main>
				<div class="px-3 pb-3">{{logBar}}</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
		<script src="js/simple-sidebar.js" type="text/javascript"></script>
		{{scripts}}
	</body>
</html>
