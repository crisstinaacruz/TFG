<?php


class Navbar
{

    private static function obtenerUsuarioId($email) {
        $conexion = ConnectDatabase::conectar();
        if ($conexion) {
            $query = "SELECT usuario_id FROM usuarios WHERE email = :email";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['usuario_id'] : null;
        }
        return null;
    }


    public static function renderAuthenticatedNavbar($email)
    {
        
        $user_id = self::obtenerUsuarioId($email);
        $_SESSION['usuario_id'] = $user_id;

        if ($email == "admin@gmail.com") {          
            echo '<header class="header">
            <div class="header__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="header__content">
        
                                <a href="../index.php" class="header__logo">
                                    <img src="../../assets/img/Magic_Cinema-removebg-preview.png" alt="">
                                </a>
        
                                <ul class="header__nav">
                                    <li class="header__nav-item">
                                        <a href="../views/cartelera.php" class="header__nav-link">Cartelera</a>
                                    </li>
        
                                    <li class="header__nav-item">
                                        <a href="../views/promociones.php" class="header__nav-link">Promociones</a>
                                    </li>
        
                                    <li class="header__nav-item">
                                        <a href="../views/experiencias.php" class="header__nav-link">Experiencias</a>
                                    </li>
        
                                    <li class="header__nav-item">
                                        <a href="../views/contactanos.php" class="header__nav-link">Contáctanos</a>
                                    </li>
                                </ul>
        
                                <div class="header__auth">
                                <button class="btn btn-primary mx-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">
                                ' . $email . '
                            </button>
                                </div>
        
                                <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                                    <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="staticBackdropLabel">' . $email . '</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <div>
                                            <div class="row my-3">
                                                <div class="col-12 my-2">
                                                    <a href="../views/user.php">Ver perfil</a>
                                                </div>
                                                <div class="col-12 my-2">
                                                    <a href="../views/crud/peliculas/administrador_pelicula.php">Administradores</a>
                                                </div>
                                            </div>
                                            <form class="d-flex" role="Cerrar sesion" method="POST" action="../includes/cerrarSesion.php">
                                                <button type="submit" class="btn btn-danger" name="logout" style="color:#fff;">Cerrar Sesión</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <button class="header__btn mx-3" type="button">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
        
                        </div>
                    </div>
                </div>
            </div>
        </header>';
        } else {
            echo '
            <header class="header">
                <div class="header__wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="header__content">
    
                                    <a href="../../index.php" class="header__logo">
                                        <img src="../../assets/img/Magic_Cinema-removebg-preview.png" alt="">
                                    </a>
    
                                    <ul class="header__nav">
                                        <li class="header__nav-item">
                                            <a href="../../views/cartelera.php" class="header__nav-link">Cartelera</a>
                                        </li>
    
                                        <li class="header__nav-item">
                                            <a href="../../views/promociones.php" class="header__nav-link">Promociones</a>
                                        </li>
    
                                        <li class="header__nav-item">
                                            <a href="../../views/experiencias.php" class="header__nav-link">Experiencias</a>
                                        </li>
    
                                        <li class="header__nav-item">
                                            <a href="../../views/contactanos.php" class="header__nav-link">Contáctanos</a>
                                        </li>
                                    </ul>
    
                                    <div class="header__auth">
                                        <button class="btn btn-primary mx-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop" style="background: linear-gradient(90deg, #ff55a5 0%, #ff5860 100%); border: none; color: #fff; padding: 10px 20px; border-radius: 5px;">
                                            ' . $email . '
                                        </button>
                                    </div>
    
                                    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="staticBackdropLabel">' . $email . '</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <div>
                                                <div class="row my-3">
                                                    <div class="col-12 my-2">
                                                        <a href="../views/user.php">Ver perfil</a>
                                                    </div>
                                                </div>
                                                <form class="d-flex" role="Cerrar sesion" method="POST" action="../../includes/cerrarSesion.php">
                                                    <button type="submit" class="btn btn-danger" name="logout" style="color:#fff;">Cerrar Sesión</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <button class="header__btn mx-3" type="button">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
    
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            ';
        }
    }

    public static function renderUnauthenticatedNavbar()
    {
        echo '
        <header class="header">
            <div class="header__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="header__content">

                                <a href="../../index.php" class="header__logo">
                                    <img src="../../assets/img/Magic_Cinema-removebg-preview.png" alt="">
                                </a>

                                <ul class="header__nav">
                                    <li class="header__nav-item">
                                        <a href="../../views/cartelera.php" class="header__nav-link">Cartelera</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/promociones.php" class="header__nav-link">Promociones</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/experiencias.php" class="header__nav-link">Experiencias</a>
                                    </li>

                                    <li class="header__nav-item">
                                        <a href="../../views/contactanos.php" class="header__nav-link">Contáctanos</a>
                                    </li>
                                </ul>

                                <div class="header__auth">
                                    <a href="../../views/Login.php" class="header__sign-in mx-1">
                                        <i class="icon ion-ios-log-in"></i>
                                        <span>Iniciar Sesión</span>
                                    </a>
                                </div>

                                <button class="header__btn" type="button">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        ';
    }
}
