<?php


$user= 'root';
$password= 'root';
$database= 'magiccinema';



if (isset($_POST["enviar"])){

    $name= $_POST["nombre"];
    $lastname= $_POST["apellido"];
    $email= $_POST["email"];
    $pass= $_POST["contra"];


    try {
        $db = new PDO("pgsql:host=db;dbname=$database", $user, $password);
        foreach($db->query("SELECT nombre, email FROM Usuarios WHERE email='$email'") as $row) {
        }
        $verEmail = $row['email'];
    } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
    }

        try {
            $db = new PDO("pgsql:host=db;dbname=$database", $user, $password);
            foreach($db->query("INSERT INTO Usuarios (nombre, apellidos, email, password) VALUES ('$name','$lastname', '$email', '$pass')") as $row) {
        }
            
            
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }

  
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>

<body>

    <form method="post">
        <label for="nom">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Inserta tu nombre" required>
        <label for="ape">Apellidos</label>
        <input type="text" name="apellido" id="apellido" placeholder="Inserta tu apellido" required>
        <label for="ema">Email</label>
        <input type="email" name="email" id="email" placeholder="Inserta tu email" required>
        <label for="contr">Contraseña</label>
        <input type="text" name="contra" id="contra" placeholder="Inserta tu contraseña" required>
        <button type="submit" name="enviar">Enviar</button>
    </form>
       
</body>

</html>