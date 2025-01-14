<?php
session_start();

require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
require_once("../Models/Reserva/ReservaDAO.php");
require_once("../../_Varios.php");

use Reserva\ReservaDAO;
use Restaurante\RestauranteDAO;

$conexion = obtenerPdoConexionBD();

$restauranteDAO = new RestauranteDAO($conexion);
$reservaDAO = new ReservaDAO($conexion);

$reservaDAO->actualizarReservasFinalizadas();

// Obtener el valor de criterio y tipo de comida
$criterio = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$tipoComida = isset($_GET['tipo_comida']) ? $_GET['tipo_comida'] : '';
$ciudadSeleccionada = isset($_GET['ciudad']) ? $_GET['ciudad'] : '';

// Verificar el tipo de usuario desde la sesion
$tipoUsuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;
$usuarioId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

// Verifica si el usuario está logueado y obtener el tipo de usuario
if (isset($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id'];
    $tipoUsuario = $restauranteDAO->obtenerTipoUsuario($usuarioId);
} else {
    $tipoUsuario = null;
}


if ($tipoUsuario == 2 && $usuarioId) {
    // Solo los restaurantes que pertenecen al propietario
    $restaurantes = $restauranteDAO->obtenerRestaurantesPorPropietario($usuarioId, $criterio, $tipoComida, $ciudadSeleccionada);
} else {
    // Mostrar todos los restaurantes a usuarios no logueados y tipo 1
    $restaurantes = $restauranteDAO->buscarRestaurantes($criterio, $tipoComida, $ciudadSeleccionada);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

<div class="header">
    <h1>Restaurantes</h1>
    <div class="buttons">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- Si el usuario está logueado -->
            <?php if ($tipoUsuario == 1): ?>
                <a href="historial_reservas.php" class="button">Historial de Reservas</a>
                <a href="perfil.php" class="button">Perfil</a>
                <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
            <?php elseif ($tipoUsuario == 2): ?>
                <!-- Usuario tipo 2 -->
                <a href="agregar_restaurante.php" class="button">Añadir Restaurante</a>
                <a href="perfil.php" class="button">Perfil</a>
                <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
            <?php endif; ?>
        <?php else: ?>
            <!-- Si el usuario no está logueado -->
            <a href="login.php" class="button">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</div>

<div class="search-bar">
    <form method="get" action="" id="search-form">
        <input type="text" name="buscar" id="buscar" placeholder="Buscar Restaurante..."
               value="<?php echo htmlspecialchars($criterio); ?>">

        <!-- Filtro por tipo de comida -->
        <select name="tipo_comida" id="tipo-comida">
            <option value="">Selecciona tipo de comida</option>
            <?php
            $sql = "SELECT id, nombre FROM tipos_comida";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $tiposComida = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tiposComida as $tipo) {
                $selected = (isset($_GET['tipo_comida']) && $_GET['tipo_comida'] == $tipo['id']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($tipo['id']) . '" ' . $selected . '>' . htmlspecialchars($tipo['nombre']) . '</option>';
            }
            ?>
        </select>

        <!-- Filtro por ciudad -->
        <select name="ciudad" id="ciudad">
            <option value="">Selecciona una ciudad</option>
            <?php
            $sqlCiudades = "SELECT DISTINCT ciudad FROM restaurantes WHERE ciudad IS NOT NULL AND ciudad != ''";
            $stmtCiudades = $conexion->prepare($sqlCiudades);
            $stmtCiudades->execute();
            $ciudades = $stmtCiudades->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ciudades as $ciudad) {
                $selected = (isset($_GET['ciudad']) && $_GET['ciudad'] == $ciudad['ciudad']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($ciudad['ciudad']) . '" ' . $selected . '>' . htmlspecialchars($ciudad['ciudad']) . '</option>';
            }
            ?>
        </select>
    </form>
</div>


<div class="content">
    <?php if (empty($restaurantes)): ?>
        <p>No se encontraron restaurantes.</p>
    <?php else: ?>
        <?php foreach ($restaurantes as $restauranteDTO): ?>
            <div class="restaurant-card">
                <!-- Nombre del restaurante y nivel de precio -->
                <h3>
                    <?php echo htmlspecialchars($restauranteDTO->getNombre()); ?>
                    <span class="nivel-precio">
                        <?php echo str_repeat('€', ($restauranteDTO->getPrecioMedio() <= 15) ? 1 : (($restauranteDTO->getPrecioMedio() <= 30) ? 2 : 3)); ?>
                    </span>

                </h3>

                <!-- Foto del restaurante -->
                <?php if ($restauranteDTO->getUrlFoto()): ?>
                    <img src="<?php echo htmlspecialchars($restauranteDTO->getUrlFoto()); ?>"
                         alt="Foto de <?php echo htmlspecialchars($restauranteDTO->getNombre()); ?>">
                <?php endif; ?>

                <!-- Ciudad del restaurante -->
                <p><?php echo htmlspecialchars($restauranteDTO->getCiudad()); ?></p>
                <p>Precio medio: <?php echo htmlspecialchars($restauranteDTO->getPrecioMedio()); ?></p>

                <!-- Boton de accion -->
                <div class="button-container">
                    <a href="detalle_restaurante.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>" class="button">Ver Detalles</a>

                    <?php if ($tipoUsuario == 1): ?>
                        <a href="reservar.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>" class="button">Reservar</a>
                    <?php endif; ?>

                    <?php if ($tipoUsuario == 2): ?>
                        <a href="gestionar_reservas.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>" class="button">Gestionar Reservas</a>
                        <!-- Botón para editar la información del restaurante -->
                        <a href="editar_restaurante.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>" class="button">Editar Información</a>
                    <?php endif; ?>
                </div>



            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<script>
    // Enviar el formulario automaticamente al cambiar los filtros
    document.getElementById('buscar').addEventListener('change', function () {
        document.getElementById('search-form').submit();
    });

    document.getElementById('tipo-comida').addEventListener('change', function () {
        document.getElementById('search-form').submit();
    });

    document.getElementById('ciudad').addEventListener('change', function () {
        document.getElementById('search-form').submit();
    });
</script>
</body>
</html>
