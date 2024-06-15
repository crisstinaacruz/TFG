<?php
include_once '../../../includes/config.php';
$pdo = ConnectDatabase::conectar();

$id = $_GET['id'];

$statement = $pdo->prepare("SELECT * FROM promociones WHERE promocion_id = ?");
$statement->execute([$id]);
$promociones = $statement->fetch(PDO::FETCH_ASSOC);

if ($statement->rowCount() === 0) {
    echo '<script>alert("El ID proporcionado no existe en la base de datos.");</script>';
}

$titulo = $promociones['titulo'];
$descripcion = $promociones['descripcion'];
$fecha = $promociones['fecha'];
$imagen = $promociones['imagen'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nuevaimag = '../../../uploads/promociones/' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], $nuevaimag);
        $imagen = $nuevaimag;
    }

    if (!empty($titulo) && !empty($descripcion) && !empty($fecha) && !empty($imagen)) {
        $statement = $pdo->prepare("UPDATE promociones SET titulo = ?, descripcion = ?, fecha = ?, imagen = ? WHERE promocion_id = ?");
        $statement->execute([$titulo, $descripcion, $fecha, $imagen, $id]);

        header('Location: administrador_promo.php');
        exit();
    } else {
        echo '<script>alert("Por favor, rellene todos los campos.");</script>';
    }
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
<title>Magic Cinema - Editar promociones</title>

</head>
    <body>
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
                                    <a href="administrador_promo.php" class="header__nav-link">Promociones</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="../horarios/administrador_horario.php" class="header__nav-link">Horarios</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="../bar/administrador_bar.php" class="header__nav-link">Bar</a>
                                </li>

                                <a href="administrador_promo.php" class="header__sign-in">
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
    
    <div class="container mt-5 text-white">
        <h2 class="mt-5">Editar Promociones</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-row">
                <div class="form-group mb-3 mt-5">
                    <label for="titulo">Título:</label>
                    <input type="text" class="form-control" name="titulo" value="<?php echo $titulo; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required rows="5"><?php echo $descripcion; ?></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" name="fecha" value="<?php echo $fecha; ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control" name="imagen" accept="image/*">
                    <?php echo '<img src="' . $promociones['imagen'] . '" class="img-thumbnail my-3" style="max-width: 100px;" alt="Promocion">';
                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3 mb-3">Guardar Cambios</button>
        </form>
    </div>


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