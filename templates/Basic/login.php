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
		{{styles}}
		<title>{{title}}</title>
		<style>
			body {
				min-height: 100vh;
				background:
					linear-gradient(135deg, rgba(50, 59, 68, 0.95), rgba(22, 27, 34, 0.92)),
					radial-gradient(circle at top left, rgba(26, 179, 148, 0.35), transparent 40%);
			}
		</style>
	</head>
	<body class="d-flex align-items-center justify-content-center p-3 p-lg-4">
		<div class="row g-0 overflow-hidden rounded-4 shadow-lg w-100" style="max-width: 960px;">
			<div class="col-lg-5 bg-dark text-white p-4 p-lg-5 d-flex flex-column justify-content-between">
				<div>
					<img src="images/pair-logo.png" alt="Pair logo" width="72" height="40" class="mb-4">
					<div class="small text-uppercase text-white-50 mb-2">Pair Boilerplate</div>
					<h1 class="h3 mb-3">Accesso amministrazione</h1>
					<p class="mb-0 text-white-50">Configurazione pulita, moduli base pronti e template compatibile con Pair attuale.</p>
				</div>
				<div class="small text-white-50 mt-4">Usa le credenziali create in installazione oppure gestite dal modulo utenti.</div>
			</div>
			<div class="col-lg-7 bg-white p-4 p-lg-5">
				<div id="messageArea"></div>
				{{content}}
			</div>
		</div>
		{{scripts}}
	</body>
</html>
