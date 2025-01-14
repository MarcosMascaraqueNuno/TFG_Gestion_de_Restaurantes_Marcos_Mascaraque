<?php
require_once("../../_Varios.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Views/login.php");
    exit;
}

$conexion = obtenerPdoConexionBD();
$usuarioId = $_SESSION['usuario_id'];

$restauranteId = $_POST['restaurante_id'];
$franjaHorariaId = $_POST['franja_horaria_id'];
$numeroComensales = $_POST['numero_comensales'] ?? 1;
$comentarios = $_POST['comentarios'] ?? '';

$sqlFranja = "SELECT hora_inicio FROM horarios_comidas WHERE id = :franja_id AND restaurante_id = :restaurante_id";
$sentenciaFranja = $conexion->prepare($sqlFranja);
$sentenciaFranja->execute([
    ':franja_id' => $franjaHorariaId,
    ':restaurante_id' => $restauranteId
]);
$franja = $sentenciaFranja->fetch(PDO::FETCH_ASSOC);

if (!$franja) {
    header("Location: ../Views/error.php?mensaje=Franja horaria no válida.");
    exit;
}

$fechaReserva = date('Y-m-d');
$horaReserva = $franja['hora_inicio'];
$estadoReserva = 'pendiente';

$sqlReserva = "
    INSERT INTO Reservas (id_usuario, id_restaurante, fecha_reserva, hora_reserva, numero_comensales, estado_reserva, comentarios)
    VALUES (:id_usuario, :id_restaurante, :fecha_reserva, :hora_reserva, :numero_comensales, :estado_reserva, :comentarios)
";

$sentenciaReserva = $conexion->prepare($sqlReserva);
$sentenciaReserva->execute([
    ':id_usuario' => $usuarioId,
    ':id_restaurante' => $restauranteId,
    ':fecha_reserva' => $fechaReserva,
    ':hora_reserva' => $horaReserva,
    ':numero_comensales' => $numeroComensales,
    ':estado_reserva' => $estadoReserva,
    ':comentarios' => $comentarios
]);

header("Location: ../Views/inicio.php");
exit;

exit;
?>
