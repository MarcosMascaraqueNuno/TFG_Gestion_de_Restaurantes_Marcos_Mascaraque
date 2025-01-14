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

$consulta = $conexion->prepare("SELECT nombre, apellidos, email, contrasena FROM usuarios WHERE id = ?");
$consulta->execute([$usuarioId]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = $_POST['nombre'];
    $nuevoEmail = $_POST['email'];
    $contrasenaActual = $_POST['contrasena_actual'];
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $nuevaContrasenaRepetida = $_POST['nueva_contrasena_repetida'];

    if (password_verify($contrasenaActual, $usuario['contrasena'])) {

        if (!empty($nuevaContrasena) && $nuevaContrasena === $nuevaContrasenaRepetida) {
            $hashContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

            $consulta = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ?, contrasena = ? WHERE id = ?");
            $consulta->execute([$nuevoNombre, $nuevoEmail, $hashContrasena, $usuarioId]);
        } else {
            $mensaje = "Por favor, completa los campos de nueva contraseña y repetición de contraseña correctamente.";
        }
    } else {
        $mensaje = "La contraseña actual es incorrecta. Por favor, inténtalo de nuevo.";
    }

    header("Location: perfil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
<div class="header">
    <h1>Editar Perfil</h1>
</div>

<div class="buttons">
    <a href="perfil.php" class="button">Volver a Mi Perfil</a>
    <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
</div>

<div class="perfil">
    <h2>Editar Información de Usuario</h2>
    <form method="post" action="editar_perfil.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $usuario['apellidos']; ?>"><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>"><br>

        <label for="contrasena_actual">Contraseña Actual:</label>
        <input type="password" name="contrasena_actual" required><br>

        <label for="nueva_contrasena">Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" placeholder="Dejar en blanco si no deseas cambiarla"><br>

        <label for="nueva_contrasena_repetida">Repetir Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena_repetida" placeholder="Dejar en blanco si no deseas cambiarla"><br>

        <input type="submit" value="Guardar Cambios">
    </form>
    <?php if (isset($mensaje)) { ?>
        <div class="error-message"><?= $mensaje ?></div>
    <?php } ?>
</div>

</body>
</html>
