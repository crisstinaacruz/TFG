<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include_once '../includes/config.php';
include_once "../includes/Navbar.php";

$conexion = ConnectDatabase::conectar();

$usuario_id = $_SESSION['usuario_id'];
$idsButacas = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$correoUsuario = isset($_SESSION['correoUsuario']) ? $_SESSION['correoUsuario'] : '';
$bar_productos = isset($_SESSION['bar_productos']) ? $_SESSION['bar_productos'] : [];
$horario_id = $_SESSION['horario_id'];
$precio_final = isset($_SESSION['precio_final']) ? floatval($_SESSION['precio_final']) : 0.00;

$titulo_pelicula = $_SESSION['titulo_pelicula'];
$fecha_formateada = $_SESSION['fecha_formateada'];
$hora_formateada = $_SESSION['hora_formateada'];

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

    public function realizarReserva($idsButacas, $usuario_id, $bar_productos, $horario_id, $precio_final)
    {
        $sql = "INSERT INTO reservas (usuario_id, horario_id, total) VALUES (:usuario_id, :horario_id, :precio_final)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':horario_id', $horario_id, PDO::PARAM_INT);
        $stmt->bindParam(':precio_final', $precio_final, PDO::PARAM_STR);
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

        if (!empty($bar_productos)) {
            foreach ($bar_productos as $producto) {
                $sql_producto = "INSERT INTO reserva_productos_bar (reserva_id, bar_id, cantidad) VALUES (:reserva_id, :bar_id, :cantidad)";
                $stmt_producto = $this->pdo->prepare($sql_producto);
                $stmt_producto->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
                $stmt_producto->bindParam(':bar_id', $producto['id'], PDO::PARAM_INT);
                $stmt_producto->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
                $stmt_producto->execute();
            }
        }

        return $reserva_id;
    }
    public function getBarTitleById($bar_id)
    {
        $sql = "SELECT titulo FROM bar WHERE bar_id = :bar_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':bar_id', $bar_id, PDO::PARAM_INT);
        $stmt->execute();
        $titulo_bar = $stmt->fetchColumn();
        return $titulo_bar;
    }

public function enviarCorreo($correoUsuario, $titulo_pelicula, $bar_productos, $asientos_info, $fecha_formateada, $hora_formateada, $precio_final)
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
                    <p><strong>Productos del bar:</strong></p>
                    <ul>";

                    if (empty($bar_productos)) {
                        $body .= "<p>Sin complementos del bar</p>";
                    } else {
                        foreach ($bar_productos as $producto) {
                            $titulo_bar = $this->getBarTitleById($producto['id']);
                            $body .= "<li>{$titulo_bar} - Cantidad: {$producto['cantidad']}</li>";
                        }
                    }

        $body .= "
                    </ul>
                    <p><strong>Asientos:</strong></p>
                    <ul>";

        foreach ($asientos_info as $asiento) {
            $body .= "<li>{$asiento['sala']} - Fila: {$asiento['fila']}, Columna: {$asiento['columna']}</li>";
        }

        $body .= "
                    </ul>
                    <p><strong>Total:</strong> {$precio_final} €</p>
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

if(isset($_POST['continuar'])) {
    $procesarPago = new ProcesarPago();
    $procesarPago->actualizarButacas($idsButacas);
    $reserva_id = $procesarPago->realizarReserva($idsButacas, $usuario_id, $bar_productos, $horario_id, $precio_final);

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

    $procesarPago->enviarCorreo($correoUsuario, $titulo_pelicula, $bar_productos, $asientos_info, $fecha_formateada, $hora_formateada, $precio_final);

    header('Location: comprafin.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/png" href="../assets/icon/iconredsys.png" sizes="32x32">
    <title>Página de Pago - Redsys</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #header {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #header img {
            max-width: 150px;
        }
        ol.steps-wr {
            list-style: none;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }
        ol.steps-wr li {
            text-align: center;
            flex: 1;
        }
        ol.steps-wr li.active .num {
            background-color: #ffa500;
            color: #fff;
        }
        ol.steps-wr li .num {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            margin-bottom: 10px;
        }
        ol.steps-wr li.active .s-text, ol.steps-wr li .s-text {
            color: #333;
        }
        #body {
            padding: 20px;
        }
        .result-mod-wr {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            width: 45%;
        }
        .datosDeLaOperacion {
            font-weight: bold;
            font-size: 18px;
            color: #ffa500;
            margin-bottom: 10px;
        }
        .ticket-header, .ticket-info {
            margin-bottom: 20px;
        }
        .price {
            display: flex;
            justify-content: space-between;
        }
        .table-condensed {
            width: 100%;
            border-collapse: collapse;
        }
        .table-condensed td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table-condensed .text {
            font-weight: bold;
        }
        .form-group input {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn.continue {
            background-color: #ffa500;
            color: #fff;
        }
        .btn.continue:hover {
            background-color: #e69500;
        }
        .btn.cancel {
            background-color: #ddd;
            color: #333;
        }
        .btn.cancel:hover {
            background-color: #ccc;
        }
        #footer {
            background-color: #f7f7f7;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
        }
        .powered {
            font-size: 12px;
            color: #666;
        }
        .buttons-wr {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn.print {
            background-color: #ddd;
            color: #333;
        }
        .btn.print:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
<div id="container">
    <form method="post">
        
        <header id="header">
            <div class="container">
                <img src="../assets/img/redsys.jpg" alt="Redsys">
                
            </div>
        </header>
        <ol class="steps-wr">
            <li id="s-method" class="step">
                <span class="num">1</span>
                <p class="s-text">Seleccione método de pago</p>
            </li>
            <li id="s-auth" class="step">
                <span class="num">2</span>
                <p class="s-text">Comprobación autenticación</p>
            </li>
            <li id="s-connect" class="step">
                <span class="num">3</span>
                <p class="s-text">Solicitando Autorización</p>
            </li>
            <li id="s-result" class="step active">
                <span class="num">4</span>
                <p class="s-text">Resultado Transacción</p>
            </li>
        </ol>
        <div id="body">
            <div class="result-mod-wr">
                <div class="datosDeLaOperacion">Datos de la operación</div>
                <div class="ticket-header">
                    <div class="price">
                        <div class="left">
                            <p>Importe</p>
                        </div>
                        <div class="right">
                            <p><?php echo number_format($precio_final, 2); ?>&nbsp;Euros</p>
                        </div>
                    </div>
                </div>
                <div class="ticket-info">
                    <table class="table-condensed">
                        <tr id="filaNombreComercio">
                            <td class="text">Código Comercio:</td>
                            <td class="numeric">384953</td>
                        </tr>
                        <tr id="filaCodigoComercio">
                            <td class="text">Terminal:</td>
                            <td class="numeric">3048-1</td>
                        </tr>
                        <tr id="filaNumeroPedido">
                            <td class="text">Número pedido:</td>
                            <td class="numeric">pedido679</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="buttons-wr">
                <button type="button" class="btn print">Imprimir</button>
                <button type="submit" class="btn continue" name="continuar">Continuar</button>
            </div>
        </div>
    </form>
    <footer id="footer">
        <p class="powered">Powered by Redsys</p>
        <div id="footerGeneral">
            <div class="copyright">
                <center>
                    <text>&copy; 2024 Redsys Servicios de Procesamiento. SL - Todos los derechos reservados.</text>
                </center>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
