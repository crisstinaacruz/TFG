<?php
session_start();
$total = isset($_SESSION['total']) ? floatval($_SESSION['total']) : 0.00;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Página de Pago - Redsys</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #header {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #header img {
            max-width: 150px;
        }
        ol.steps-wr {
            list-style: none;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }
        ol.steps-wr li {
            text-align: center;
            flex: 1;
        }
        ol.steps-wr li.active .num {
            background-color: #ffa500;
            color: #fff;
        }
        ol.steps-wr li .num {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            margin-bottom: 10px;
        }
        ol.steps-wr li.active .s-text, ol.steps-wr li .s-text {
            color: #333;
        }
        #body {
            padding: 20px;
        }
        .result-mod-wr {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            width: 45%;
        }
        .datosDeLaOperacion {
            font-weight: bold;
            font-size: 18px;
            color: #ffa500;
            margin-bottom: 10px;
        }
        .ticket-header, .ticket-info {
            margin-bottom: 20px;
        }
        .price {
            display: flex;
            justify-content: space-between;
        }
        .table-condensed {
            width: 100%;
            border-collapse: collapse;
        }
        .table-condensed td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table-condensed .text {
            font-weight: bold;
        }
        .form-group input {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn.continue {
            background-color: #ffa500;
            color: #fff;
        }
        .btn.continue:hover {
            background-color: #e69500;
        }
        .btn.cancel {
            background-color: #ddd;
            color: #333;
        }
        .btn.cancel:hover {
            background-color: #ccc;
        }
        #footer {
            background-color: #f7f7f7;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
        }
        .powered {
            font-size: 12px;
            color: #666;
        }
        .buttons-wr {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn.print {
            background-color: #ddd;
            color: #333;
        }
        .btn.print:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
<div id="container">
    <form autocomplete="off" action="comprafin.php" method="get" name="formCuenta">
        
        <header id="header">
            <div class="container">
                <img src="../assets/img/redsys.jpg" alt="Redsys">
                
            </div>
        </header>
        <ol class="steps-wr">
            <li id="s-method" class="step">
                <span class="num">1</span>
                <p class="s-text">Seleccione método de pago</p>
            </li>
            <li id="s-auth" class="step">
                <span class="num">2</span>
                <p class="s-text">Comprobación autenticación</p>
            </li>
            <li id="s-connect" class="step">
                <span class="num">3</span>
                <p class="s-text">Solicitando Autorización</p>
            </li>
            <li id="s-result" class="step active">
                <span class="num">4</span>
                <p class="s-text">Resultado Transacción</p>
            </li>
        </ol>
        <div id="body">
            <div class="result-mod-wr">
                <div class="datosDeLaOperacion">Datos de la operación</div>
                <div class="ticket-header">
                    <div class="price">
                        <div class="left">
                            <p>Importe</p>
                        </div>
                        <div class="right">
                            <p><?php echo number_format($total, 2); ?>&nbsp;Euros</p>
                        </div>
                    </div>
                </div>
                <div class="ticket-info">
                    <table class="table-condensed">
                        <tr id="filaNombreComercio">
                            <td class="text">Código Comercio:</td>
                            <td class="numeric">384953</td>
                        </tr>
                        <tr id="filaCodigoComercio">
                            <td class="text">Terminal:</td>
                            <td class="numeric">3048-1</td>
                        </tr>
                        <tr id="filaNumeroPedido">
                            <td class="text">Número pedido:</td>
                            <td class="numeric">pedido679</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="buttons-wr">
                <button type="button" class="btn print">Imprimir</button>
                <button type="submit" class="btn continue">Continuar</button>
            </div>
        </div>
    </form>
    <footer id="footer">
        <p class="powered">Powered by Redsys</p>
        <div id="footerGeneral">
            <div class="copyright">
                <center>
                    <text>&copy; 2024 Redsys Servicios de Procesamiento. SL - Todos los derechos reservados.</text>
                </center>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
