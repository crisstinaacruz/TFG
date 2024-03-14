<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class Register
{
    private $conexion;

    public function __construct()
    {
        include_once "../includes/config.php";
        $this->conexion = ConnectDatabase::conectar();
    }

    public function verificarUsuarioExistente($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE Nombre_usuario = :user";
        $resultado = $this->conexion->prepare($sql);

        $usuario = htmlentities(addslashes(trim($usuario)));
        $resultado->bindValue(":user", $usuario, PDO::PARAM_STR);
        $resultado->execute();

        return $resultado->rowCount() == 1;
    }

    public function insertarUsuario($name, $lastname, $usuario, $contrasenia, $email)
    {
        $pass_cifrado = password_hash($contrasenia, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, Nombre_usuario, Contrasena, Correo_electronico) VALUES (:nombre, :lastname, :user, :password, :email)";
        $resultado = $this->conexion->prepare($sql);


        $resultado->execute(array(":nombre" => $name, ":lastname" => $lastname, ":user" => $usuario, ":password" => $pass_cifrado, ":email" => $email));
        if ($resultado->rowCount() > 0) {
            // Si la inserción fue exitosa, envía el correo electrónico de confirmación
            $this->enviarCorreoConfirmacion($email);
        }
        return $resultado->rowCount() > 0;
    }

    public function enviarCorreoConfirmacion($email)
    {
        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@magiccinema.es';
            $mail->Password = 'MagicCinema2023*';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('no-reply@magiccinema.es', 'no-reply@magiccinema.es');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirmación de Registro';
            $mail->Body = 'Gracias por registrarte en nuestro sitio. Tu cuenta ha sido creada con éxito.';

            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo de confirmación: {$mail->ErrorInfo}";
        }
    }


    public function redireccionar($ruta)
    {
        header("location: $ruta");
        exit;
    }

    public function error($e)
    {
        echo "Línea del error: " . $e->getLine();
        echo  "<p>" . $e->getMessage() . "</p>";
    }

    public function __destruct()
    {
        if ($this->conexion) {
            $this->conexion = null;
        }
    }
}

try {
    $registro_usuario = new register();
    $name = $_POST["name"];
    $lastname = $_POST["lastname"];
    $usuario_input = $_POST["user"];
    $contrasenia_input = $_POST["password"];
    $email_input = $_POST["email"];


    if ($registro_usuario->verificarUsuarioExistente($usuario_input)) {

        $registro_usuario->redireccionar("mensajes/usuario_existe.php");
    } else {

        $registro_usuario->insertarUsuario($name, $lastname, $usuario_input, $contrasenia_input, $email_input);
        $registro_usuario->redireccionar("html/FormLogin.html");
    }
} catch (Exception $e) {
    $registro_usuario->error($e);
}
?> 
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/dist/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/dist/css/themes/default.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/dist/alertify.min.js"></script>

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

	<!-- CSS -->
	<link rel="stylesheet" href="../../assets/css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="../../assets/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="../../assets/css/owl.carousel.min.css">
	<link rel="stylesheet" href="../../assets/css/jquery.mCustomScrollbar.min.css">
	<link rel="stylesheet" href="../../assets/css/nouislider.min.css">
	<link rel="stylesheet" href="../../assets/css/ionicons.min.css">
	<link rel="stylesheet" href="../../assets/css/plyr.css">
	<link rel="stylesheet" href="../../assets/css/photoswipe.css">
	<link rel="stylesheet" href="../../assets/css/default-skin.css">
	<link rel="stylesheet" href="../../assets/css/main.css">

	<!-- Favicons -->
	<link rel="icon" type="image/png" href="../../assets/icon/favicon-32x32.png" sizes="32x32">
	<link rel="apple-touch-icon" href="../../assets/icon/favicon-32x32.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../../assets/icon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../../assets/icon/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="144x144" href="../../assets/icon/apple-touch-icon-144x144.png">

	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Dmitry Volkov">
	<title>Magic Cinema - Registro</title>

</head>

<body class="body">
	<div class="sign section--bg" data-bg="../../assets/img/section/section.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="sign__content">
						<form action="../Register.php" method="post" class="sign__form" onsubmit="return validar()">
							<a href="../../index.php" class="sign__logo">
								<img src="../../assets/img/logo.png" alt="">
							</a>
							<div class="sign__group">
								<input type="text" name="name" class="sign__input" placeholder="Nombre" required>
							</div>
							<div class="sign__group">
								<input type="text" name="lastname" class="sign__input" placeholder="Apellidos" required>
							</div>

							<div class="sign__group">
								<input type="text" name="user" class="sign__input" placeholder="Nombre de usuario" required>
							</div>

							<div class="sign__group">
								<input type="email" name="email" class="sign__input" placeholder="Correo electrónico" required>
							</div>

							<div class="sign__group">
								<input type="password" name="password" id="password" class="sign__input" placeholder="Contraseña" required>
							</div>
														
							<div class="sign__group">
								<input type="password" name="password" id="confirm_password" class="sign__input" placeholder="Confirmar Contraseña" required>
							</div>

							<div class="sign__group sign__group--checkbox">
								<input id="check" name="check" type="checkbox" checked="checked" required >
								<label for="check">Acepto las <a href="politicas.html">políticas de
										privacidad</a></label>
							</div>

							<button class="sign__btn" type="submit" onclick="validateFormChek()">Regístrate ahora</button>

							<span class="sign__text">¿Ya tienes una cuenta? <a 
								href="FormLogin.html">¡Iniciar sesión!</a></span>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
        function validateFormChek() {
            var checkbox = document.getElementById('check');

            if (!checkbox.checked) {
                alert('Debes aceptar los términos y condiciones');
                return false;
            }
        }
        function validar(){

            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            // Verificar si las contraseñas coinciden
            if (password !== confirm_password) {
                alert("Las contraseñas no coinciden");
                return false;
            }

            // Verificar la complejidad de la contraseña
            var passwordRegex = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-zA-Z]).{6,20}$/;
            if (!passwordRegex.test(password)) {
                alert("La contraseña debe tener de 6 a 13 letras, un caracter especial y al menos un número.");
                return false;
            }

            return true; // Permite el envío del formulario si todas las validaciones son exitosas
        }
    </script>

	<!-- JS -->
	<script src="../../assets/js/jquery-3.3.1.min.js"></script>
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../../assets/js/owl.carousel.min.js"></script>
	<script src="../../assets/js/jquery.mousewheel.min.js"></script>
	<script src="../../assets/js/jquery.mCustomScrollbar.min.js"></script>
	<script src="../../assets/js/wNumb.js"></script>
	<script src="../../assets/js/nouislider.min.js"></script>
	<script src="../../assets/js/plyr.min.js"></script>
	<script src="../../assets/js/jquery.morelines.min.js"></script>
	<script src="../../assets/js/photoswipe.min.js"></script>
	<script src="../../assets/js/photoswipe-ui-default.min.js"></script>
	<script src="../../assets/js/main.js"></script>
</body>

</html>