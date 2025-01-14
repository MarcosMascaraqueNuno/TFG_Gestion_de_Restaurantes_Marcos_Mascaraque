<?php
require_once("../../_Varios.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];

    if ($contrasena !== $confirmar_contrasena) {
        echo "Las contraseñas no coinciden. Por favor, inténtelo de nuevo.";
    } else {
        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

        $conexion = obtenerPdoConexionBD();

        $consulta = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, email, nombre, apellidos, telefono) VALUES (?, ?, ?, ?, ?, ?)");

        if ($consulta->execute([$nombre_usuario, $contrasena_hasheada, $email, $nombre, $apellidos, $telefono])) {
            header("Location: inicio.php");
            exit;
        } else {
            echo "Hubo un problema al registrar el usuario. Por favor, inténtelo de nuevo.";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Agenda Telefónica</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <div class="header">
        <h1>Registro</h1>
    </div>
    <div class="buttons">
        <a href="login.php" class="button">Iniciar Sesión</a>
        <a href="inicio.php" class="button">Volver</a>
    </div>
    <div class="formulario">
        <form action="" method="post">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" pattern="^(?=.*[A-Za-z]).{6,}$" title="Debe tener al menos 6 caracteres, incluyendo al menos una mayúscula y un número." required>

            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" name="confirmar_contrasena" pattern="^(?=.*[A-Za-z]).{6,}$" title="Debe tener al menos 6 caracteres, incluyendo al menos una mayúscula y un número." required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" required>

            <input type="submit" value="Registrar">


            <p><a href="registro_restaurante.php">¿Eres un restaurante?</a></p>
        </form>
</div>
</body>
</html>
