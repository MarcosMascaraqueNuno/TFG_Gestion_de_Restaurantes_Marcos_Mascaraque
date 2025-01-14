<?php
require_once("../Models/Reserva/ReservaDAO.php");
require_once("../Models/Resena/ResenaDAO.php");
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

$reservaDAO = new Reserva\ReservaDAO($conexion);
$reservas = $reservaDAO->obtenerReservasPorUsuario($usuarioId);

$resenaDAO = new Resena\ResenaDAO($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservas</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
<div class="header">
    <h1>Historial de Reservas</h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Volver al Inicio</a>
        <a href="perfil.php" class="button">Perfil</a>
        <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
    </div>
</div>
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <p class="mensaje-exito"><?php echo htmlspecialchars($_SESSION['mensaje_exito']); ?></p>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php elseif (isset($_SESSION['mensaje_error'])): ?>
    <p class="mensaje-error"><?php echo htmlspecialchars($_SESSION['mensaje_error']); ?></p>
    <?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>

<div class="content">

    <?php if (empty($reservas)): ?>
        <p>No tienes reservas realizadas.</p>
    <?php else: ?>
        <table class="reservas-table">
            <thead>
            <tr>
                <th>Restaurante</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Número de Personas</th>
                <th>Estado</th>
                <th>Comentarios</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva['nombre_restaurante']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['hora_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['numero_comensales']); ?></td>
                    <td>
                        <span class="estado <?php echo strtolower($reserva['estado_reserva']); ?>">
                            <?php echo htmlspecialchars($reserva['estado_reserva']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($reserva['comentarios'] ?: 'Sin comentarios'); ?></td>
                    <td>
                        <!-- Verifica si la reserva no esta cancelada -->
                        <?php if ($reserva['estado_reserva'] !== 'Cancelada'): ?>
                            <!-- Si la reserva no está cancelada muestra el boton para cancelar la reserva -->
                            <form method="POST" action="../Controls/cancelar_reserva.php" style="display: inline;">
                                <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">
                                <button type="submit" class="button-cancelar" onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                    Cancelar Reserva
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Si la reserva esta cancelada muestra un mensaje indicando que ya fue cancelada -->
                            <span>Reserva Cancelada</span>
                        <?php endif; ?>

                        <!-- Verifica si la reserva está finalizada y no se ha dejado reseña -->
                        <?php if ($reserva['estado_reserva'] === 'Finalizada' && !$resenaDAO->existeResenaParaReserva($reserva['id_reserva'])): ?>
                            <!-- Si la reserva esta finalizada y no tiene reseña, permite dejar una reseña -->
                            <a href="dejar_resena.php?id_reserva=<?php echo $reserva['id_reserva']; ?>" class="button">Dejar Reseña</a>
                        <?php elseif ($resenaDAO->existeResenaParaReserva($reserva['id_reserva'])): ?>
                            <!-- Si ya se ha dejado una reseña, muestra un mensaje indicando que la reseña ya fue enviada -->
                            <span>Reseña enviada</span>
                        <?php else: ?>
                            <!-- Si no se puede dejar una reseña todavia  muestra un mensaje -->
                            <span>No puedes dejar una reseña aun</span>
                        <?php endif; ?>
                    </td>


                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
