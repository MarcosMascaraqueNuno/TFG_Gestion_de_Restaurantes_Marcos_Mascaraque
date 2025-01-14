<?php
require_once("../Models/Reserva/ReservaDAO.php");
require_once("../Models/Resena/ResenaDAO.php");
require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");

require_once("../../_Varios.php");

use Resena\ResenaDAO;
use Reserva\ReservaDAO;

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$conexion = obtenerPdoConexionBD();
$usuarioId = $_SESSION['usuario_id'];

if (!isset($_GET['id_reserva'])) {
    echo "No se ha proporcionado una reserva válida.";
    exit;
}

$idReserva = $_GET['id_reserva'];

$reservaDAO = new ReservaDAO($conexion);
$reserva = $reservaDAO->obtenerReservaPorId($idReserva);

$idRestaurante = $reserva['id_restaurante'];
$restauranteDAO = new Restaurante\RestauranteDAO($conexion);
$restaurante = $restauranteDAO->obtenerPorId($idRestaurante);
$restauranteDTO = $restauranteDAO->obtenerPorId($idRestaurante);

if ($restaurante === null) {
    echo "No se encontró el restaurante.";
    exit;
}

$exito = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    if ($calificacion < 1 || $calificacion > 5) {
        $error = "La calificación debe estar entre 1 y 5.";
    } else {
        $resenaDAO = new ResenaDAO($conexion);
        $resenaDAO->crearResena([
            'id_restaurante' => $restaurante->getId(),
            'id_usuario' => $usuarioId,
            'id_reserva' => $idReserva,
            'calificacion' => $calificacion,
            'comentario' => $comentario,
            'fecha_resena' => date("Y-m-d")
        ]);

        $exito = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar Reseña</title>
    <link rel="stylesheet" href="../../styles.css">

</head>
<body>
<div class="header">
    <h1><?php echo htmlspecialchars($restauranteDTO->getNombre()); ?></h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Volver</a>
        <a href="perfil.php" class="button">Perfil</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- Mostrar cerrar sesion si está logueado -->
            <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
        <?php else: ?>
            <!-- Mostrar inicisar sesion si no está logueado -->
            <a href="login.php" class="button">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</div>
<div class="form-container">
    <h1>Valoración</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="dejar_resena.php?id_reserva=<?php echo $idReserva; ?>" method="post">
        <label for="calificacion">Calificación (1-5):</label>
        <select id="calificacion" name="calificacion" required>
            <option value="">Seleccione una calificación</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?> Estrellas</option>
            <?php endfor; ?>
        </select>

        <label for="comentario">Comentario:</label>
        <textarea id="comentario" name="comentario" rows="5" placeholder="Escribe tu experiencia..." required></textarea>

        <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($_GET['id_reserva']); ?>">

        <button type="submit">Guardar Reseña</button>
    </form>
</div>

<?php if ($exito): ?>
    <div class="modal" id="successModal" style="display: flex;">
        <div class="modal-content">
            <h2>¡Reseña realizada con éxito!</h2>
            <button onclick="window.location.href='inicio.php';">Aceptar</button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = 'inicio.php';
        }, 3000);
    </script>
<?php endif; ?>
</body>
</html>
