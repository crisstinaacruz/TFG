<?php
include_once '../../../includes/config.php';
$pdo = ConnectDatabase::conectar();


$sala = $_POST['sala'];
$pelicula = $_POST['pelicula'];
$fecha = $_POST['fecha'];

$sqlSala = "SELECT sala_id, filas, columnas FROM salas WHERE nombre = :sala";
$stmtSala = $pdo->prepare($sqlSala);
$stmtSala->bindParam(':sala', $sala, PDO::PARAM_STR);
$stmtSala->execute();
$sala = $stmtSala->fetch(PDO::FETCH_ASSOC);

$salaId = $sala['sala_id'];
$filas = $sala['filas'];
$columnas = $sala['columnas'];

$sqlPelicula = "SELECT pelicula_id FROM peliculas WHERE titulo = :pelicula";
$stmtPelicula = $pdo->prepare($sqlPelicula);
$stmtPelicula->bindParam(':pelicula', $pelicula, PDO::PARAM_STR);
$stmtPelicula->execute();
$peliculaId = $stmtPelicula->fetchColumn();

$fechaFormateada = date('Y-m-d H:i:s', strtotime($fecha));

$sqlHorario = "INSERT INTO horarios (sala_id, pelicula_id, fecha) VALUES (
    :salaId,
    :peliculaId,
    :fecha
)";
$stmtHorario = $pdo->prepare($sqlHorario);
$stmtHorario->bindParam(':salaId', $salaId, PDO::PARAM_INT);
$stmtHorario->bindParam(':peliculaId', $peliculaId, PDO::PARAM_INT);
$stmtHorario->bindParam(':fecha', $fecha, PDO::PARAM_STR);
$stmtHorario->execute();


$horarioId = $pdo->lastInsertId();


for ($fila = 1; $fila <= $filas; $fila++) {
    for ($columna = 1; $columna <= $columnas; $columna++) {
        $sqlAsiento = "INSERT INTO asientos (sala_id, fila, columna, estado_asiento) VALUES (
            :salaId,
            :fila,
            :columna,
            'Disponible'
            )";
        $stmtAsiento = $pdo->prepare($sqlAsiento);
        $stmtAsiento->bindParam(':salaId', $salaId, PDO::PARAM_INT);
        $stmtAsiento->bindParam(':fila', $fila, PDO::PARAM_INT);
        $stmtAsiento->bindParam(':columna', $columna, PDO::PARAM_INT);
        $stmtAsiento->execute();
    }
}


    header('Location: administrador_horario.php');
    exit();
?>
