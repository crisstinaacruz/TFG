<?php
session_start();
include_once '../../../includes/config.php';

$id = $_SESSION['bar_id'];

class BarDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function eliminarBar($id) {


        try {
            $this->pdo->beginTransaction();

            $statementDeleteProductosBar = $this->pdo->prepare("DELETE FROM reserva_productos_bar WHERE bar_id = ?");
            $statementDeleteProductosBar->execute([$id]);

            $statementDeleteBar = $this->pdo->prepare("DELETE FROM bar WHERE bar_id = ?");
            $statementDeleteBar->execute([$id]);

            $this->pdo->commit();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al eliminar el producto del bar: " . $e->getMessage());
        }
    }


}

$BarDelete = new BarDelete();

try {
    if ($BarDelete->eliminarBar($id)) {
        header('Location: administrador_bar.php');
        exit();
    } else {
        echo "Error en la validación del ID.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
