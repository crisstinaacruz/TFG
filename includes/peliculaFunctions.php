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
            echo '<li><span>Género:</span> <a href="#">' . $pelicula['genero'] . '</a></li>';
            echo '<li><span>Fecha de lanzamiento:</span>  ' . date('d-m-Y', strtotime($pelicula['fecha_de_estreno'])) . '</li>';
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
        function obtenerNombrePeliculaPorID($conexion, $id_pelicula)
    {
        $sql = "SELECT titulo FROM peliculas WHERE pelicula_id = :id_pelicula";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['titulo'];
    }
        
        

        if ($id_pelicula !== null) {
            echo '<div class="container">';
            echo '<div class="row">';

            $horarios = self::obtenerHorariosPelicula($conexion, $id_pelicula);
            echo '<h3>Horarios de hoy</h3>';
            if (!empty($horarios['hoy'])) {
                
                foreach ($horarios['hoy'] as $horario) {
                    echo '<div class="col-sm-4 mt-3 mb-3 mb-sm-0">';
                        echo '<div class="card " style="box-shadow: 0 5px 25px 0 rgba(0,0,0,0.3); border: 2px solid transparent; border-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border-image-slice: 1; background-color: #28282d;">';
                            echo '<div class="card-body p-3">';
                                echo '<h4 class="card-title text-white fw-bolder" style ="font-family: \'Open Sans\', sans-serif;">' . $horario['nombre_pelicula'] . '</h4>';
                                echo '<p class="card-text text-white" style ="font-family: \'Open Sans\', sans-serif;"><strong>Fecha:</strong> ' . date('d-m-Y', strtotime($horario['fecha'])) . '</p>';
                                echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;">' . $horario['sala_nombre'] . '</p>';
                                echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;"><strong>Sesión:</strong> ' . date('H:i', strtotime($horario['fecha'])) . '</p>';
                                echo '<a href="../includes/session.php?horario_id=' . $horario['horario_id'] . '" class="" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">Comprar Entrada</a>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            else{
                $nombre_pelicula = obtenerNombrePeliculaPorID($conexion, $id_pelicula);

                echo '<div class="container mt-3 mb-2">';
                echo '<p style="color: #fff; font-family: \'.Open Sans\', sans-serif;">No hay fecha para la película ' . $nombre_pelicula . '</p>';
                echo '</div>';
            }
            echo '<h3>Horarios próximos días</h3>';
            if (!empty($horarios['proximos_dias'])) {
                
                foreach ($horarios['proximos_dias'] as $horario) {

                    echo '<div class="col-sm-4 mt-3 mb-3 mb-sm-0">';
                    echo '<div class="card " style="box-shadow: 0 5px 25px 0 rgba(0,0,0,0.3); border: 2px solid transparent; border-image: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border-image-slice: 1; background-color: #28282d;">';
                    echo '<div class="card-body p-3">';
                    echo '<h4 class="card-title text-white fw-bolder" style ="font-family: \'Open Sans\', sans-serif;">' . $horario['nombre_pelicula'] . '</h4>';
                    echo '<p class="card-text text-white" style ="font-family: \'Open Sans\', sans-serif;"><strong>Fecha:</strong> ' . date('d-m-Y', strtotime($horario['fecha'])) . '</p>';
                    echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;">' . $horario['sala_nombre'] . '</p>';
                    echo '<p class="text-white" style="font-family: \'Open Sans\', sans-serif;"><strong>Sesión:</strong> ' . date('H:i', strtotime($horario['fecha'])) . '</p>';
                    echo '<a href="../includes/session.php?horario_id=' . $horario['horario_id'] . '" class="" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">Comprar Entrada</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                }
            
            } else {
                $nombre_pelicula = obtenerNombrePeliculaPorID($conexion, $id_pelicula);

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
    date_default_timezone_set('Europe/Madrid');
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

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
                p.pelicula_id = :id
                AND (
                    h.fecha::date > :fecha_actual::date
                    OR (h.fecha::date = :fecha_actual::date AND h.fecha::time >= :hora_actual::time)
                )
            ORDER BY
                h.fecha ASC";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_pelicula, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
    $stmt->bindParam(':hora_actual', $hora_actual, PDO::PARAM_STR);
    $stmt->execute();

    $resultados = [];
    while ($horario = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $horario;
    }

    $horarios_hoy = [];
    $horarios_proximos_dias = [];

    foreach ($resultados as $horario) {
        $fecha_horario = date('Y-m-d', strtotime($horario['fecha']));

        if ($fecha_horario == $fecha_actual) {
            $horarios_hoy[] = $horario;
        } else {
            $horarios_proximos_dias[] = $horario;
        }
    }

    return [
        'hoy' => $horarios_hoy,
        'proximos_dias' => $horarios_proximos_dias
    ];
}

}

?>