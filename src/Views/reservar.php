<?php
require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
require_once("../Models/Reserva/ReservaDAO.php");
require_once("../Models/Reserva/ReservaDTO.php");
require_once("../../_Varios.php");

use Restaurante\RestauranteDAO;

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$conexion = obtenerPdoConexionBD();
$usuarioId = $_SESSION['usuario_id'];
$restauranteDAO = new RestauranteDAO($conexion);

// Validar si se pasa el id por la url
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

$restauranteId = $_GET['id'];

// Obtener el detalle del restaurante
$restauranteDTO = $restauranteDAO->obtenerPorId($restauranteId);

if (!$restauranteDTO) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

// Consulta para obtener las franjas horarias disponibles del restaurante
$sql = "SELECT id, hora_inicio, tipo FROM horarios_comidas WHERE restaurante_id = :restaurante_id ORDER BY hora_inicio";
$sentencia = $conexion->prepare($sql);
$sentencia->execute([':restaurante_id' => $restauranteId]);
$franjasHorarias = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Comprobamos si la reserva fue exitosa
$exito = isset($_GET['exito']) && $_GET['exito'] == '1';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar en <?php echo htmlspecialchars($restauranteDTO->getNombre()); ?></title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>

<div class="header">
    <h1>Reservar en <?php echo htmlspecialchars($restauranteDTO->getNombre()); ?></h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Volver</a>
        <a href="perfil.php" class="button">Perfil</a>
        <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4" style="padding-top: 20px">Reservar Mesa</h2>
    <form id="reservaForm" action="../Controls/procesar_reserva.php" method="POST" class="needs-validation" style="margin-top: -25px" novalidate>
        <input type="hidden" name="restaurante_id" value="<?php echo htmlspecialchars($restauranteId); ?>">

        <!-- Fecha de la reserva con calendario -->
        <div class="mb-3">
            <label for="fecha_reserva" class="form-label">Día de la Reserva:</label>
            <input type="text" name="fecha_reserva" id="fecha_reserva" class="form-control" style="width: 15%;" placeholder="Selecciona un día" required>
            <div class="invalid-feedback">Por favor, selecciona una fecha válida.</div>
        </div>

        <!-- Franjas horarias -->
        <div class="mb-3">
            <h4>Selecciona tu Franja Horaria:</h4>

            <!-- Comidas -->
            <div class="mb-3">
                <h5>Comidas:</h5>
                <div class="d-flex flex-wrap">
                    <?php foreach ($franjasHorarias as $franja): ?>
                        <?php if ($franja['tipo'] == 1): ?>
                            <label class="btn btn-outline-primary me-2 mb-2">
                                <input type="radio" name="franja_horaria_id" value="<?php echo htmlspecialchars($franja['id']); ?>" required>
                                <?php
                                // Mostrar solo hora y minutos ajustando lo caracteres
                                $hora = substr($franja['hora_inicio'], 0, 5);
                                echo htmlspecialchars($hora);
                                ?>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cenas -->
            <div class="mb-3">
                <h5>Cenas:</h5>
                <div class="d-flex flex-wrap">
                    <?php foreach ($franjasHorarias as $franja): ?>
                        <?php if ($franja['tipo'] == 2): // Tipo 2 = Cenas ?>
                            <label class="btn btn-outline-success me-2 mb-2">
                                <input type="radio" name="franja_horaria_id" value="<?php echo htmlspecialchars($franja['id']); ?>" required>
                                <?php
                                // Mostrar solo hora y minutos ajustando lo caracteres
                                $hora = substr($franja['hora_inicio'], 0, 5);
                                echo htmlspecialchars($hora);
                                ?>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>


            <!-- Numero de comensales -->
            <div class="mb-3">
                <label for="numero_comensales" class="form-label">Número de Comensales:</label>
                <input type="number" name="numero_comensales" id="numero_comensales" class="form-control" style="width: 10%;" min="1" required>
                <div class="invalid-feedback">Por favor, ingresa un número de comensales entre 1 y 10.</div>
            </div>

            <!-- Comentarios -->
            <div class="mb-3">
                <label for="comentarios" class="form-label">Comentarios (opcional):</label>
                <textarea name="comentarios" id="comentarios" class="form-control" style="width: 50%;" rows="3"></textarea>
            </div>

            <!-- Boton de envio -->
            <button type="button" class="btn btn-primary" id="btnReservar">Reservar</button>
    </form>
</div>

<!-- Modal de confirmacion -->
<div class="modal" id="successModal">
    <div class="modal-content" >
        <h2>¡Reserva confirmada!</h2>
        <button id="btnAceptar" class="btn btn-success">Aceptar</button>
    </div>
</div>

<script>
    // Mostrar pop-up de confirmación
    document.getElementById('btnReservar').addEventListener('click', function(event) {
        event.preventDefault();

        document.getElementById('successModal').style.display = 'flex';

        setTimeout(function() {
            if (document.getElementById('successModal').style.display === 'flex') {
                document.getElementById('successModal').style.display = 'none';

                document.getElementById('reservaForm').submit();
            }
        }, 3000);
    });

    document.getElementById('btnAceptar').addEventListener('click', function() {
        document.getElementById('successModal').style.display = 'none';

        document.getElementById('reservaForm').submit();
    });


    // Inicializar el calendario
    flatpickr("#fecha_reserva", {
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "es",
        inline: true,
    });
</script>


</body>
</html>
