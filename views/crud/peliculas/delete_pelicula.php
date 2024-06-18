<?php
session_start();
include_once '../../../includes/config.php';
$id = $_SESSION['pelicula_id'];

class PeliculaDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function eliminarPelicula($id) {
        try {
            $this->pdo->beginTransaction();

            $statementHorarios = $this->pdo->prepare("SELECT horario_id FROM horarios WHERE pelicula_id = ?");
            $statementHorarios->execute([$id]);
            $horarios = $statementHorarios->fetchAll(PDO::FETCH_ASSOC);

            foreach ($horarios as $horario) {
                $statementReservas = $this->pdo->prepare("SELECT reserva_id FROM reservas WHERE horario_id = ?");
                $statementReservas->execute([$horario['horario_id']]);
                $reservas = $statementReservas->fetchAll(PDO::FETCH_ASSOC);

                foreach ($reservas as $reserva) {
                    $statementDeleteProductosBar = $this->pdo->prepare("DELETE FROM reserva_productos_bar WHERE reserva_id = ?");
                    $statementDeleteProductosBar->execute([$reserva['reserva_id']]);
                }
            }

            foreach ($horarios as $horario) {
                $statementDeleteReservas = $this->pdo->prepare("DELETE FROM reservas WHERE horario_id = ?");
                $statementDeleteReservas->execute([$horario['horario_id']]);
            }

            $statementDeleteHorarios = $this->pdo->prepare("DELETE FROM horarios WHERE pelicula_id = ?");
            $statementDeleteHorarios->execute([$id]);

            
            $statementDeletePeliculas = $this->pdo->prepare("DELETE FROM peliculas WHERE pelicula_id = ?");
            $statementDeletePeliculas->execute([$id]);

            $this->pdo->commit();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al eliminar la película: " . $e->getMessage());
        }
    }
}

$PeliculaDelete = new PeliculaDelete();

try {
    if ($PeliculaDelete->eliminarPelicula($id)) {
        header('Location: administrador_pelicula.php');
        exit();
    } else {
        echo "Error al eliminar la película.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
