<?php

class ConnectDatabase{

    public static function conectar()
    {
        try {
            $db_nombre = "magiccinema";
            $db_usuario = "root";
            $db_pass = "root";
            
            $base = new PDO("pgsql:host=db;dbname=$db_nombre", $db_usuario, $db_pass);
            $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $base;

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}

?>
