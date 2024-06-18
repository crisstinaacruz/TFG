<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include_once "../includes/config.php";
    $conexion = ConnectDatabase::conectar();


    if ($conexion) {

        $email = $_POST["email"];
        $verificationCode = $_POST["verificationCode"];

        
        $sql = "SELECT * FROM check_codes WHERE email = :email AND codigo = :code AND expira_en > NOW()";
        $statement = $conexion->prepare($sql);
        $statement->bindValue(":email", $email, PDO::PARAM_STR);
        $statement->bindValue(":code", $verificationCode, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) {
           
            $_SESSION["email"] = $email;

            header("Location: ../index.php");
            exit();
        } else {
           
            echo "<script>alert('El código de verificación es inválido o ha expirado. Por favor, inténtelo de nuevo.');</script>";
        }

        
        $conexion = null;
    } else {
    
        echo "<script>alert('Error al conectar a la base de datos. Por favor, inténtelo de nuevo más tarde.');</script>";
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
    <title>Magic Cinema - Código de verificación</title>
</head>

<body class="body">
    <?php
    if (isset($_SESSION["email"])) {
        $email = $_SESSION['email'];
        echo '
    <div class="sign section--bg" data-bg="../assets/img/section/section.jpg">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sign__content">
                        <form id="verificationForm" method="post" class="sign__form" onsubmit="return validateForm()">
                            <a href="../../index.php" class="sign__logo">
                                <img src="../../assets/img/logo.png" alt="">
                            </a>

                            <h4 style="color: #fff; font-family: \'Open Sans\', sans-serif;" class="mb-5">Código de verificación</h4>

                            <!-- Campo oculto para almacenar el ID de usuario -->
                            <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">

                            <div class="sign__group">
                                <!-- Único código -->
                                <input type="text" id="verificationCode" name="verificationCode" class="sign__input" placeholder="Código de Verificación" required>
                            </div>

                            <button class="sign__btn" type="submit">Enviar</button>

                            <div id="countdown" style="color: #fff; font-family: \'Open Sans\', sans-serif;" class="my-4">Tiempo restante: 1:00</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    } else {

        echo "Error: ID de usuario no encontrado en la URL.";
    }

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

    <script>
    function validateForm() {
        var verificationCode = document.getElementById('verificationCode').value;

        if (verificationCode.length !== 6) {
            alert('El código debe ser de 6 carácteres.');
            return false;
        }

        return true;
    }

   
    var countdownElement = document.getElementById('countdown');
    var countdown = 50;

    function updateCountdown() {
        var minutes = Math.floor(countdown / 60);
        var seconds = countdown % 60;

        countdownElement.textContent = 'Tiempo restante: ' + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

        if (countdown > 0) {
            countdown--;
            setTimeout(updateCountdown, 1000);
        } else {
            countdownElement.textContent = 'Tiempo agotado';
           
            window.location.href = 'Login.php'; 
        }
    }


    updateCountdown();
</script>

</body>

</html>