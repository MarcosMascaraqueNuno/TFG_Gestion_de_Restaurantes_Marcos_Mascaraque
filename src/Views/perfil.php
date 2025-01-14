<?php
require_once("../../_Varios.php");


if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$conexion = obtenerPdoConexionBD();
$usuarioId = $_SESSION['usuario_id'];

$consulta = $conexion->prepare("SELECT nombre, apellidos, email, tipo_usuario FROM usuarios WHERE id = ?");
$consulta->execute([$usuarioId]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = $_POST['nombre'];
    $nuevoEmail = $_POST['email'];

    if (!empty($nuevoNombre) && !empty($nuevoEmail)) {
        $consulta = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $consulta->execute([$nuevoNombre, $nuevoEmail, $usuarioId]);
        header("Location: perfil.php");
        exit;
    } else {
        $mensaje = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
<div class="header">
    <h1>Mi Perfil</h1>
</div>

<div class="buttons">
    <a href="inicio.php" class="button">Volver a los restaurantes</a>
    <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
</div>

<div class="perfil">
    <h2>Información de Usuario</h2>
    <ul>
        <li><strong>Nombre:</strong> <?php echo $usuario['nombre']; ?></li>
        <li><strong>Apellidos:</strong> <?php echo $usuario['apellidos']; ?></li>
        <li><strong>Correo Electrónico:</strong> <?php echo $usuario['email']; ?></li>
    </ul>

    <a href="editar_perfil.php" class="button">Editar Perfil</a>
</div>

</body>
</html>
