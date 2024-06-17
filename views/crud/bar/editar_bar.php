<?php
session_start();
include_once '../../../includes/config.php';
$pdo = ConnectDatabase::conectar();



    $bar_id = $_SESSION['bar_id'];

    $statement = $pdo->prepare("SELECT * FROM bar WHERE bar_id = ?");
    $statement->execute([$bar_id]);
    $bar = $statement->fetch(PDO::FETCH_ASSOC);

    $titulo = $bar['titulo'];
    $precio = $bar['precio'];
    $imagen = $bar['imagen'];


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = $_POST['titulo'];
        $precio = $_POST['precio'];
    
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nuevaimag = '../../../uploads/peliculas/' . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], $nuevaimag);
            $imagen = $nuevaimag;
        }
    
        if (!empty($titulo) && !empty($precio)) {
            $statement = $pdo->prepare("UPDATE bar SET titulo = ?, precio = ?, imagen = ? WHERE bar_id = ?");
            $statement->execute([$titulo, $precio, $imagen, $bar_id]);
    
            header('Location: administrador_bar.php');
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
<title>Magic Cinema - Editar bar</title>

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
                                    <a href="../promociones/administrador_promo.php" class="header__nav-link">Promociones</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="../horarios/administrador_horario.php" class="header__nav-link">Horarios</a>
                                </li>

                                <li class="header__nav-item">
                                    <a href="administrador_bar.php" class="header__nav-link">Bar</a>
                                </li>

                                <a href="administrador_bar.php" class="header__sign-in">
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
            <h2 class="mb-3 text-black">-</h2>
            <h2 class="mb-4">Editar Bar</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group mb-3">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" name="titulo" value="<?php echo $titulo; ?>" required>
                    </div>

                    <div class="form-group mb-3">
                    <label for="precio">Precio:</label>
                    <input type="text" class="form-control" name="precio" value="<?php echo $precio; ?>" required>
                    </div>

                    <div class="form-group mb-3">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control" name="imagen" accept="image/*">
                    <?php echo '<img src="' . $imagen . '" class="img-thumbnail my-3" style="max-width: 100px;" alt="Bar">';
                    ?>
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
