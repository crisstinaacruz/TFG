<?php
session_start();

// Incluye el archivo de configuración y establece la conexión a la base de datos
include_once '../includes/config.php';
$conexion = ConnectDatabase::conectar();

// Obtiene los datos necesarios desde la URL
$idsButacas = isset($_GET['idsButacas']) ? $_GET['idsButacas'] : '';
$usuarioId = isset($_SESSION["Usuario_ID"]) ? $_SESSION["Usuario_ID"] : null;
$correoUsuario = isset($_GET['correo']) ? $_GET['correo'] : '';
$id_horario = $_GET['idHorario'];
$total = isset($_GET['total']) ? floatval($_GET['total']) : 0.00;

$query_fecha = "SELECT fecha FROM horarios WHERE horario_id = :id_horario";
$statement_fecha = $conexion->prepare($query_fecha);
$statement_fecha->bindParam(':id_horario', $id_horario);
$statement_fecha->execute();
$fecha_horario = $statement_fecha->fetch(PDO::FETCH_ASSOC)['fecha'];
// Formatear la fecha y hora
$fecha_formateada = date('Y-m-d', strtotime($fecha_horario));
$hora_formateada = date('H:i', strtotime($fecha_horario));

// Generar firma para Redsys
function generateSignature($parameters, $key) {
    $key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(16);
    $key = openssl_encrypt($parameters, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($key);
}

// Datos del comercio
$merchantCode = "tu_codigo_comercio";
$terminal = "1";
$amount = $total * 100; // Importe en céntimos
$currency = "978"; // Código ISO 4217 de la moneda (EUR)
$order = "pedido123"; // Número de pedido
$productDescription = "Descripción del producto";
$titular = "Nombre del titular";
$urlOK = "comprafin.php";
$urlKO = "compra-fallida.php";


// Datos a firmar
$parameters = json_encode(array(
    'Ds_Merchant_MerchantCode' => $merchantCode,
    'Ds_Merchant_Terminal' => $terminal,
    'Ds_Merchant_Amount' => $amount,
    'Ds_Merchant_Currency' => $currency,
    'Ds_Merchant_Order' => $order,
    'Ds_Merchant_ProductDescription' => $productDescription,
    'Ds_Merchant_Titular' => $titular,
    'Ds_Merchant_UrlOK' => $urlOK,
    'Ds_Merchant_UrlKO' => $urlKO
));

// Clave secreta proporcionada por Redsys
$secretKey = "clave_secreta";
$signature = generateSignature($parameters, $secretKey);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="../assets/css/nouislider.min.css">
    <link rel="stylesheet" href="../assets/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/plyr.css">
    <link rel="stylesheet" href="../assets/css/photoswipe.css">
    <link rel="stylesheet" href="../assets/css/default-skin.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="icon" type="image/png" href="../assets/icon/icono.png" sizes="32x32">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Magic Cinema - Resumen de Compra</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    include_once "../includes/Navbar.php";

    if (isset($_SESSION["email"])) {
        Navbar::renderAuthenticatedNavbar($_SESSION["email"]);
    } else {
        Navbar::renderUnauthenticatedNavbar();
    }
    ?>
    <section class="home">
        <div class="owl-carousel home__bg">
            <div class="item home__cover" data-bg="../assets/img/home/home__bg4.jpg"></div>
        </div>
    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content__title">Resumen de Compra</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="container mt-5">
                <h2 class="content__title">Total a Pagar: <?php echo $total; ?> €</h2>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;">Entradas:</h3>
                <ul style="color:#fff; font-family: 'Open Sans', sans-serif;">
                    <?php
                    // Parsear los IDs de las butacas y obtener la información de la sala, fila y columna para cada una
                    $butacas = explode(",", $idsButacas);
                    foreach ($butacas as $butaca) {
                        // Obtener información de la butaca
                        $query = "SELECT salas.nombre AS sala, asientos.fila, asientos.columna 
                                    FROM asientos 
                                    INNER JOIN salas ON asientos.sala_id = salas.sala_id 
                                    WHERE asiento_id = :butaca";
                        $statement = $conexion->prepare($query);
                        $statement->bindParam(':butaca', $butaca);
                        $statement->execute();
                        $butaca_info = $statement->fetch(PDO::FETCH_ASSOC);
                        // Mostrar la información de la butaca
                        echo "<li>" . $butaca_info['sala'] . " - Fila: " . $butaca_info['fila'] . ", Columna: " . $butaca_info['columna'] . "</li>";
                    }
                    ?>
                </ul>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;  margin-top: 20px;"">Correo Electrónico:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $correoUsuario; ?></p>
                <h3 style="color:#fff; font-family: 'Open Sans', sans-serif;">Fecha y Hora:</h3>
                <p style="color:#fff; font-family: 'Open Sans', sans-serif;"><?php echo $fecha_formateada . ' ' . $hora_formateada; ?></p>
                <form id="paymentForm" method="POST" action="https://sis-t.redsys.es:25443/sis/realizarPago">
                    <!-- Datos del comercio -->
                    <input type="hidden" name="Ds_Merchant_MerchantCode" value="<?php echo $merchantCode; ?>">
                    <input type="hidden" name="Ds_Merchant_Terminal" value="<?php echo $terminal; ?>">
                    <input type="hidden" name="Ds_Merchant_Amount" value="<?php echo $amount; ?>">
                    <input type="hidden" name="Ds_Merchant_Currency" value="<?php echo $currency; ?>">
                    <input type="hidden" name="Ds_Merchant_Order" value="<?php echo $order; ?>">
                    <input type="hidden" name="Ds_Merchant_ProductDescription" value="<?php echo $productDescription; ?>">
                    <input type="hidden" name="Ds_Merchant_Titular" value="<?php echo $titular; ?>">
                    <input type="hidden" name="Ds_Merchant_UrlOK" value="<?php echo $urlOK; ?>">
                    <input type="hidden" name="Ds_Merchant_UrlKO" value="<?php echo $urlKO; ?>">
                    <input type="hidden" name="Ds_Merchant_MerchantSignature" value="<?php echo $signature; ?>">
                    <button style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;" type="submit" class="btn btn-primary">Pagar</button>
                </form>
            </div>
        </div>
    </section>

    <!-- footer -->
    <?php
    include_once "../includes/footer.php";
    echo getFooterHTML();
    ?> 

    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script src="../assets/assets/js/jquery.mousewheel.min.js"></script>
    <script src="../assets/js/jquery.mCustomScrollbar.min.js"></script>
    <script src="../assets/js/wNumb.js"></script>
    <script src="../assets/js/nouislider.min.js"></script>
    <script src="../assets/js/plyr.min.js"></script>
    <script src="../assets/js/jquery.morelines.min.js"></script>
    <script src="../assets/js/photoswipe.min.js"></script>
    <script src="../assets/js/photoswipe-ui-default.min.js"></script>
    <script src="../assets/js/main.js"></script>

</body>

</html>

