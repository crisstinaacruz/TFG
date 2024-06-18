<?php
session_start();
include_once '../../../includes/config.php';

$id = $_SESSION['horario_id'];

class HorarioDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function eliminarHorarios($id) {


        try {
            $this->pdo->beginTransaction();

            $statementReservas = $this->pdo->prepare("SELECT reserva_id FROM reservas WHERE horario_id = ?");
            $statementReservas->execute([$id]);
            $reservas = $statementReservas->fetchAll(PDO::FETCH_ASSOC);

            foreach ($reservas as $reserva) {
                $statementDeleteProductosBar = $this->pdo->prepare("DELETE FROM reserva_productos_bar WHERE reserva_id = ?");
                $statementDeleteProductosBar->execute([$reserva['reserva_id']]);
            }

            $statementDeleteReservas = $this->pdo->prepare("DELETE FROM reservas WHERE horario_id = ?");
            $statementDeleteReservas->execute([$id]);

            $statementDeleteHorario = $this->pdo->prepare("DELETE FROM horarios WHERE horario_id = ?");
            $statementDeleteHorario->execute([$id]);

            $this->pdo->commit();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al eliminar el horario: " . $e->getMessage());
        }
    }


}

$HorarioDelete = new HorarioDelete();

try {
    if ($HorarioDelete->eliminarHorarios($id)) {
        header('Location: administrador_horario.php');
        exit();
    } else {
        echo "Error en la validación del ID.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
