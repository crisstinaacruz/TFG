<?php

include_once '../../../includes/config.php';

class BarInsert {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function insertarBar($titulo, $precio, $imagen) {
        
        if ($this->validarDatos($titulo, $precio, $imagen)) {
            $imagen = $this->procesarImagen($imagen);
            
            $statement = $this->pdo->prepare("INSERT INTO bar (titulo, precio, imagen) VALUES (?, ?, ?)");

            $statement->execute([$titulo, $precio, $imagen]);

            return true;

        } else {
            return false;
        }
    }

    private function validarDatos($titulo, $precio, $imagen) {
        return !empty($titulo) && !empty($precio) && !empty($imagen);
    }

    private function procesarImagen($imagen) {
        $directorioDestino = "../../../uploads/bar/";
        $archivoDestino = $directorioDestino . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $archivoDestino)) {
            // Retorna la ruta del archivo si se sube exitosamente
            return $archivoDestino;
        } else {
            // Retorna null si falla la subida
            return null;
        }
    }
}

$BarInsert = new BarInsert();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];

    if ($BarInsert->insertarBar($titulo, $precio, $_FILES['imagen'])) {
        header('Location: administrador_bar.php');
        exit();

    } else {
        echo "Error en la validación de datos.";
    }

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
<title>Magic Cinema - Nuevo bar</title>

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
                                    <a href="../peliculas/administrador_pelicula.php" class="header__nav-link">Peliculas</a>
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

                                <a href="administrador_pelicula.php" class="header__sign-in">
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
        <h2 class="mb-4">Nuevo Bar</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" class="form-control" name="titulo" required>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" class="form-control" name="precio" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <!-- JS -->
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
