<?php
session_start();

require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
require_once("../../_Varios.php");

$conexion = obtenerPdoConexionBD();
$restauranteDAO = new Restaurante\RestauranteDAO($conexion);

$tipoUsuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;
$usuarioId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

// Verificar que el usuario este autenticado y sea del tipo adecuado
if (isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id'];
    $tipoUsuario = $restauranteDAO->obtenerTipoUsuario($usuarioId);

    if ($tipoUsuario !== 2) {
        header("Location: inicio.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}


$restauranteId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$restauranteId) {
    echo "ID de restaurante no proporcionado.";
    exit;
}

$conexion = obtenerPdoConexionBD();
$restauranteDAO = new Restaurante\RestauranteDAO($conexion);

$restaurante = $restauranteDAO->obtenerPorId($restauranteId);

if (!$restaurante) {
    echo "Restaurante no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $numero_direccion = $_POST['numero_direccion'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $capacidad_total = $_POST['capacidad_total'] ?? '';
    $horario_apertura = $_POST['horario_apertura'] ?? '';
    $horario_cierre = $_POST['horario_cierre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio_medio = $_POST['precio_medio'] ?? '';

    $restaurante->setNombre($nombre);
    $restaurante->setDireccion($direccion);
    $restaurante->setNumero($numero_direccion);
    $restaurante->setCiudad($ciudad);
    $restaurante->setTelefono($telefono);
    $restaurante->setEmail($email);
    $restaurante->setCapacidadTotal($capacidad_total);
    $restaurante->setHorarioApertura($horario_apertura);
    $restaurante->setHorarioCierre($horario_cierre);
    $restaurante->setDescripcion($descripcion);
    $restaurante->setPrecioMedio($precio_medio);

    if ($restauranteDAO->actualizarRestaurante($restaurante)) {
        header("Location: detalle_restaurante.php?id=" . $restaurante->getId());
        exit;
    } else {
        echo "Error al actualizar el restaurante.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Restaurante</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="header">
    <h1>Editar <?php echo htmlspecialchars($restaurante->getNombre()); ?></h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Inicio</a>
        <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
    </div>
</div>
<div class="formulario" style="min-width: 50%">
    <form action="editar_restaurante.php?id=<?php echo htmlspecialchars($restaurante->getId()); ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <label for="nombre">Nombre de restaurante:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($restaurante->getNombre()); ?>" required><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($restaurante->getTelefono()); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($restaurante->getEmail()); ?>" required><br>

                <label for="capacidad_total">Capacidad Total:</label>
                <input type="number" id="capacidad_total" name="capacidad_total" class="form-control" value="<?php echo htmlspecialchars($restaurante->getCapacidadTotal()); ?>" required><br>


                <label for="precio_medio">Precio Medio por Persona (€):</label>
                <input type="number" id="precio_medio" name="precio_medio" step="0.01" class="form-control" value="<?php echo htmlspecialchars($restaurante->getPrecioMedio()); ?>" required><br>


                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50"><?php echo htmlspecialchars($restaurante->getDescripcion()); ?></textarea><br><br>
            </div>

            <div class="col-md-6">
                <label for="direccion">Dirección Completa:</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo htmlspecialchars($restaurante->getDireccion()); ?>" required placeholder="Escribe la dirección"><br>

                <label for="numero">Número:</label>
                <input type="text" id="numero_direccion" name="numero_direccion" class="form-control" value="<?php echo htmlspecialchars($restaurante->getNumero()); ?>" required><br>

                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" class="form-control" value="<?php echo htmlspecialchars($restaurante->getCiudad()); ?>" required><br>

                <label for="horario_apertura">Horario de Apertura:</label>
                <input type="time" id="horario_apertura" name="horario_apertura" class="form-control" value="<?php echo htmlspecialchars($restaurante->getHorarioApertura()); ?>" required><br>

                <label for="horario_cierre">Horario de Cierre:</label>
                <input type="time" id="horario_cierre" name="horario_cierre" class="form-control" value="<?php echo htmlspecialchars($restaurante->getHorarioCierre()); ?>" required><br>

            </div>
        </div>
        <button type="submit" class="btn btn-primary">Editar Restaurante</button>
    </form>
</div>
</body>
</html>
