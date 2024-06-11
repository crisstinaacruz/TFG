<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include_once '../includes/config.php';


$conexion = ConnectDatabase::conectar();

$usuario_id = $_SESSION['usuario_id'];

// Obtiene los datos necesarios desde la URL
$idsButacas = isset($_GET['idsButacas']) ? $_GET['idsButacas'] : '';
$email = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;
$correoUsuario = isset($_GET['correo']) ? $_GET['correo'] : '';
$id_horario = $_GET['idHorario'];
$total = isset($_GET['total']) ? floatval($_GET['total']) : 0.00;



// Obtiene la fecha del horario y el pelicula_id
$query_fecha = "SELECT fecha, pelicula_id FROM horarios WHERE horario_id = :id_horario";
$statement_fecha = $conexion->prepare($query_fecha);
$statement_fecha->bindParam(':id_horario', $id_horario);
$statement_fecha->execute();
$resultado_horario = $statement_fecha->fetch(PDO::FETCH_ASSOC);
$fecha_horario = $resultado_horario['fecha'];
$pelicula_id = $resultado_horario['pelicula_id'];

// Formatear la fecha y hora
$fecha_formateada = date('d-m-Y', strtotime($fecha_horario));
$hora_formateada = date('H:i', strtotime($fecha_horario));

// Obtener el título de la película
$query_pelicula = "SELECT titulo FROM peliculas WHERE pelicula_id = :pelicula_id";
$statement_pelicula = $conexion->prepare($query_pelicula);
$statement_pelicula->bindParam(':pelicula_id', $pelicula_id);
$statement_pelicula->execute();
$titulo_pelicula = $statement_pelicula->fetch(PDO::FETCH_ASSOC)['titulo'];






    class ProcesarPago
    {
        private $pdo;
    
        public function __construct()
        {
            $this->pdo = ConnectDatabase::conectar();
        }
    
        public function actualizarButacas($idsButacas)
        {
            $idsButacasArray = explode(',', $idsButacas);
    
            // Escapar y formatear los IDs para la consulta SQL
            $idsButacasArray = array_map(function ($id) {
                return intval($id);
            }, $idsButacasArray);
    
            $idsButacasStr = implode(',', $idsButacasArray);
            // Actualizar la tabla de asientos (suponiendo que existe una columna llamada 'estado' que representa si está ocupado o no)
            $sql = "UPDATE asientos SET estado_asiento = 'Ocupado' WHERE asiento_id IN ($idsButacasStr)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
    
    
        public function realizarReserva($idsButacas, $usuario_id, $id_horario)
        {
            $idsButacasArray = explode(',', $idsButacas);
            foreach ($idsButacasArray as $asientoId) {
                // Obtener información sobre el asiento desde la base de datos (ajusta según tu esquema)
                $infoAsiento = $this->obtenerInfoAsiento($asientoId);
                // Realizar inserción en la tabla de reservas
                $sql = "INSERT INTO reservas (usuario_id, id_horario, asiento_id) VALUES (:usuario_id, :id_horario, :asiento_id)";
                $stmt = $this->pdo->prepare($sql);
    
                $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt->bindParam(':id_horario', $id_horario, PDO::PARAM_INT);
                $stmt->bindParam(':asiento_id', $asientoId, PDO::PARAM_INT);
                $stmt->execute();
    
                $this->enviarCorreo($email, $infoAsiento);
            }
        }
    
        public function enviarCorreo($email, $infoAsiento)
        {
            // Configuración de PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'no-reply@magiccinema.es';
                $mail->Password = 'MagicCinema2024*';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
    
                $mail->setFrom('no-reply@magiccinema.es', 'no-reply@magiccinema.es');
                $mail->addAddress($email);
    
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Reserva Confirmada';
    
                $body = "Gracias por tu reserva. Aquí está la información detallada:<br><br>" .
                    "Película: {$infoAsiento['titulo_pelicula']}<br>" .
                    "Sala: {$infoAsiento['nombre_sala']}<br>" .
                    "Asiento: Fila {$infoAsiento['fila']}, Columna {$infoAsiento['columna']}<br>";
    
                $mail->Body = $body;
    
                $mail->send();
            } catch (Exception $e) {
                echo "Error al enviar el correo de confirmación: {$mail->ErrorInfo}";
            }
        }
    }


if (isset($_POST['pagar'])) {
    // Verificar si el formulario ha sido enviado y luego llamar a la función realizarReserva
    $procesarPago = new ProcesarPago();
    $procesarPago->actualizarButacas($idsButacas);
    $procesarPago->realizarReserva($idsButacas, $usuario_id, $id_horario);
}


?>

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
    <title>Magic Cinema - Resumen de Compra</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    include_once "../includes/Navbar.php";

    if (isset($_SESSION["email"])) {
        Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
    } else {
        Navbar::renderUnauthenticatedNavbar();
    }
    ?>
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
                        <h2 class="content__title">Resumen de Compra <?php echo $usuario_id; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="container mt-5">
                <h2 class="content__title">Total a Pagar: <?php echo $total; ?> €</h2>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;">Entradas:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;">Película: <?php echo $titulo_pelicula; ?></p>
                <ul style="color:#fff; font-family: 'Open Sans', sans-serif;">
                    <?php
                    // Parsear los IDs de las butacas y obtener la información de la sala, fila y columna para cada una
                    $butacas = explode(",", $idsButacas);
                    foreach ($butacas as $butaca) {
                        // Obtener información de la butaca
                        $query = "SELECT salas.nombre AS sala, asientos.fila, asientos.columna 
                                    FROM asientos 
                                    INNER JOIN salas ON asientos.sala_id = salas.sala_id 
                                    WHERE asiento_id = :butaca";
                        $statement = $conexion->prepare($query);
                        $statement->bindParam(':butaca', $butaca);
                        $statement->execute();
                        $butaca_info = $statement->fetch(PDO::FETCH_ASSOC);
                        // Mostrar la información de la butaca
                        echo "<li>" . $butaca_info['sala'] . " - Fila: " . $butaca_info['fila'] . ", Columna: " . $butaca_info['columna'] . "</li>";
                    }
                    ?>
                </ul>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;  margin-top: 20px;"">Correo Electrónico:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $correoUsuario; ?></p>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;">Fecha y Hora:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $fecha_formateada . ' ' . $hora_formateada; ?></p>

                <form method="post">
                    <button style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;" type="submit" class="btn btn-primary" name="pagar">Pagar</button>
                </form>

            </div>
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