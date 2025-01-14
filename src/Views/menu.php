<?php
require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
require_once("../../_Varios.php");

use Restaurante\RestauranteDAO;


$conexion = obtenerPdoConexionBD();
$restauranteDAO = new RestauranteDAO($conexion);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

$restauranteId = $_GET['id'];
$restauranteDTO = $restauranteDAO->obtenerPorId($restauranteId);

if (!$restauranteDTO) {
    header("Location: error.php?mensaje=Restaurante no encontrado.");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
    <link rel="stylesheet" href="../../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf_viewer.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <style>
        /* Ajustar el tamaño del canvas del PDF */
        canvas {
            margin: 10px 0;
            max-width: 80%;
            height: auto;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Menú de <?php echo htmlspecialchars($restauranteDTO->getNombre()); ?></h1>
    <div class="buttons">
        <a href="detalle_restaurante.php?id=<?php echo htmlspecialchars($restauranteId); ?>" class="button">Volver</a>
        <a href="perfil.php" class="button">Perfil</a>
        <a href="../Controls/cerrar_sesion.php" class="button">Cerrar Sesión</a>
    </div>
</div>

<?php
$menuRuta = $restauranteDTO->getMenuUrl();
if (!empty($menuRuta)) {
    ?>

        <div id="pdf-js-viewer"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const url = "<?php echo htmlspecialchars($menuRuta); ?>";

            const loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                const viewer = document.getElementById('pdf-js-viewer');
                const scale = 1.5;
                const numPages = pdf.numPages;

                for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                    pdf.getPage(pageNum).then(function(page) {
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        const viewport = page.getViewport({ scale: scale });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        page.render({
                            canvasContext: context,
                            viewport: viewport
                        });

                        viewer.appendChild(canvas);
                    });
                }
            }).catch(function(error) {
                console.error('Error al cargar el PDF: ', error);
            });
        });
    </script>
<?php
} else {
?>
    <p class="text-center">No hay menú disponible actualmente.</p>
    <?php
}
?>

</body>
</html>
