<?php

class BarHandler
{
    public static function obtenerBar($pdo)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM bar ORDER BY bar_id");
            $stmt->execute();

            $bar = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $precio = isset($_SESSION['precio']) ? floatval($_SESSION['precio']) : 0.00;
            ?>
            <div class="row d-flex justify-content-center">
                <?php foreach ($bar as $producto) : ?>
                    <div class="col-md-5 col-12">
                        <div class="card" style="box-shadow: 0 5px 25px 0 rgba(0,0,0,0.3); max-width: 1040px; border: 2px solid transparent; border-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border-image-slice: 1; background-color: #28282d;">
                            <img src="<?php echo $producto['imagen']; ?>" class="card-img-top" alt="Producto">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #fff; font-family: 'Open Sans', sans-serif;"><?php echo htmlspecialchars($producto['titulo']); ?></h5>
                                <p class="card-text" style="color: #fff; font-family: 'Open Sans', sans-serif;"><?php echo htmlspecialchars($producto['precio']); ?> €</p>
                                
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-light" type="button" onclick="decrementarCantidad(<?php echo $producto['bar_id']; ?>)">-</button>
                                    <span id="cantidad<?php echo $producto['bar_id']; ?>" class="form-control text-center bg-dark text-white">0</span>
                                    <input type="hidden" id="cantidadInput<?php echo $producto['bar_id']; ?>" value="0">
                                    <button class="btn btn-outline-light" type="button" onclick="incrementarCantidad(<?php echo $producto['bar_id']; ?>)">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-section">
        <h4 style="color: #fff; font-size: 24px;">Total:</h4>
        <p id="precio" style="color: #fff; font-size: 24px;">0.00 €</p>

        <div class="button-group">
            <button class="btn" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); color: #fff; padding: 10px 20px; border-radius: 5px;">
                    <a href="../includes/session.php" style="text-decoration: none; color: #000;">Continuar sin producto</a>
                </button>
            <button class="btn" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); color: #fff; padding: 10px 20px; border-radius: 5px;">
                <a href="#" style="text-decoration: none; color: #fff;" onclick="enviarSeleccion()">Añadir</a>
            </button>
        </div>
    </div>

            <script>
                var totalPrecio = 0;

                function incrementarCantidad(idProducto) {
                    var cantidadElemento = document.getElementById('cantidad' + idProducto);
                    var cantidadInput = document.getElementById('cantidadInput' + idProducto);
                    var cantidad = parseInt(cantidadElemento.innerText);
                    cantidad++;
                    cantidadElemento.innerText = cantidad;
                    cantidadInput.value = cantidad;
                    actualizarPrecioTotal();
                }

                function decrementarCantidad(idProducto) {
                    var cantidadElemento = document.getElementById('cantidad' + idProducto);
                    var cantidadInput = document.getElementById('cantidadInput' + idProducto);
                    var cantidad = parseInt(cantidadElemento.innerText);
                    if (cantidad > 0) {
                        cantidad--;
                        cantidadElemento.innerText = cantidad;
                        cantidadInput.value = cantidad;
                        actualizarPrecioTotal();
                    }
                }

                function actualizarPrecioTotal() {
                    var total = 0;
                    var productos = document.querySelectorAll('[id^="cantidadInput"]');
                    productos.forEach(function(producto) {
                        var cantidad = parseInt(producto.value);
                        if (cantidad > 0) {
                            var precio = parseFloat(producto.closest('.card-body').querySelector('.card-text').innerText.replace(' €', ''));
                            total += cantidad * precio;
                        }
                    });
                    totalPrecio = total.toFixed(2);
                    document.getElementById('precio').innerText = totalPrecio + ' €';
                }

                function enviarSeleccion() {
                    var productos = document.querySelectorAll('[id^="cantidadInput"]');
                    var seleccion = [];
                    productos.forEach(function(producto) {
                        var cantidad = parseInt(producto.value);
                        if (cantidad > 0) {
                            var idProducto = producto.id.replace('cantidadInput', '');
                            seleccion.push({ id: idProducto, cantidad: cantidad });
                        }
                    });
                    
                    var url = new URL('../includes/session.php', window.location.origin);
                    url.searchParams.append('total', totalPrecio);
                    url.searchParams.append('productos', JSON.stringify(seleccion));
                    
                    window.location.href = url.toString();
                }

            </script>

            <?php
            return $bar;
        } catch (PDOException $e) {
            error_log("Error al obtener productos del bar: " . $e->getMessage());
            return false;
        }
    }
}
?>
