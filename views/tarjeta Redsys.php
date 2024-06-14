<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include_once '../includes/config.php';


$conexion = ConnectDatabase::conectar();

$usuario_id = $_SESSION['usuario_id'];

$idsButacas = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$correoUsuario = isset($_SESSION['correoUsuario']) ? $_SESSION['correoUsuario'] : '';
$horario_id = $_SESSION['horario_id'];
$total = isset($_SESSION['total']) ? floatval($_SESSION['total']) : 0.00;



$query_fecha = "SELECT fecha, pelicula_id FROM horarios WHERE horario_id = :horario_id";
$statement_fecha = $conexion->prepare($query_fecha);
$statement_fecha->bindParam(':horario_id', $horario_id);
$statement_fecha->execute();
$resultado_horario = $statement_fecha->fetch(PDO::FETCH_ASSOC);
$fecha_horario = $resultado_horario['fecha'];
$pelicula_id = $resultado_horario['pelicula_id'];

$fecha_formateada = date('d-m-Y', strtotime($fecha_horario));
$hora_formateada = date('H:i', strtotime($fecha_horario));

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

        $idsButacasArray = array_map('intval', $idsButacasArray);
        $idsButacasStr = implode(',', $idsButacasArray);

        $sql = "UPDATE asientos SET estado_asiento = 'Ocupado' WHERE asiento_id IN ($idsButacasStr)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function realizarReserva($idsButacas, $usuario_id, $horario_id)
    {
        $sql = "INSERT INTO reservas (usuario_id, horario_id) VALUES (:usuario_id, :horario_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':horario_id', $horario_id, PDO::PARAM_INT);
        $stmt->execute();

        $reserva_id = $this->pdo->lastInsertId();

        $idsButacasArray = explode(',', $idsButacas);
        foreach ($idsButacasArray as $asiento_id) {
            $sql_asiento = "INSERT INTO reserva_asientos (reserva_id, asiento_id) VALUES (:reserva_id, :asiento_id)";
            $stmt_asiento = $this->pdo->prepare($sql_asiento);
            $stmt_asiento->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            $stmt_asiento->bindParam(':asiento_id', $asiento_id, PDO::PARAM_INT);
            $stmt_asiento->execute();
        }

        return $reserva_id;
    }

    public function enviarCorreo($correoUsuario, $titulo_pelicula, $asientos_info, $fecha_formateada, $hora_formateada)
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
            $mail->addAddress($correoUsuario);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Reserva Confirmada';

            $body = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f8f9fa;
                        color: #343a40;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
                        padding: 20px;
                        border-radius: 8px 8px 0 0;
                        color: #fff;
                        text-align: center;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                    }
                    .content {
                        padding: 20px;
                    }
                    .content h2 {
                        color: #ff55a5;
                        font-size: 20px;
                        margin-top: 0;
                    }
                    .content p {
                        margin: 10px 0;
                    }
                    .content ul {
                        list-style-type: none;
                        padding: 0;
                    }
                    .content ul li {
                        background-color: #f1f1f1;
                        margin: 5px 0;
                        padding: 10px;
                        border-radius: 4px;
                    }
                    .footer {
                        text-align: center;
                        padding: 20px;
                        font-size: 12px;
                        color: #6c757d;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Reserva Confirmada</h1>
                    </div>
                    <div class='content'>
                        <h2>Gracias por tu reserva. Aquí está la información detallada:</h2>
                        <p><strong>Película:</strong> {$titulo_pelicula}</p>
                        <p><strong>Fecha y Hora:</strong> {$fecha_formateada} a las {$hora_formateada}</p>
                        <p><strong>Asientos:</strong></p>
                        <ul>";

            foreach ($asientos_info as $asiento) {
                $body .= "<li>Sala: {$asiento['sala']} - Fila: {$asiento['fila']}, Columna: {$asiento['columna']}</li>";
            }

            $body .= "
                        </ul>
                    </div>
                    <div class='footer'>
                        &copy; 2024 Magic Cinema. Todos los derechos reservados.
                    </div>
                </div>
            </body>
            </html>";

            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo de confirmación: {$mail->ErrorInfo}";
        }
    }
}

if (isset($_POST['pagar'])) {
    $procesarPago = new ProcesarPago();
    $procesarPago->actualizarButacas($idsButacas);
    $reserva_id = $procesarPago->realizarReserva($idsButacas, $usuario_id, $horario_id);

    $idsButacasArray = explode(',', $idsButacas);
    $asientos_info = [];
    foreach ($idsButacasArray as $asiento_id) {
        $query = "SELECT salas.nombre AS sala, asientos.fila, asientos.columna 
                  FROM asientos 
                  INNER JOIN salas ON asientos.sala_id = salas.sala_id 
                  WHERE asiento_id = :asiento_id";
        $statement = $conexion->prepare($query);
        $statement->bindParam(':asiento_id', $asiento_id);
        $statement->execute();
        $asientos_info[] = $statement->fetch(PDO::FETCH_ASSOC);
    }

    $procesarPago->enviarCorreo($correoUsuario, $titulo_pelicula, $asientos_info, $fecha_formateada, $hora_formateada);
}

function generateSignature($parameters, $key) {
    $key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(16);
    $key = openssl_encrypt($parameters, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($key);
}

$merchantCode = "3048";
$terminal = "1";
$amount = "1000"; 
$currency = "978";
$order = "pedido123";
$productDescription = "Entradas";
$titular = "Magic Cinema";
$urlOK = "comprafin.php";
$urlKO = "comprafallida.php";

// Datos a firmar
$parameters = json_encode(array(
    'Ds_Merchant_MerchantCode' => $merchantCode,
    'Ds_Merchant_Terminal' => $terminal,
    'Ds_Merchant_Amount' => $amount,
    'Ds_Merchant_Currency' => $currency,
    'Ds_Merchant_Order' => $order,
    'Ds_Merchant_ProductDescription' => $productDescription,
    'Ds_Merchant_Titular' => $titular,
    'Ds_Merchant_UrlOK' => $urlOK,
    'Ds_Merchant_UrlKO' => $urlKO
));


$secretKey = "clave_secreta";
$signature = generateSignature($parameters, $secretKey);
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
    <link rel="icon" type="image/png" href="../../../assets/icon/icono.png" sizes="32x32">
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
                        <h2 class="content__title">Resumen de Compra</h2>
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
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;  margin-top: 20px;">Correo Electrónico:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $correoUsuario; ?></p>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;">Fecha y Hora:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $fecha_formateada . ' ' . $hora_formateada; ?></p>
                <form id="paymentForm" method="POST" action="https://sis.redsys.es/sis/realizarPago">
        
        <input type="hidden" name="Ds_Merchant_MerchantCode" value="3048">
        <input type="hidden" name="Ds_Merchant_Terminal" value="1">
        <input type="hidden" name="Ds_Merchant_Amount" value="<?php echo $total * 100; ?>"> <!-- Importe en céntimos (10€) -->
        <input type="hidden" name="Ds_Merchant_Currency" value="978"> <!-- Código ISO 4217 de la moneda (EUR) -->
        <input type="hidden" name="Ds_Merchant_Order" value="pedido123"> <!-- Número de pedido -->
        <input type="hidden" name="Ds_Merchant_ProductDescription" value="Entradas">
        <input type="hidden" name="Ds_Merchant_Titular" value="Magic Cinema">
        

        <input type="hidden" name="Ds_Merchant_UrlOK" value="comprafin.php">
        <input type="hidden" name="Ds_Merchant_UrlKO" value="comprafallida.php">
        

        <input type="hidden" name="Ds_Merchant_MerchantSignature" value="firma_calculada_con_la_api">
        

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