<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Primero, verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Realiza la conexión a la base de datos y realiza la consulta
	include_once "../includes/config.php";
	$conexion = ConnectDatabase::conectar();

	// Verifica si la conexión fue exitosa
	if ($conexion) {
		// Recupera el correo electrónico y la contraseña del formulario de inicio de sesión
		$email = $_POST["user"];
		$password = $_POST["password"];

		// Crea la consulta SQL para verificar las credenciales del usuario
		$sql = "SELECT * FROM usuarios WHERE email = :email";
		$statement = $conexion->prepare($sql);
		$statement->bindValue(":email", $email, PDO::PARAM_STR);
		$statement->execute();

		if ($statement->rowCount() > 0) {
			$usuario = $statement->fetch(PDO::FETCH_ASSOC);

			if (password_verify($password, $usuario["password"])) {
				// Genera un código alfanumérico
				$verificationCode = substr(md5(uniqid(mt_rand(), true)), 0, 6);

				// Define la marca de tiempo de expiración (1 minuto desde ahora)
				$expirationTime = time() + 60;

				// Inserta el código en la tabla check_codes
				$insertSql = "INSERT INTO check_codes (email, codigo, expira_en) VALUES (:email, :codigo, :expiration)";
				$insertStatement = $conexion->prepare($insertSql);
				$insertStatement->bindValue(":email", $email, PDO::PARAM_STR);
				$insertStatement->bindValue(":codigo", $verificationCode, PDO::PARAM_STR);
				$insertStatement->bindValue(":expiration", date('Y-m-d H:i:s', $expirationTime), PDO::PARAM_STR);
				$insertStatement->execute();

				// Envía el correo de confirmación
				enviarCorreoConfirmacion($email, $verificationCode);

				header("Location: authentication.php?email=" . $email);
				exit;
			} else {
				// La contraseña no coincide, muestra un mensaje de error al usuario
				echo "<script>alert('La contraseña es incorrecta.');</script>";
			}
		} else {
			// No se encontró ningún usuario con el correo electrónico proporcionado, muestra un mensaje de error al usuario
			echo "<script>alert('No se encontró ningún usuario con el correo electrónico proporcionado.');</script>";
		}

		// Cierra la conexión a la base de datos
		$conexion = null;
	} else {
		// No se pudo conectar a la base de datos, muestra un mensaje de error
		echo "<script>alert('Error al conectar a la base de datos.');</script>";
	}
}

function enviarCorreoConfirmacion($email, $verificationCode)
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
		$mail->Subject = 'Codigo de verificación';
		$mail->Body = 'Tu código de verificación es: ' . $verificationCode;

		$mail->send();
	} catch (Exception $e) {
		echo "<script>alert('Error al enviar el correo de verificación: {$mail->ErrorInfo}');</script>";
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

	<link rel="icon" type="image/png" href="../assets/icon/icono.png" sizes="32x32">


	<meta name="description" content="">
	<meta name="keywords" content="">
	<title>Magic Cinema - Iniciar Sesión</title>

</head>

<body class="body">

	<div class="sign section--bg" data-bg="../../assets/img/section/section.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="sign__content">
						<form method="post" class="sign__form">
							<a href="../../index.php" class="sign__logo">
								<img src="../../assets/img/logo.png" alt="">
							</a>

							<div class="sign__group">
								<input type="text" name="user" class="sign__input" placeholder="Correo electrónico" required>
							</div>

							<div class="sign__group">
								<input type="password" name="password" class="sign__input" placeholder="Contraseña" required>
							</div>

							<button class="sign__btn" type="submit">Iniciar sesión</button>

							<span class="sign__text">¿No tienes una cuenta? <a href="Registro.php">¡Inscribirse!</a></span>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

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