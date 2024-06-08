<?php
session_start();

try {
    
    include_once '../includes/config.php';

    $pdo = ConnectDatabase::conectar();

    // Incluir el archivo con la lógica de promociones
    require_once('../includes/promociones.php');

    // Obtener las promociones
    $promociones = PromocionesHandler::obtenerPromociones($pdo);

    // Generar el HTML de las tarjetas
    $tarjetasHTML = PromocionesHandler::generarTarjetasHTML($promociones);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- CSS -->
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

    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="body">
    <?php
    include_once "../includes/Navbar.php";


    // Verifica si el usuario está autenticado
    if (isset($_SESSION["email"])) {
        Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
    } else {
        Navbar::renderUnauthenticatedNavbar();
    }


    ?>
    <!-- home -->
    <section class="home">
        <!-- home bg -->
        <div class="owl-carousel home__bg">
            <div class="item home__cover" data-bg="../assets/img/home/home__bg.jpg"></div>
            <div class="item home__cover" data-bg="../assets/img/home/home__bg2.jpg"></div>
            <div class="item home__cover" data-bg="../assets/img/home/home__bg3.jpg"></div>
            <div class="item home__cover" data-bg="../assets/img/home/home__bg4.jpg"></div>
        </div>
        <!-- end home bg -->
    </section>

    <?php echo $tarjetasHTML; ?>
    <!-- end content -->


    <?php
    include_once "../includes/footer.php";
    echo getFooterHTML();
    ?>

    <!-- JS -->
    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script src="../assets/assets/js/jquery.mousewheel.min.js"></script>
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