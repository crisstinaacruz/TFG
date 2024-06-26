<?php
session_start();

include_once '../includes/config.php';
include_once "../includes/Navbar.php";

$conexion = ConnectDatabase::conectar();

$idsButacas = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$correoUsuario = isset($_SESSION['correoUsuario']) ? $_SESSION['correoUsuario'] : '';
$bar_productos = isset($_SESSION['bar_productos']) ? $_SESSION['bar_productos'] : [];
$horario_id = isset($_SESSION['horario_id']) ? $_SESSION['horario_id'] : '';
$total = isset($_SESSION['total']) ? floatval($_SESSION['total']) : 0.00;
$precio = isset($_SESSION['precio']) ? floatval($_SESSION['precio']) : 0.00;

$precio_final = $precio + $total;
$_SESSION['precio_final'] = $precio_final;

$query_fecha = "SELECT fecha, pelicula_id FROM horarios WHERE horario_id = :horario_id";
$statement_fecha = $conexion->prepare($query_fecha);
$statement_fecha->bindParam(':horario_id', $horario_id);
$statement_fecha->execute();
$resultado_horario = $statement_fecha->fetch(PDO::FETCH_ASSOC);
$fecha_horario = $resultado_horario['fecha'];
$pelicula_id = $resultado_horario['pelicula_id'];

$fecha_formateada = date('d-m-Y', strtotime($fecha_horario));
$hora_formateada = date('H:i', strtotime($fecha_horario));

$_SESSION['fecha_formateada'] = $fecha_formateada;
$_SESSION['hora_formateada'] = $hora_formateada;

$query_pelicula = "SELECT titulo FROM peliculas WHERE pelicula_id = :pelicula_id";
$statement_pelicula = $conexion->prepare($query_pelicula);
$statement_pelicula->bindParam(':pelicula_id', $pelicula_id);
$statement_pelicula->execute();
$titulo_pelicula = $statement_pelicula->fetch(PDO::FETCH_ASSOC)['titulo'];

$_SESSION['titulo_pelicula'] = $titulo_pelicula;

if (isset($_POST['pagar'])) {
    header('Location: redsys.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <link rel="icon" type="image/png" href="../../../assets/icon/icono.png" sizes="32x32">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema - Resumen de Compra</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        .card-custom {
            background-color: transparent;
            border: 1px solid #fff;
            color: #fff;
            margin-bottom: 20px;
        }
        .card-custom h3, .card-custom p, .card-custom ul, .card-custom li {
            color: #fff;
        }
        .btn-custom {
            background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .gradient-text {
            background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
    <section class="home">
        <div class="owl-carousel home__bg">
            <div class="item home__cover" data-bg="../assets/img/home/home__bg4.jpg"></div>
        </div>
    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content__title text-center"><b>Resumen de compra</b></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-center">
            <div class="col-md-6 mt-3">
                
                <div class="card card-custom p-4">
                <h3 class="card-title gradient-text">Entradas</h3>
                <p style="font-size: larger;">Película: <b><?php echo $titulo_pelicula; ?></b></p>
                <ul>
                        <?php
                        $butacas = explode(",", $idsButacas);
                        foreach ($butacas as $butaca) {
                            $query = "SELECT salas.nombre AS sala, asientos.fila, asientos.columna 
                                        FROM asientos 
                                        INNER JOIN salas ON asientos.sala_id = salas.sala_id 
                                        WHERE asiento_id = :butaca";
                            $statement = $conexion->prepare($query);
                            $statement->bindParam(':butaca', $butaca);
                            $statement->execute();
                            $butaca_info = $statement->fetch(PDO::FETCH_ASSOC);
                            echo "<li>" . $butaca_info['sala'] . " - Fila: " . $butaca_info['fila'] . ", Columna: " . $butaca_info['columna'] . "</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="card card-custom p-4">
                    <h3 class="card-title gradient-text">Fecha y hora</h3>
                    <p><?php echo $fecha_formateada . ' a las ' . $hora_formateada; ?></p>
                </div>

                <div class="card card-custom p-4">
                    <h3 class="card-title gradient-text">Productos del bar</h3>
                    <ul>
                        <?php
                        if (empty($bar_productos)) {
                            echo "<li>Sin complementos del bar</li>";
                        } else {
                            foreach ($bar_productos as $producto) {
                                $query_bar = "SELECT titulo FROM bar WHERE bar_id = :bar_id";
                                $statement_bar = $conexion->prepare($query_bar);
                                $statement_bar->bindParam(':bar_id', $producto['id']);
                                $statement_bar->execute();
                                $titulo_bar = $statement_bar->fetch(PDO::FETCH_ASSOC)['titulo'];
                                echo "<li>" . $titulo_bar . " x " . $producto['cantidad'] . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
       
                <div class="card card-custom p-4">
                    <h3 class="card-title gradient-text">Correo electrónico</h3>
                    <p><?php echo $correoUsuario; ?></p>
                </div>
                <div class="card card-custom p-2">
                    <h2 class="card-title text-center">Total a pagar: <b><?php echo $precio_final; ?> €</b></h2>
                </div>

                <form method="POST" class="text-center">
                    <button class="btn btn-custom btn-lg" type="submit" name="pagar">Pagar</button>
                </form>
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
