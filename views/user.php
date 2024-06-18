<?php
session_start();


if (!empty($_SESSION["usuario_id"])) {
    try {
        include_once '../includes/config.php';
        $conexion = ConnectDatabase::conectar();

        $consulta = $conexion->prepare("SELECT nombre, apellidos, email FROM usuarios WHERE usuario_id = :usuario_id");
        $consulta->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_STR); 
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $nombreUsuario = $resultado['nombre'];
            $apellidosUsuario = $resultado['apellidos'];
            $correoUser = $resultado['email'];
        } else {
            echo "No se encontraron datos para el usuario.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conexion = null;
    }
} else {
    echo "No ha iniciado sesión.";
    header("Location: Login.php");
    exit();
}
?>


?>
<!DOCTYPE html>
<html lang="en">

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


    <link rel="icon" type="image/png" href="icon/favicon-32x32.png" sizes="32x32">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema - Perfil</title>

</head>

<body>
    <section class="home">

        <div class="owl-carousel home__bg">
            <div class="item home__cover" data-bg="../assets/img/home/home__bg.jpg"></div>

        </div>

    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">

                        <h2 class="content__title">Perfil de <?php echo $correoUser; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">

            <form action="../index.php" method="post" enctype="multipart/form-data" class="sign__form">
                <div class="sign__group">
                    <input style="cursor: not-allowed; " type="text" id="nombre" name="nombre" class="sign__input" placeholder="Nombre" value="<?php echo $nombreUsuario; ?>" readonly required>
                </div>

                <div class="sign__group">
                    <input style="cursor: not-allowed;" type="text" id="apellidos" name="apellidos" class="sign__input" placeholder="Apellidos" value="<?php echo $apellidosUsuario; ?>" readonly required>
                </div>
                <div class="sign__group">
                    <input style="cursor: not-allowed;" type="email" id="correo" name="correo" class="sign__input" placeholder="Correo Electronico" value="<?php echo $correoUser; ?>" readonly required>
                </div>
                <button class="" style="font-family: 'Open Sans', sans-serif; background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">Volver</button>

            </form>
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