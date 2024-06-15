

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

    <link rel="icon" type="image/png" href="../assets/icon/icono.png" sizes="32x32">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema - Compra Finalizada</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<header class="header">
            <div class="header__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="header__content">

                                <a href="../../index.php" class="header__logo">
                                    <img src="../../assets/img/Magic_Cinema-removebg-preview.png" alt="">
                                </a>

                                <ul class="header__nav">
                                    <li class="header__nav-item">
                                        <a href="../../views/cartelera.php" class="header__nav-link">Cartelera</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/promociones.php" class="header__nav-link">Promociones</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/experiencias.php" class="header__nav-link">Experiencias</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/contactanos.php" class="header__nav-link">Contáctanos</a>
                                    </li>
                                </ul>

                                <div class="header__auth">
                                    <a href="../../views/Login.php" class="header__sign-in mx-1">
                                        <i class="icon ion-ios-log-in"></i>
                                        <span>Iniciar Sesión</span>
                                    </a>
                                </div>

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
                        <h2 class="content__title">Compra Finalizada con Éxito</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <h3 style="color:#fff; font-family: 'Open Sans', sans-serif; margin-top: 50px;">¡Gracias por su compra!</h3>
            <p style="color:#fff; font-family: 'Open Sans', sans-serif;">Su compra ha sido finalizada con éxito. Hemos enviado un correo electrónico con los detalles de su compra.</p>
            <a href="/../../index.php">
                <button style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;" class="btn btn-primary">Volver a la página principal</button>
            </a>
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
