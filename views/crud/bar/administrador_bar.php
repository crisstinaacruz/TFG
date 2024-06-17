<?php
session_start();
include_once '../../../includes/config.php';
$pdo = ConnectDatabase::conectar();

$statement = $pdo->prepare("SELECT * FROM bar ORDER BY bar_id");
$statement->execute();
$resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['editar'])) {
    $bar_id = $_POST['editar'];
    $_SESSION['bar_id'] = $bar_id;
    header('Location: editar_bar.php');
    exit();
} else if (isset($_POST['eliminar'])) {
    $bar_id = $_POST['eliminar'];
    $_SESSION['bar_id'] = $bar_id;
    header('Location: delete_bar.php');
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

<link rel="stylesheet" href="../../../assets/css/bootstrap-reboot.min.css">
<link rel="stylesheet" href="../../../assets/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="../../../assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="../../../assets/css/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="../../../assets/css/nouislider.min.css">
<link rel="stylesheet" href="../../../assets/css/ionicons.min.css">
<link rel="stylesheet" href="../../../assets/css/plyr.css">
<link rel="stylesheet" href="../../../assets/css/photoswipe.css">
<link rel="stylesheet" href="../../../assets/css/default-skin.css">
<link rel="stylesheet" href="../../../assets/css/main.css">
<link rel="icon" type="image/png" href="../../../assets/icon/icono.png" sizes="32x32">
<meta name="description" content="">
<meta name="keywords" content="">
<title>Magic Cinema - Administrador</title>

</head>

<body class="body">

    <header class="header">
        <div class="header__wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header__content">
                            <a href="../../../index.php" class="header__logo">
                                <img src="../../../assets/img/Magic_Cinema-removebg-preview.png" alt="">
                            </a>

                            <ul class="header__nav">
                                <li class="header__nav-item">
                                    <a href="../peliculas/administrador_pelicula.php" class="header__nav-link">Películas</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="../promociones/administrador_promo.php" class="header__nav-link">Promociones</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="../horarios/administrador_horario.php" class="header__nav-link">Horarios</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="administrador_bar.php" class="header__nav-link">Bar</a>
                                </li>

                                <a href="../../../index.php" class="header__sign-in">
                                    <i class="icon ion-ios-log-in"></i>
                                    <span>Volver</span>
                                </a>
                            </ul>

                            <button class="header__btn" type="button">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

		<section class="section section--first section--bg">
			<div class="owl-carousel home__bg">
				<div class="item home__cover" data-bg="../../../assets/img/home/home__bg.jpg"></div>
				<div class="item home__cover" data-bg="../../../assets/img/home/home__bg2.jpg"></div>
				<div class="item home__cover" data-bg="../../../assets/img/home/home__bg3.jpg"></div>
				<div class="item home__cover" data-bg="../../../assets/img/home/home__bg4.jpg"></div>
			</div>
		</section>

    <div class="contain mt-5 mx-5">
    <a href="form_añadir_bar.php" class="btn btn-primary btn-sm mt-3">Añadir nuevo producto al bar</a>
    <table class="table table-striped table-bordered mt-4 w-100">
        <thead class="thead-dark">
            <tr>
                <th>Título</th>
                <th>Precio</th>  
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($resultados as $bar) {
                    echo '<tr>';
                    echo '<td>' . $bar['titulo'] . '</td>';
                    echo '<td>' . $bar['precio'] . '</td>';
                    echo '<td><img src="'. $bar['imagen'] . '" class="img-thumbnail" style="max-width: 100px;" alt="Película"></td>';
                    echo '<td>
                    <form method="post">
                    <button type="submit" class="btn btn-warning btn-sm mt-3" value="' . $bar['bar_id'] . '" name="editar">Editar</button>
                    <button type="submit" class="btn btn-danger btn-sm mt-3" value="' . $bar['bar_id'] . '" name="eliminar">Eliminar</button>
                    </form>
                          </td>';
                    echo '</tr>';
                }

                
                ?>
        </tbody>
    </table>
    </div>
    <?php
    include_once "../../../includes/footer.php";
    echo getFooterHTML();
    ?>
    <script src="../../../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/owl.carousel.min.js"></script>
    <script src="../../../assets/assets/js/jquery.mousewheel.min.js"></script>
    <script src="../../../assets/js/jquery.mCustomScrollbar.min.js"></script>
    <script src="../../../assets/js/wNumb.js"></script>
    <script src="../../../assets/js/nouislider.min.js"></script>
    <script src="../../../assets/js/plyr.min.js"></script>
    <script src="../../../assets/js/jquery.morelines.min.js"></script>
    <script src="../../../assets/js/photoswipe.min.js"></script>
    <script src="../../../assets/js/photoswipe-ui-default.min.js"></script>
    <script src="../../../assets/js/main.js"></script>

</body>

</html>