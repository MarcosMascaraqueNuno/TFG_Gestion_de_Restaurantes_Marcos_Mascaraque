<?php
require_once("../Models/Reserva/ReservaDAO.php");
require_once("../../_Varios.php");

use Reserva\ReservaDAO;

if (!isset($_SESSION)) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar si llega el id por la url
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: error.php");
    exit;
}

$conexion = obtenerPdoConexionBD();
$usuarioId = $_SESSION['usuario_id'];
$restauranteId = $_GET['id'];


$fechaReserva = isset($_GET['fecha_reserva']) ? $_GET['fecha_reserva'] : date('Y-m-d');
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

$reservaDAO = new ReservaDAO($conexion);

// Obtener las reservas filtradas por un dia especifico
$reservas = $reservaDAO->obtenerReservasPorRestaurante($restauranteId, $fechaReserva, $estado);

// Obtener el nombre del restaurante
$nombreRestaurante = $reservaDAO->obtenerNombreRestaurante($restauranteId);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reservas</title>
    <link rel="stylesheet" href="../../styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="header">
    <h1>Gestionar Reservas</h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Volver a los restaurantes</a>
        <a href="perfil.php" class="button">Perfil</a>
        <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
    </div>
</div>

<div class="container">
    <div class="content">
        <h2><?php echo htmlspecialchars($nombreRestaurante); ?></h2>
        <div class="filtros">
            <form method="GET" action="">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($restauranteId); ?>">

                <label for="fecha_reserva">Fecha:</label>
                <input type="date" id="fecha_reserva" name="fecha_reserva"
                       value="<?php echo isset($_GET['fecha_reserva']) ? htmlspecialchars($_GET['fecha_reserva']) : ''; ?>">

                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="">Seleccionar Estado</option>
                    <option value="Pendiente" <?php echo isset($_GET['estado']) && $_GET['estado'] == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Confirmada" <?php echo isset($_GET['estado']) && $_GET['estado'] == 'Confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="Finalizada" <?php echo isset($_GET['estado']) && $_GET['estado'] == 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                    <option value="Cancelada" <?php echo isset($_GET['estado']) && $_GET['estado'] == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>


                <button type="button" id="filtrarPendientes" class="button">Total Pendientes</button>
                <button type="button" id="filtrarConfirmadasHoy" class="button">Confirmadas Hoy</button>
            </form>
        </div>



        <div id="mensaje"></div>

        <?php if (empty($reservas)): ?>
            <p>No hay reservas para este restaurante.</p>
        <?php else: ?>

            <table class="tabla">
                <thead>
                <tr>
                    <th>Fecha Reserva</th>
                    <th>Hora Reserva</th>
                    <th>Comensales</th>
                    <th>Estado</th>
                    <th>Nombre Cliente</th>
                    <th>Email Cliente</th>
                    <th>Teléfono Cliente</th>
                    <th>Comentarios</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody id="reservas-lista">
                <?php foreach ($reservas as $reserva): ?>
                    <tr data-reserva-id="<?php echo $reserva['id_reserva']; ?>">
                        <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['hora_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['numero_comensales']); ?></td>
                        <td>
                        <span class="estado <?php echo strtolower($reserva['estado_reserva']); ?>">
                            <?php echo htmlspecialchars($reserva['estado_reserva']); ?>
                        </span>
                        </td>
                        <td><?php echo htmlspecialchars($reserva['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['email_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['telefono_cliente']); ?></td>
                        <td>
                            <?php
                            echo htmlspecialchars(!empty($reserva['comentarios']) ? $reserva['comentarios'] : 'Sin comentarios');
                            ?>
                        </td>
                        <td>
                            <?php if ($reserva['estado_reserva'] == 'Pendiente'): ?>
                                <button class="button confirmar" data-reserva-id="<?php echo $reserva['id_reserva']; ?>">Confirmar Reserva</button>
                            <?php elseif ($reserva['estado_reserva'] == 'Confirmada'): ?>
                                <button class="button cancelar" data-reserva-id="<?php echo $reserva['id_reserva']; ?>">Cancelar Reserva</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Envia el formulario al cambiar los valores de fecha_reserva o estado
        $("#fecha_reserva, #estado").on("change", function() {
            const form = $(this).closest("form");
            form.submit();
        });

        // Boton para filtrar solo las reservas pendientes
        $("#filtrarPendientes").click(function() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('estado', 'Pendiente');
            urlParams.set('fecha_reserva', '');
            window.location.search = urlParams.toString();
        });

        // Boton para filtrar solo las reservas configmadas de ese dia
        $("#filtrarConfirmadasHoy").click(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const today = new Date().toISOString().split('T')[0];
            urlParams.set('estado', 'Confirmada');
            urlParams.set('fecha_reserva', today);
            window.location.search = urlParams.toString();
        });

// Confirmar reserva mediante AJAX al hacer clic en el boton
        $(".confirmar").click(function() {
            var reservaId = $(this).data("reserva-id");

            $.ajax({
                url: "../Controls/confirmar_reserva.php",
                type: "POST",
                data: { reserva_id: reservaId },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    $("#mensaje").text("Error al confirmar la reserva.");
                }
            });
        });

        // Cancelar reserva mediante AJAX al hacer clic en el boton
        $(".cancelar").click(function() {
            var reservaId = $(this).data("reserva-id");

            var confirmarCancelacion = confirm("¿Estás seguro de que deseas cancelar esta reserva?");

            if (confirmarCancelacion) {
                $.ajax({
                    url: "../Controls/cancelar_reserva.php",
                    type: "POST",
                    data: { reserva_id: reservaId },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        $("#mensaje").text("Error al cancelar la reserva.");
                    }
                });
            }
        });
    });
</script>
</body>
</html>