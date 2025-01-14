<?php
require_once("../../_Varios.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $conexion = obtenerPdoConexionBD();

    $consulta = $conexion->prepare("SELECT id, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?");

    if ($consulta->execute([$nombre_usuario])) {
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];

            header("Location: inicio.php");
            exit;
        } else {
            $mensajeError = "Nombre de usuario o contraseña incorrectos. Por favor, inténtelo de nuevo.";
        }
    } else {
        $mensajeError = "Hubo un problema al iniciar sesión. Por favor, inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Agenda Telefónica</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
<div class="header">
    <h1>Inicio de Sesión</h1>
</div>
<div class="buttons">
    <a href="inicio.php" class="button">Volver</a>
</div>
<div class="formulario">
    <form action="" method="post">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <input type="submit" value="Iniciar Sesión">
    </form>
    <?php if (isset($mensajeError)) { ?>
        <div class="error-message"><?= $mensajeError ?></div>
    <?php } ?>

        <p>¿No tienes una cuenta?</p>
        <a href="registro.php" class="button">Registrar Cliente</a>
        <a href="registro_restaurante.php" class="button">Registrar Dueño de Restaurante</a>

</div>

</body>
</html>
