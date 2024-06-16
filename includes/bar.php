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
                                <button class="" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">
                                <a href="../includes/session.php?total=<?php echo $precio + $producto['precio']; ?>&bar=<?php echo $producto['bar_id']; ?>" style="text-decoration: none; color: #fff;">Continuar</a>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">
                <a href="../includes/session.php?total=<?php echo $precio ?>" style="text-decoration: none; color: #fff;">Continuar sin producto</a>
            </button>

<?php
            return $bar;
        } catch (PDOException $e) {
            error_log("Error al obtener productos del bar: " . $e->getMessage());
            return false;
        }
    }
}

?>