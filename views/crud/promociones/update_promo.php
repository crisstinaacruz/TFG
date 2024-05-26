<?php

include_once '../../../includes/config.php';

class PromoUpdate {

    private $pdo;

    public function __construct() {
        $this->pdo = ConnectDatabase::conectar();
    }

    public function actualizarPromo($id, $titulo, $descripcion, $fecha, $imagen) {
        
        if ($this->validarDatos($id, $titulo, $descripcion, $imagen)) {
            $imagen = $this->procesarImagen($id, $imagen);
            
            $statement = $this->pdo->prepare("UPDATE promociones SET 
                titulo = ?, descripcion = ?, fecha = ?, imagen = ? WHERE promocion_id = ?");

            $statement->execute([$titulo, $descripcion, $fecha, $imagen, $id]);

            return true;

        } else {
            return false;
        }
    }

    private function validarDatos($id, $titulo, $descripcion, $imagen) {
        return !empty($id) && !empty($titulo) && !empty($descripcion) && !empty($imagen);
    }


        private function procesarImagen($imagen) {
            $directorioDestino = "../../../uploads/promociones/";
            $archivoDestino = $directorioDestino . basename($_FILES['imagen']['name']);
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $archivoDestino)) {
                // Retorna la ruta del archivo si se sube exitosamente
                return $archivoDestino;
            } else {
                // Retorna null si falla la subida
                return null;
            }
        }
}

$PromoUpdate = new PromoUpdate();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : null;

    if ($PromoUpdate->actualizarPromo($id, $titulo, $descripcion, $fecha, $_FILES['imagen'])) {
        header('Location: administrador_promo.php');
        exit();

    } else {
        echo "Error en la validación de datos.";
    }

} else {
    header('Location: administrador_promo.php');
    exit();
}
?>
