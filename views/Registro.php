<?php
include_once "../includes/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conexion = ConnectDatabase::conectar();

require '../vendor/autoload.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Verificar si el usuario ya existe
        $sql_verificar = "SELECT * FROM usuarios WHERE email = :email";
        $resultado_verificar = $conexion->prepare($sql_verificar);
        $resultado_verificar->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
        $resultado_verificar->execute();

        if ($resultado_verificar->rowCount() > 0) {
            echo "<script>alert('El usuario ya existe.');</script>";
        }

        // Si el usuario no existe, insertarlo en la base de datos
        $sql_insertar = "INSERT INTO usuarios (nombre, apellidos, password, email) VALUES (:nombre, :lastname, :password, :email)";
        $resultado_insertar = $conexion->prepare($sql_insertar);

        $pass_cifrado = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $resultado_insertar->execute(array(":nombre" => $_POST["name"], ":lastname" => $_POST["lastname"], ":password" => $pass_cifrado, ":email" => $_POST["email"]));

        // Llamar a la función enviarCorreoConfirmacion después de que el usuario se haya registrado exitosamente
        $registro_exitoso = $resultado_insertar->rowCount() > 0;
        if ($registro_exitoso) {
            // Llamar a la función enviarCorreoConfirmacion para enviar el correo de confirmación
            enviarCorreoConfirmacion($_POST["email"]);
        }

        // Redirigir al usuario a la página de inicio de sesión después del registro
        header("Location: Login.php");
        exit;
    } catch (Exception $e) {
        // Mostrar error en un alert
        echo "<script>alert('Error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.');</script>";
    }
}

// Función para enviar el correo de confirmación
function enviarCorreoConfirmacion($email) {
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

        $mail->setFrom('no-reply@magiccinema.es', 'Magic Cinema');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirmación de Registro';

        $mail->Body = '
        <html>
        <head>
            <style>
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: auto;
                    font-family: Arial, sans-serif;
                    color: #333;
                    background-color: #f7f7f7;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #4CAF50;
                    color: white;
                    text-align: center;
                    padding: 10px 0;
                    border-radius: 10px 10px 0 0;
                }
                .content {
                    margin: 20px 0;
                    text-align: center;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #777;
                }
                .button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 20px 0;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>Magic Cinema</h1>
                </div>
                <div class="content">
                    <h2>Bienvenido a Magic Cinema</h2>
                    <p>Gracias por registrarte en nuestro sitio. Tu cuenta ha sido creada con éxito.</p>
                    <p>Ahora puedes disfrutar de todas las ventajas y novedades que ofrecemos.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2023 Magic Cinema. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo de confirmación. Por favor, inténtalo de nuevo más tarde.');</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
	<link rel="icon" type="image/png" href="../assets/icon/icono.png" sizes="32x32">

	<meta name="description" content="">
	<meta name="keywords" content="">
	<title>Magic Cinema - Registro</title>

</head>

<body class="body">
	<div class="sign section--bg" data-bg="../../assets/img/section/section.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="sign__content">
						<form method="post" class="sign__form" onsubmit="return validar()">
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
								<input type="email" name="email" class="sign__input" placeholder="Correo electrónico" required>
							</div>

							<div class="sign__group">
								<input type="password" name="password" id="password" class="sign__input" placeholder="Contraseña" required>
							</div>

							<div class="sign__group">
								<input type="password" name="password" id="confirm_password" class="sign__input" placeholder="Confirmar Contraseña" required>
							</div>

							<div class="sign__group sign__group--checkbox">
								<input id="check" name="check" type="checkbox" checked="checked" required>
								<label for="check">Acepto las <a href="politicas.html">políticas de
										privacidad</a></label>
							</div>

							<button class="sign__btn" type="submit" onclick="validateFormChek()">Regístrate ahora</button>

							<span class="sign__text">¿Ya tienes una cuenta? <a href="Login.php">¡Iniciar sesión!</a></span>
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

		function validar() {

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
	<script src="../../assets/js/main.js"></script>
</body>

</html>