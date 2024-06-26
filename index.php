<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<link rel="stylesheet" href="assets/css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="assets/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
	<link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css">
	<link rel="stylesheet" href="assets/css/nouislider.min.css">
	<link rel="stylesheet" href="assets/css/ionicons.min.css">
	<link rel="stylesheet" href="assets/css/plyr.css">
	<link rel="stylesheet" href="assets/css/photoswipe.css">
	<link rel="stylesheet" href="assets/css/default-skin.css">
	<link rel="stylesheet" href="assets/css/main.css">

	<link rel="icon" type="image/png" href="assets/icon/icono.png" sizes="32x32">


	<meta name="description" content="">
	<meta name="keywords" content="">
	<title>Magic Cinema</title>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="body">

	<?php
	include_once "includes/Navbar.php";
	include_once 'includes/indexFunctions.php';


	if (isset($_SESSION["email"])) {
		Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
	} else {
		Navbar::renderUnauthenticatedNavbar();
	}


	IndexPeliculaHandler::mostrarTopPeliculas();
	?>


	<section class="content">
		<div class="content__head">
			<div class="container">
				<div class="row">
					<div class="col-12">

						<h2 class="content__title">Ofertas y sorteos</h2>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="card my-5 border border-0" style="max-width: 1040px; background-color: transparent;">
				<div class="row g-0">
					<div class="col-md-4">
						<img src="assets/img/wish-el-poder-de-los-deseos-sorteo-viaje-disney.jpg" class="img-fluid rounded-start" alt="...">
					</div>
					<div class="col-md-8">
						<div class="card-body">
							<h5 class="card-title fw-bold" style="color:#fff; font-family: 'Open Sans', sans-serif;">¡Todos tus deseos con este viaje lleno de magia!</h5>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">Compra ya tu entrada online y haz tus sueños realidad ganando este viaje a Disneyland® Paris</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">Participa en el sorteo con "Wish: El poder de los deseos" y podrás conseguir un viaje en familia al lugar donde todos los deseos se hacen realidad.</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">El sorteo termina el: 26/07/2024</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card my-5 border border-0" style="max-width: 1040px; background-color: transparent;">
				<div class="row g-0">
					<div class="col-md-4">
						<img src="assets/img/ofertas-wonka-cinesa.jpg" class="img-fluid rounded-start" alt="...">
					</div>
					<div class="col-md-8">
						<div class="card-body">
							<h5 class="card-title fw-bold" style="color:#fff; font-family: 'Open Sans', sans-serif;">¡Consigue varios premios inspirados en la película!</h5>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">Compra ya tus entradas online para ver "Wonka" y consigue todos estos premios</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">¿Te vienes de viaje por el mundo con el joven Willy Wonka? Sé de los primeros en conocer la historia del personaje más emblemático de Roald Dahl. ¡Y consigue varios premios inspirados en la película!</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">La película se estrena el: 30/06/2024</p>
						</div>
					</div>
				</div>
			</div>
			<div class="card my-5 border border-0" style="max-width: 1040px; background-color: transparent;">
				<div class="row g-0">
					<div class="col-md-4">
						<img src="assets/img/ofertas-los-juegos-del-hambre-cinesa.jpg" class="img-fluid rounded-start" alt="...">
					</div>
					<div class="col-md-8">
						<div class="card-body">
							<h5 class="card-title fw-bold" style="color:#fff; font-family: 'Open Sans', sans-serif;">¡Consigue varios premios inspirados en la película!</h5>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">Compra ya tus entradas online para ver "Los Juegos del Hambre: Balada de pájaros cantores y serpientes" y consigue todos estos premios.</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">¿Te atreves a seguir de cerca los primeros Juegos? Viaja al Distrito 12 de hace 65 años a través de la gran pantalla. ¡Y consigue varios premios inspirados en la película!</p>
							<p class="card-text" style="color:#fff; font-family: 'Open Sans', sans-serif;">La película se estrena el: 01/07/2024</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="content__head">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h2 class="content__title">PELÍCULAS EN VERSIÓN ORIGINAL</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="card text-bg-dark">
				<img src="assets/img/peliculas-version-original-cinesa.png" class="card-img" alt="...">
				<div class="card-img-overlay my-5">
					<h5 class="card-title fw-bold" style="font-family: 'Open Sans', sans-serif;">¿Prefieres ver las películas en versión original?</h5>
					<p class="card-text" style="font-family: 'Open Sans', sans-serif;">The Marvels, Los Juegos del Hambre, Five Nights at Freddy's... En Magic Cinema, encontrarás tus títulos preferidos también en Versión Original Subtitulada al Español.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="content__head">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h2 class="content__title">DESCUBRE NUESTRAS SALAS PREMIUM</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="card text-bg-dark">
				<img src="assets/img/banner-plf-1324x420.jpg" class="card-img" alt="...">
				<div class="card-img-overlay my-5">
					<h5 class="card-title fw-bold" style="font-family: 'Open Sans', sans-serif;">Salas Premium</h5>
					<p class="card-text text-start" style="font-family: 'Open Sans', sans-serif;">IMAX, ISENSE, DBOX, SCREENX... Conoce todos los detalles de las salas de cine premium más exclusivas. Todo a tu alcance para hacer de tu visita al cine una experiencia única.</p>
				</div>
			</div>
		</div>
	</section>

	<?php
	include_once "includes/footer.php";
	echo getFooterHTML();
	?>

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	<script src="assets/assets/js/jquery.mousewheel.min.js"></script>
	<script src="assets/js/jquery.mCustomScrollbar.min.js"></script>
	<script src="assets/js/wNumb.js"></script>
	<script src="assets/js/nouislider.min.js"></script>
	<script src="assets/js/plyr.min.js"></script>
	<script src="assets/js/jquery.morelines.min.js"></script>
	<script src="assets/js/photoswipe.min.js"></script>
	<script src="assets/js/photoswipe-ui-default.min.js"></script>
	<script src="assets/js/main.js"></script>
</body>

</html>