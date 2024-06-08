<?php
session_start(); // Asegúrate de que la sesión esté iniciada

if (!empty($_SESSION["usuario"])) {
    try {
        // Conecta con la base de datos utilizando tu clase de conexión
        include_once '../includes/config.php';
        $conexion = ConnectDatabase::conectar();

        // Prepara y ejecuta la consulta SQL para obtener los datos del usuario
        $consulta = $conexion->prepare("SELECT nombre, apellidos, email FROM usuarios WHERE email = :email");
        $consulta->bindParam(':email', $_SESSION['usuario'], PDO::PARAM_STR); // Ajusta según el campo de la sesión que contiene el email
        $consulta->execute();

        // Obtiene los resultados
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Asigna los datos del usuario a las variables
            $nombreUsuario = $resultado['nombre'];
            $apellidosUsuario = $resultado['apellidos'];
            $correoUsuario = $resultado['email'];
        } else {
            echo "No se encontraron datos para el usuario.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Cierra la conexión
        $conexion = null;
    }
} else {
    echo "No ha iniciado sesión.";
    header("Location: Login.php"); // Redirige a la página de inicio de sesión
    exit();
}
?>


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="icon/favicon-32x32.png" sizes="32x32">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema - Perfil</title>

</head>

<body>
    <section class="home">
        <!-- home bg -->
        <div class="owl-carousel home__bg">
            <div class="item home__cover" data-bg="../assets/img/home/home__bg.jpg"></div>

        </div>
        <!-- end home bg -->
    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- content title -->
                        <h2 class="content__title">Perfil de <?php echo $_SESSION["usuario"]; ?></h2>
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
                    <input style="cursor: not-allowed;" type="email" id="correo" name="correo" class="sign__input" placeholder="Correo Electronico" value="<?php echo $correoUsuario; ?>" readonly required>
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