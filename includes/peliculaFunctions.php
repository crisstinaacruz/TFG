<?php

include_once '../includes/config.php';

class InfoPeliculaHandler
{
    public static function obtenerInformacionPelicula($id_pelicula)
    {
        $conexion = ConnectDatabase::conectar();
        $pelicula = self::obtenerPeliculaPorID($conexion, $id_pelicula);

        if ($pelicula) {
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<div class="col-12">';
            echo '<h1 class="details__title">' . $pelicula['titulo'] . '</h1>';
            echo '</div>';

            echo '<div class="col-12 col-xl-6">';
            echo '<div class="card card--details border border-0" style="background-color: transparent;">';
            echo '<div class="row">';
            echo '<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-5">';
            echo '<div class="card__cover">';
            echo '<img src="' . $pelicula['imagen'] . '" class="card-img-top" alt="' . $pelicula['titulo'] . '">';
            echo '</div>';
            echo '</div>';

            echo '<div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-7">';
            echo '<div class="card__content">';
            echo '<div class="card__wrap">';
            echo '<ul class="card__list">';
            echo '<li> +' . $pelicula['clasificacion'] . '</li>';
            echo '</ul>';
            echo '</div>';
            echo '<ul class="card__meta">';
            echo '<li><span>Genero:</span> <a href="#">' . $pelicula['genero'] . '</a></li>';
            echo '<li><span>Fecha de Lanazamiento:</span> ' . $pelicula['fecha_de_estreno'] . '</li>';
            echo '<li><span>Duración:</span> ' . $pelicula['duracion'] . '</li>';
            echo '</ul>';
            echo '<div class="card__description card__description--details">';
            echo $pelicula['descripcion'] . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-12 col-xl-6">';
            echo '<iframe width="560" height="315" src=' . $pelicula['trailer_url'] . ' frameborder="0" allowfullscreen class="container"></iframe>';
            echo ' </div>';
            echo '</div>';

            return;
        } else {
            echo '<p style="color: #fff;">La película no existe.</p>';
            return;
        }
    }

    public static function obtenerInformacionPeliculaEntrada($id_pelicula)
    {
        $conexion = ConnectDatabase::conectar();

        if ($id_pelicula !== null) {
            echo '<div class="container">';
            echo '<div class="row">';

            $horarios = self::obtenerHorariosPelicula($conexion, $id_pelicula);

            if ($horarios) {
                foreach ($horarios as $horario) {
                    echo '<div class="col-sm-4 mb-3 mb-sm-0">';
                    echo '<div class="card " style="box-shadow: 0 5px 25px 0 rgba(0,0,0,0.3); border: 2px solid transparent; border-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border-image-slice: 1; background-color: #28282d;">';
                    echo '<div class="card-body p-3">';
                    echo '<h4 class="card-title text-white fw-bolder" style ="font-family: \'Open Sans\', sans-serif;">' . $horario['nombre_pelicula'] . '</h4>';

                    if (isset($horario['fecha'])) {
                        $fechaFormateada = date('d-m-Y', strtotime($horario['fecha']));
                        $horaFormateada = date('H:i', strtotime($horario['fecha']));
                    } else {
                        $fechaFormateada = 'Fecha no disponible';
                        $horaFormateada = 'Hora no disponible';
                    }

                    echo '<p class="card-text text-white" style ="font-family: \'Open Sans\', sans-serif;"><strong>Fecha:</strong> ' . $fechaFormateada . '</p>';
                    echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;"><strong>Sala:</strong> ' . $horario['sala_nombre'] . '</p>';
                    echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;"><strong>Sesión:</strong> ' . $horaFormateada . '</p>';
                    echo '<a href="../includes/session.php?horario_id=' . $horario['horario_id'] . '" class="" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">Comprar Entrada</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            } else {
                // Muestra el mensaje de error con el nombre de la película en lugar del ID
                $nombre_pelicula = self::obtenerNombrePeliculaPorID($conexion, $id_pelicula);

                echo '<div class="container">';
                echo '<p style="color: #fff; font-family: \'.Open Sans\', sans-serif;">No hay fecha para la película ' . $nombre_pelicula . '</p>';
                echo '</div>';
            }

            return;
        } else {
            echo 'ID de película no proporcionado.';
            return;
        }
    }

    private static function obtenerPeliculaPorID($conexion, $id_pelicula)
    {
        $sql = "SELECT * FROM peliculas WHERE pelicula_id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id_pelicula, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private static function obtenerHorariosPelicula($conexion, $id_pelicula)
    {
        $sql = "SELECT 
                    p.titulo AS nombre_pelicula,
                    h.fecha AS fecha,
                    s.nombre AS sala_nombre,
                    h.horario_id AS horario_id
                FROM 
                    peliculas p
                INNER JOIN 
                    horarios h ON p.pelicula_id = h.pelicula_id
                INNER JOIN
                    salas s ON h.sala_id = s.sala_id
                WHERE 
                    p.pelicula_id = :id;"; // Ag
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id_pelicula, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultados = [];
            while ($horario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $horario;
            }
            return $resultados;
        } else {
            return false;
        }
    }

    private static function obtenerNombrePeliculaPorID($conexion, $id_pelicula)
    {
        $sql = "SELECT titulo FROM peliculas WHERE pelicula_id = :id_pelicula";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['titulo'];
    }
}


?>