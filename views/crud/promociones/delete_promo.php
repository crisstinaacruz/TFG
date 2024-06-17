<?php
session_start();
include_once '../../../includes/config.php';
$id = $_SESSION['promo_id'];
class PromoDelete {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function eliminarPromo($id) {
        
        if ($this->validarId($id)) {
            $statement = $this->pdo->prepare("DELETE FROM promociones WHERE promocion_id = ?");
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

$PromoDelete = new PromoDelete();


    if ($PromoDelete->eliminarPromo($id)) {
        header('Location: administrador_promo.php');
        exit();

    } else {
        echo "Error en la validación del ID.";
    }


?>
