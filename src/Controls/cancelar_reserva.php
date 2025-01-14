<?php
require_once("../../_Varios.php");
require_once("../Models/Reserva/ReservaDAO.php");

use Reserva\ReservaDAO;

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $conexion = obtenerPdoConexionBD();
    $reservaDAO = new ReservaDAO($conexion);

    if (isset($_POST['id_reserva'])) {
        $reservaId = $_POST['id_reserva'];
        $reservaDAO->cancelarReserva($reservaId);

        $_SESSION['mensaje_exito'] = "Reserva cancelada correctamente";

        header("Location: ../Views/historial_reservas.php");
        exit;
    } else {
        $_SESSION['mensaje_error'] = "No se recibió un ID de reserva válido.";

        header("Location: ../Views/historial_reservas.php");
        exit;
    }

} catch (Exception $e) {
    $_SESSION['mensaje_error'] = "Ocurrió un error al procesar la solicitud.";
    header("Location: ../Views/historial_reservas.php");
    exit;
}
