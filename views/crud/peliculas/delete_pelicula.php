<?php

include_once '../../../includes/config.php';

class PeliculaDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function eliminarPelicula($id) {
        try {
            // Verificar si la película existe
            $statementCheck = $this->pdo->prepare("SELECT * FROM peliculas WHERE pelicula_id = ?");
            $statementCheck->execute([$id]);
            if ($statementCheck->rowCount() === 0) {
                throw new Exception("La película no existe.");
            }

            // Eliminar los registros dependientes en la tabla reservas
            $statementReservas = $this->pdo->prepare("
                DELETE FROM reservas 
                WHERE horario_id IN (SELECT horario_id FROM horarios WHERE pelicula_id = ?)
            ");
            $statementReservas->execute([$id]);

            // Eliminar los registros dependientes en la tabla horarios
            $statementHorarios = $this->pdo->prepare("DELETE FROM horarios WHERE pelicula_id = ?");
            $statementHorarios->execute([$id]);

            // Eliminar la película
            $statementPeliculas = $this->pdo->prepare("DELETE FROM peliculas WHERE pelicula_id = ?");
            $statementPeliculas->execute([$id]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Error al eliminar la película: " . $e->getMessage());
        }
    }
}

$PeliculaDelete = new PeliculaDelete();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

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
} else {
    header('Location: administrador_pelicula.php');
    exit();
}
?>