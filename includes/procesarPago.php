<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;


require '../vendor/autoload.php';
include_once '../includes/config.php';
$conexion = ConnectDatabase::conectar();

class ProcesarPago
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function actualizarButacas($idsButacas)
    {
        $idsButacasArray = explode(',', $idsButacas);

        // Escapar y formatear los IDs para la consulta SQL
        $idsButacasArray = array_map(function ($id) {
            return intval($id);
        }, $idsButacasArray);

        $idsButacasStr = implode(',', $idsButacasArray);
        $sql = "UPDATE asientos SET estado_asiento = 'Ocupado' WHERE asiento_id IN ($idsButacasStr)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
    }

    public function realizarReserva($idsButacas, $usuario_id, $email, $id_horario)
    {
        

        $idsButacasArray = explode(',', $idsButacas);
        foreach ($idsButacasArray as $asientoId) {
            $infoAsiento = $this->obtenerInfoAsiento($asientoId);
            $sql = "INSERT INTO reservas (usuario_id, id_horario, asiento_id) VALUES (:usuario_id, :id_horario, :asiento_id)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':id_horario', $id_horario, PDO::PARAM_INT);
            $stmt->bindParam(':asiento_id', $asientoId, PDO::PARAM_INT);
            $stmt->execute();

            ProcesarPago::enviarCorreo($email, $infoAsiento);

        }
        
    }

    
    

public function enviarCorreo($email, $infoAsiento)
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

        $mail->setFrom('no-reply@magiccinema.es', 'no-reply@magiccinema.es');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Reserva Confirmada';
        
        $body = "Gracias por tu reserva. Aquí está la información detallada:<br><br>" .
            "Película: {$titulo_pelicula}<br>" .
            "Sala: <br>" .
            "Asiento: Fila , Columna <br>";

        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo de confirmación: {$mail->ErrorInfo}";
    }
}
}
?>