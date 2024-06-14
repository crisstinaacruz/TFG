<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include_once "../includes/config.php";
	$conexion = ConnectDatabase::conectar();

	if ($conexion) {
		$email = $_POST["correo"];
		$password = $_POST["password"];

		$sql = "SELECT * FROM usuarios WHERE email = :email";
		$statement = $conexion->prepare($sql);
		$statement->bindValue(":email", $email, PDO::PARAM_STR);
		$statement->execute();

		if ($statement->rowCount() > 0) {
			$usuario = $statement->fetch(PDO::FETCH_ASSOC);

			if (password_verify($password, $usuario["password"])) {
				$verificationCode = substr(md5(uniqid(mt_rand(), true)), 0, 6);

				$expirationTime = time() + 60;

				$insertSql = "INSERT INTO check_codes (email, codigo, expira_en) VALUES (:email, :codigo, CURRENT_TIMESTAMP + INTERVAL '1 minute')";
				$insertStatement = $conexion->prepare($insertSql);
				$insertStatement->bindValue(":email", $email, PDO::PARAM_STR);
				$insertStatement->bindValue(":codigo", $verificationCode, PDO::PARAM_STR);
				$insertStatement->execute();


				enviarCorreoConfirmacion($email, $verificationCode);
				$_SESSION['email'] = $email;

				header("Location: authentication.php");
				exit();
			} else {
				echo "<script>alert('La contraseña es incorrecta.');</script>";
			}
		} else {
			echo "<script>alert('No se encontró ningún usuario con el correo electrónico proporcionado.');</script>";
		}

		$conexion = null;
	} else {
		echo "<script>alert('Error al conectar a la base de datos.');</script>";
	}
}

function enviarCorreoConfirmacion($email, $verificationCode)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@magiccinema.es';
        $mail->Password = 'MagicCinema2024*';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('no-reply@magiccinema.es', 'Magic Cinema');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Código de Verificación';

        $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: auto;
                    background-color: #fff;
                    font-family: Arial, sans-serif;
                    color: #333;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                }
                .header {
                    background: linear-gradient(90deg, #ff007f, #ff7f00);
                    color: #fff;
                    text-align: center;
                    padding: 20px 0;
                    border-radius: 10px 10px 0 0;
                }
                .content {
                    padding: 20px;
                    text-align: left;
                    color: #555;
                }
                
                .verification-code {
                    background-color: #333;
                    color: #fff;
                    padding: 10px;
                    border-radius: 5px;
                    text-align: center;
                    font-size: 20px;
                    font-weight: bold;
                    margin: 20px 0;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #999;
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>Magic Cinema</h1>
                </div>
                <div class="content">
                    <h2>Tu Código de Verificación</h2>
                    <p>Utiliza el siguiente código para verificar tu cuenta:</p>
                    <div class="verification-code">' . $verificationCode . '</div>
                </div>
                <div class="footer">
                    <p>&copy; 2024 Magic Cinema. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo de verificación. Por favor, inténtalo de nuevo más tarde.');</script>";
		header("Location: Login.php");
		exit();
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

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
								<input type="text" name="correo" class="sign__input" placeholder="Correo electrónico" required>
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