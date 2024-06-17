<?php
session_start();
include_once '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="../assets/css/nouislider.min.css">
    <link rel="stylesheet" href="../assets/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/plyr.css">
    <link rel="stylesheet" href="../assets/css/photoswipe.css">
    <link rel="stylesheet" href="../assets/css/default-skin.css">
    <link rel="stylesheet" href="../assets/css/main.css">

    <link rel="icon" type="image/png" href="../assets/icon/icono.png" sizes="32x32">

    <title>Magic Cinema</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="body">

<?php
    include_once "../includes/Navbar.php";

    if (isset($_SESSION["email"])) {
        Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
    } else {
        Navbar::renderUnauthenticatedNavbar();
    }
?>

<section class="home">
    <div class="owl-carousel home__bg">
        <div class="item home__cover" data-bg="../assets/img/home/home__bg2.jpg"></div>
        <div class="item home__cover" data-bg="../assets/img/home/home__bg3.jpg"></div>
        <div class="item home__cover" data-bg="../assets/img/home/home__bg4.jpg"></div>
    </div>
</section>

<section class="content">
    <div class="content__head">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <h2 class="content__title">Disfruta de las mejores experiencias en Magic Cinema</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <p style="color: #fff; font-family: 'Open Sans', sans-serif;">
                            Conoce todos los detalles de las salas de cine premium más exclusivas y adéntrate en los lujosos MAGIC CINEMA LUXE. Maravíllate con la gran oferta de sesiones en versión original y déjate mimar por los productos de bar más selectos. Todo a tu alcance para hacer de tu visita al cine una experiencia única.
                        </p>
                        <h3 style="color: #fff; font-family: 'Open Sans', sans-serif;">¿CONOCES TODAS LAS SALAS PREMIUM?</h3>
                        <img src="../assets/img/isense.jpg" class="card-img mt-3 mb-3" alt="...">
                        <h4 style="color: #fff; font-family: 'Open Sans', sans-serif;">ISENSE</h4>
                        <p style="color: #fff; font-family: 'Open Sans', sans-serif;">
                            El sonido envolvente de estas salas, una pantalla extragrande, proyector full HD y sus butacas XXL te harán sentir más parte de la película que nunca.
                        </p>

                        <img src="../assets/img/imax.jpg" class="card-img mt-3 mb-3" alt="...">
                        <h4 style="color: #fff; font-family: 'Open Sans', sans-serif;">IMAX</h4>
                        <p style="color: #fff; font-family: 'Open Sans', sans-serif;">
                            En IMAX, desde la película hasta la tecnología y el diseño de las salas se han desarrollado y adaptado para hacerte creer que eres uno de los protagonistas.
                        </p>

                        <img src="../assets/img/screen.jpg" class="card-img mt-3 mb-3" alt="...">
                        <h4 style="color: #fff; font-family: 'Open Sans', sans-serif;">SCREEN X</h4>
                        <p style="color: #fff; font-family: 'Open Sans', sans-serif;">
                            La primera plataforma cinematográfica inmersiva que ofrece a los espectadores una experiencia de visualización de 270 grados.
                        </p>
                        <img src="../assets/img/dbox.jpg" class="card-img mt-3 mb-3" alt="...">
                        <h4 style="color: #fff; font-family: 'Open Sans', sans-serif;">DBOX</h4>
                        <p style="color: #fff; font-family: 'Open Sans', sans-serif;">
                            Prepárate para sentir nuevas emociones y déjate llevar por la inmersión y el movimiento de las butacas DBOX. Una experiencia única.
                        </p>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once "../includes/footer.php";
echo getFooterHTML();
?> 

<script src="../assets/js/jquery-3.3.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/owl.carousel.min.js"></script>
<script src="../assets/js/jquery.mousewheel.min.js"></script>
<script src="../assets/js/jquery.mCustomScrollbar.min.js"></script>
<script src="../assets/js/wNumb.js"></script>
<script src="../assets/js/nouislider.min.js"></script>
<script src="../assets/js/plyr.min.js"></script>
<script src="../assets/js/jquery.morelines.min.js"></script>
<script src="../assets/js/photoswipe.min.js"></script>
<script src="../assets/js/photoswipe-ui-default.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>

</html>
