<?php

include_once '../../../includes/config.php';

class HorarioDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }
    

    public function eliminarHorarios($id) {
        
        if ($this->validarId($id)) {
            $statement = $this->pdo->prepare("DELETE FROM horarios WHERE horario_id = ?");
            $statement->execute([$id]);
            return true;

        } else {
            return false;

        }
    }
    


    private function validarId($id) {
        return is_numeric($id) && $id > 0;
    }
}

$HorarioDelete = new HorarioDelete();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
   
    $id = $_GET['id'];

    if ($HorarioDelete->eliminarHorarios($id)) {
        header('Location: administrador_horario.php');
        exit();

    } else {
        echo "Error en la validación del ID.";
    }

} else {
    header('Location: administrador_horario.php');
    exit();
}
?>