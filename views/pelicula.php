<?php
session_start();
include_once '../includes/peliculaFunctions.php';
include_once '../includes/config.php';


$id_pelicula = $_SESSION['id_pelicula'];

if (!isset($_SESSION['id_pelicula']) || $_SESSION['id_pelicula'] === 0) {
    header("Location: cartelera.php");
    exit();
}

$conexion = ConnectDatabase::conectar();

$sql = "SELECT COUNT(*) FROM peliculas WHERE pelicula_id = :id";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id', $id_pelicula, PDO::PARAM_INT);
$stmt->execute();

$pelicula_existe = $stmt->fetchColumn();

if (!$pelicula_existe) {
    header("Location: cartelera.php");
    exit();
}
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

    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        h3{
            color: white;
        }
    </style>
</head>

<body>

<?php
    include_once "../includes/Navbar.php";

    if (isset($_SESSION["email"])) {
        Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
    } else {
        Navbar::renderUnauthenticatedNavbar();
    }
 ?>


    <section class="section details">
        <div class="details__bg" data-bg="img/home/home__bg.jpg"></div>

    <?php
        if ($pelicula_existe) {
            echo InfoPeliculaHandler::obtenerInformacionPelicula($id_pelicula);
        }
    ?>

        </div>
        </div>
        </div>

    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content__title">Comprar entrada</h2>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo InfoPeliculaHandler::obtenerInformacionPeliculaEntrada($id_pelicula);
        ?>
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