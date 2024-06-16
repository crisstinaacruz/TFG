<?php
session_start();

if (isset($_GET['id_pelicula'])) {
    $_SESSION['id_pelicula'] = $_GET['id_pelicula'];
    header('Location: ../views/pelicula.php');
    exit();
}

if (isset($_GET['horario_id'])) {
    $_SESSION['horario_id'] = $_GET['horario_id'];
    header('Location: ../views/comprarEntrada.php');
    exit();
}

if (isset($_GET['butacas']) && isset($_GET['id'])) {
    $_SESSION['butacas'] = $_GET['butacas'];
    $_SESSION['id'] = $_GET['id'];
    header('Location: ../views/tipoEntrada.php');
    exit();
}

if (isset($_GET['correo']) && isset($_GET['precio'])) {
    $_SESSION['correoUsuario'] = $_GET['correo'];
    $_SESSION['precio'] = $_GET['precio'];
    header('Location: ../views/bar.php');
    exit();
}

if (isset($_GET['total'])&& isset($_GET['bar'])) {
    $_SESSION['total'] = $_GET['total'];
    $_SESSION['bar_id'] = $_GET['bar'];
    header('Location: ../views/tarjeta.php');
    exit();
}

echo "Error: Parámetros insuficientes.";
?>
