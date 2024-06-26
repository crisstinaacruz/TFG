<?php

include_once '../includes/config.php';

class BillboardHandler
{
    private static function obtenerPeliculas($sql)
    {
        $conexion = ConnectDatabase::conectar();
        $resultado = $conexion->query($sql);
        return $resultado;
    }

    private static function mostrarCarousel($resultado)
    {
        echo '
            <section class="home">

                <div class="owl-carousel home__bg">
                    <div class="item home__cover" data-bg="../assets/img/home/home__bg.jpg"></div>
                    <div class="item home__cover" data-bg="../assets/img/home/home__bg2.jpg"></div>
                    <div class="item home__cover" data-bg="../assets/img/home/home__bg3.jpg"></div>
                    <div class="item home__cover" data-bg="../assets/img/home/home__bg4.jpg"></div>
                </div>


                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="home__title"><b>TOP PELÍCULAS</b></h1>

                            <button class="home__nav home__nav--prev" type="button">
                                <i class="icon ion-ios-arrow-round-back"></i>
                            </button>
                            <button class="home__nav home__nav--next" type="button">
                                <i class="icon ion-ios-arrow-round-forward"></i>
                            </button>
                        </div>

                        <div class="col-12">
                            <div class="owl-carousel home__carousel">';

        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
            self::mostrarPelicula($registro);
        }

        echo '</div>
                        </div>
                    </div>
                </div>
            </section>';
    }

    private static function mostrarPelicula($registro)
    {
        $id_pelicula = $registro['pelicula_id'];
        $imagen_ruta = $registro['imagen'];
        $titulo = $registro['titulo'];
        $genero = $registro['genero'];

        echo '<div class="item">
            <div class="card card--big border border-0" style="background-color: transparent;">
                <a href="../includes/session.php?id_pelicula=', $id_pelicula, '"><img src="', $imagen_ruta, '" class="card-img-top" alt="', $titulo, '"></a>

                <div class="card__content">
                    <h3 class="card__title">', $titulo, '</h3>
                    <span class="card__category">
                        <a href="#">', $genero, '</a>
                    </span>
                </div>
            </div>
        </div>';
    }

    public static function mostrarTopPeliculas()
    {
        $sql = "SELECT * FROM peliculas ORDER BY titulo ASC";
        $resultado = self::obtenerPeliculas($sql);

        if ($resultado->rowCount() > 0) {
            self::mostrarCarousel($resultado);
        }
    }

    public static function mostrarCartelera()
    {
        $sql = "SELECT * FROM peliculas";
        $resultado = self::obtenerPeliculas($sql);

        if ($resultado->rowCount() > 0) {

            echo '<div class="container">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                        <div class="row">';

            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
                self::mostrarPeliculaCartelera($registro);
            }

            echo '</div>
                    </div>
                </div>
            </div>';
        }
    }

    private static function mostrarPeliculaCartelera($registro)
{
    $id_pelicula = $registro['pelicula_id'];
    $imagen_ruta = $registro['imagen'];
    $titulo = $registro['titulo'];
    $genero = $registro['genero'];
    $edad = $registro['clasificacion'];
    $descripcion = $registro['descripcion'];

    $descripcion_corta = strlen($descripcion) > 220 ? substr($descripcion, 0, 220) . "..." : $descripcion;

    echo '<div class="col-6 col-sm-12 col-lg-6">
            <div class="card card--list border border-0" style="background-color: transparent;">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <a href="../includes/session.php?id_pelicula=', $id_pelicula, '"><img src="', $imagen_ruta, '" class="card-img-top" alt="', $titulo, '"></a>
                    </div>

                    <div class="col-12 col-sm-8">
                        <div class="card__content mt-1">
                            <h3 class="card__title mt-1"><a href="../includes/session.php?id_pelicula=', $id_pelicula, '">', $titulo, '</a></h3>
                            <span class="card__category">
                                <a href="#">', $genero, '</a>
                            </span>

                            <div class="card__wrap">
                                <ul class="card__list">
                                    <li> + ', $edad, '</li>
                                </ul>
                            </div>

                            <div class="card__description">
                                <p>', $descripcion_corta, '</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}

}
?>
