<?php
require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
require_once("../Models/Resena/ResenaDTO.php");
require_once("../../_Varios.php");

use Restaurante\RestauranteDAO;

if (!isset($_SESSION)) {
    session_start();
}

// Conexión a la base de datos
$conexion = obtenerPdoConexionBD();
$restauranteDAO = new RestauranteDAO($conexion);

// Validar el parámetro id del restaurante
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

$restauranteId = $_GET['id'];
$restauranteDTO = $restauranteDAO->obtenerPorId($restauranteId);

// Validar si el restaurante existe
if (!$restauranteDTO) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

// Obtener reseñas y calificacion media
$resenas = $restauranteDAO->obtenerResenasPorRestaurante($restauranteId);
$mediaCalificacion = $restauranteDAO->obtenerMediaCalificacion($restauranteId);

$usuarioId = $_SESSION['usuario_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Restaurante</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf_viewer.min.css">


</head>
<body>
<div class="header">
    <h1><?php echo htmlspecialchars($restauranteDTO->getNombre()); ?></h1>
    <div class="buttons">
        <a href="inicio.php" class="button">Volver</a>
        <a href="perfil.php" class="button">Perfil</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- Mostrar cerrar sesión si esta logueado -->
            <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
        <?php else: ?>
            <!-- Mostrar inicisar sesión si no esta logueado -->
            <a href="login.php" class="button">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</div>

<div class="detalle-restaruante">
    <div id="carruselRestaurante" class="carousel slide" data-bs-ride="carousel">
        <!-- Carrusel -->
        <div class="carousel-indicators">
            <?php
            $fotosRestaurante = $restauranteDAO->obtenerFotosRestaurante($restauranteId);
            if ($fotosRestaurante) {
                foreach ($fotosRestaurante as $index => $foto) {
                    $activeClass = $index === 0 ? 'active' : '';
                    echo '<button type="button" data-bs-target="#carruselRestaurante" data-bs-slide-to="' . $index . '" class="' . $activeClass . '" aria-current="true" aria-label="Slide ' . ($index + 1) . '"></button>';
                }
            } else {
                echo '<button type="button" data-bs-target="#carruselRestaurante" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>';
            }
            ?>
        </div>

        <!-- Imagenes del carrusel -->
        <div class="carousel-inner">
            <?php
            if ($fotosRestaurante) {
                foreach ($fotosRestaurante as $index => $foto) {
                    $urlFoto = htmlspecialchars($foto['url_foto']);
                    $activeClass = $index === 0 ? 'active' : '';
                    echo '<div class="carousel-item ' . $activeClass . '">
                        <img src="' . $urlFoto . '" class="d-block w-100" alt="Foto del restaurante">
                      </div>';
                }
            }
            ?>
        </div>

        <!-- Controles del carrusel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carruselRestaurante" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselRestaurante" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <div class="contenedor-informacion">
        <div class="contenedor-informacion-izquierda">
            <h2>Información General</h2>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($restauranteDTO->getDireccion()); ?>, <?php echo htmlspecialchars($restauranteDTO->getNumero()); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($restauranteDTO->getTelefono()); ?></p>
            <a href="reservar.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>">Reservar</a>
        </div>
        <div class="contenedor-informacion-derecha">
            <h2 style="align-items: center">Descripción</h2>
            <p> <?php echo htmlspecialchars($restauranteDTO->getDescripcion()); ?></p>
            <a href="reservar.php?id=<?php echo htmlspecialchars($restauranteDTO->getId()); ?>">Reservar</a>
        </div>
    </div>

    <div class="contenedor-informacion">
        <div class="contenedor-informacion-izquierda">
            <h2>Reseñas</h2>
                <div class="calificacion">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $mediaCalificacion) {
                            echo "⭐";
                        } else {
                            echo "☆";
                        }
                    }
                    ?>
                    <span>(<?php echo $mediaCalificacion; ?> de 5)</span>
            </div>

            <?php if (count($resenas) > 0): ?>
                <?php foreach ($resenas as $resena): ?>
                    <div class="reseña">
                        <div class="calificacion">
                            Calificación:
                            <?php
                            // Obtener la calificacion y convertirla a estrellas
                            $calificacion = $resena->getCalificacion();
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $calificacion) {
                                    echo "⭐";
                                } else {
                                    echo "☆";
                                }
                            }
                            ?>
                        </div>
                        <div class="comentario">
                            <?php echo nl2br(htmlspecialchars($resena->getComentario())); ?>
                        </div>
                        <div class="fecha">
                            Fecha: <?php echo $resena->getFechaResena(); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay reseñas para este restaurante.</p>
            <?php endif; ?>
        </div>


        <div class="contenedor-informacion-derecha" >
            <h2>Menú</h2>
            <?php $restauranteId = $restauranteDTO->getId();?>
            <a href="menu.php?id=<?php echo $restauranteId; ?>" class="button">Ver Menú</a>
        </div>


    </div>


    <div class="map">
        <h2>Ubicación</h2>
        <?php
        $direccion = urlencode($restauranteDTO->getDireccion());
        $mapUrl = "https://www.google.com/maps/embed/v1/place?key=AIzaSyAgrviSi468oIFSgDp1-frZpbftXhKU25Q&q=$direccion";
        ?>
        <iframe width="600" height="450" frameborder="0" style="border:0" src="<?php echo $mapUrl; ?>"
                allowfullscreen></iframe>
    </div>
</div>


<script>
    // Inicializa el carrusel Swiper con botones de navegación.
    var swiper = new Swiper('.swiper-container', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Expandir reseña al hacer clic
    document.querySelectorAll('.comentario').forEach(function (comentario) {
        comentario.addEventListener('click', function () {
            comentario.classList.toggle('expanded');
        });
    });
</script>
</body>
</html>