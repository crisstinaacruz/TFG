<?php

session_start();

$nombreUsuario = '';
$apellidosUsuario = '';
$correoUsuario = '';


$id_horario = $_SESSION['horario_id'];
$totalButacas = isset($_SESSION['butacas']) ? $_SESSION['butacas'] : 0;
$idsButacas = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$idsButacasArray = is_array($idsButacas) ? $idsButacas : explode(',', $idsButacas);
$numeroTotalIDs = count($idsButacasArray);



if (!empty($_SESSION["email"])) {
    try {
        include_once '../includes/config.php';
        $conexion = ConnectDatabase::conectar();

        $usuario_id = $_SESSION['usuario_id'];
        $consulta = $conexion->prepare("SELECT nombre, apellidos, email FROM usuarios WHERE usuario_id = :usuario_id");
        $consulta->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $nombreUsuario = $resultado['nombre'];
        $apellidosUsuario = $resultado['apellidos'];
        $correoUsuario = $resultado['email'];

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conexion = null;
    }
}

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
    <title>Magic Cinema - Butacas</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Open Sans', sans-serif;
            box-shadow: 0 5px 25px 0 rgba(0, 0, 0, 0.3);
            border: 2px solid transparent;
            border-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            border-image-slice: 1;

        }

        table::before {
            background-image: -moz-linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            background-image: -webkit-linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            background-image: -ms-linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            background-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%);
            -webkit-box-shadow: 0 0 20px 0 rgba(255, 88, 96, 0.5);
            box-shadow: 0 0 20px 0 rgba(255, 88, 96, 0.5);
        }

        th,
        td {
            border: 1px solid #3434;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        td {
            color: #fff;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 2px 2px;
            cursor: pointer;
        }

        .boton-limite {
            background-color: #d3d3d3;

        }
    </style>

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
            <div class="item home__cover" data-bg="../assets/img/home/home__bg3.jpg"></div>

        </div>
    </section>

    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content__title">Tipo de Entrada</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">

            <form method="post" class="sign__form">

                <div class="sign__group">
                    <input type="text" id="nombre" name="nombre" class="sign__input" placeholder="Nombre" value="<?php echo $nombreUsuario; ?>" required>
                </div>

                <div class="sign__group">
                    <input type="text" id="apellidos" name="apellidos" class="sign__input" placeholder="Apellidos" value="<?php echo $apellidosUsuario; ?>" required>
                </div>
                <div class="sign__group">
                    <input type="email" id="correo" name="correo" class="sign__input" placeholder="Correo Electronico" value="<?php echo $correoUsuario; ?>" required>
                </div>
                
            </form>


            <table>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <span id="totalEntradasSeleccionadas">0</span>/<span id="maxEntradas"><?php echo $totalButacas; ?></span> Entradas
                    </td>
                </tr>
                <tr>
                    <th>Articulo</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
                <tr>
                    <td>Normal</td>
                    <td id="precioNormal">8.50</td>
                    <td>
                        <button onclick="decrementarCantidad('cantidadNormal')" class="button">-</button>
                        <span id="cantidadNormal">0</span>
                        <button onclick="incrementarCantidad('cantidadNormal')" class="button">+</button>
                    </td>
                    <td id="subtotalNormal">0.00</td>
                </tr>
                <tr>
                    <td>Menores de 13 años</td>
                    <td id="precioMenores">6.50</td>
                    <td>
                        <button onclick="decrementarCantidad('cantidadMenores')" class="button">-</button>
                        <span id="cantidadMenores">0</span>
                        <button onclick="incrementarCantidad('cantidadMenores')" class="button">+</button>
                    </td>
                    <td id="subtotalMenores">0.00</td>
                </tr>
                <tr>
                    <td>Carnet Joven</td>
                    <td id="precioCarnet">6.50</td>
                    <td>
                        <button onclick="decrementarCantidad('cantidadCarnet')" class="button">-</button>
                        <span id="cantidadCarnet">0</span>
                        <button onclick="incrementarCantidad('cantidadCarnet')" class="button">+</button>
                    </td>
                    <td id="subtotalCarnet">0.00</td>
                </tr>
                <tr>
                    <td>Mayores de 65 años</td>
                    <td id="precioMayores">6.50</td>
                    <td>
                        <button onclick="decrementarCantidad('cantidadMayores')" class="button">-</button>
                        <span id="cantidadMayores">0</span>
                        <button onclick="incrementarCantidad('cantidadMayores')" class="button">+</button>
                    </td>
                    <td id="subtotalMayores">0.00</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td id="precio">0.00</td>
                </tr>
            </table>

            <button id="continuarBtn" class="my-3" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;" onclick="return comprobarSeleccion()">Continuar</button>

        </div>

    </section>




    <footer class=" footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6 col-sm-4 col-md-3">
                    <h6 class="footer__title">Sobre nosotros</h6>
                    <ul class="footer__list">
                        <li><a href="QuienesSomos.php">Quienés somos</a></li>
                    </ul>
                </div>
                <div class="col-6 col-sm-4 col-md-3">
                    <h6 class="footer__title">Legal</h6>
                    <ul class="footer__list">
                        <li><a href="AvisLegal.html">Aviso Legal</a></li>
                        <li><a href="CondicionesCompra.php">Condiciones de compra</a></li>
                        <li><a href="politicas.php">Políticas de privacidad</a></li>
                    </ul>
                </div>

                <div class="col-12 col-sm-4 col-md-3">
                    <h6 class="footer__title">Contacto</h6>
                    <ul class="footer__list">
                        <li><a href="tel:+34624233403">+34 624 23 34 03</a></li>
                        <li><a href="mailto:atencionalcliente@magiccinema.es">atencionalcliente@magiccinema.es</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

    <script>

        var maxEntradas = <?php echo $totalButacas; ?>;
        var totalEntradasSeleccionadas = 0;



        function comprobarSeleccion() {
            var cantidadNormal = parseInt(document.getElementById('cantidadNormal').innerText);
            var cantidadMenores = parseInt(document.getElementById('cantidadMenores').innerText);
            var cantidadCarnet = parseInt(document.getElementById('cantidadCarnet').innerText);
            var cantidadMayores = parseInt(document.getElementById('cantidadMayores').innerText);

            var totalSeleccionado = cantidadNormal + cantidadMenores + cantidadCarnet + cantidadMayores;

            var correoUsuario = document.getElementById('correo').value;

            var validaremail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (correoUsuario.trim() === "") {
                alert("El campo de correo electrónico está vacío.");
                return false;
            }

            if (!validaremail.test(correoUsuario)) {
                alert("El correo electrónico no es válido.");
                return false;
            }

            if (totalSeleccionado === maxEntradas) {
                
                var correoUsuario = document.getElementById('correo').value;
                    var precio = parseFloat(document.getElementById('precio').innerText).toFixed(2);
                    var url = "../includes/session.php?precio=" + precio + "&correo=" + correoUsuario;
                    window.location.href = url;
                    return true;
                
            } else {
                alert("Debes seleccionar todas las entradas disponibles antes de continuar.");
                return false; 
            }
        }





        function incrementarCantidad(idCantidad) {
            var cantidadElemento = document.getElementById(idCantidad);
            var cantidad = parseInt(cantidadElemento.innerText);

            if (totalEntradasSeleccionadas < maxEntradas) {
                cantidadElemento.innerText = cantidad + 1;
                totalEntradasSeleccionadas++;
                document.getElementById('totalEntradasSeleccionadas').innerText = totalEntradasSeleccionadas;
                calcularSubtotal(idCantidad);

                if (totalEntradasSeleccionadas === maxEntradas) {
                    document.getElementById(idCantidad + '-incrementar').classList.add('boton-limite');
                }
            }
        }

        function decrementarCantidad(idCantidad) {
            var cantidadElemento = document.getElementById(idCantidad);
            var cantidad = parseInt(cantidadElemento.innerText);

            if (cantidad > 0) {
                cantidadElemento.innerText = cantidad - 1;
                totalEntradasSeleccionadas--;
                document.getElementById('totalEntradasSeleccionadas').innerText = totalEntradasSeleccionadas;
                calcularSubtotal(idCantidad);

                if (totalEntradasSeleccionadas < maxEntradas) {
                    document.getElementById(idCantidad + '-incrementar').classList.remove('boton-limite');
                }
            }
        }

        function calcularSubtotal(idCantidad) {
            var cantidadElemento = document.getElementById(idCantidad);
            var cantidad = parseInt(cantidadElemento.innerText);

            var precioId = 'precio' + idCantidad.replace('cantidad', '');
            var precioElemento = document.getElementById(precioId);
            var precio = parseFloat(precioElemento.innerText);

            var subtotal = cantidad * precio;

            var subtotalId = 'subtotal' + idCantidad.replace('cantidad', '');
            var subtotalElemento = document.getElementById(subtotalId);
            subtotalElemento.innerText = subtotal.toFixed(2);

            actualizarTotal();
        }

        function actualizarTotal() {
            var subtotales = document.querySelectorAll('[id^="subtotal"]');
            var precio = 0;

            subtotales.forEach(function(subtotal) {
                precio += parseFloat(subtotal.innerText);
            });

            var precioElemento = document.getElementById('precio');
            precioElemento.innerText = precio.toFixed(2);
        }
    </script>




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