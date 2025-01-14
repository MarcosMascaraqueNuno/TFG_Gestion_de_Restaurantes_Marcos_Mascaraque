<?php
require_once("../Models/Restaurante/RestauranteDAO.php");
require_once("../Models/Restaurante/RestauranteDTO.php");
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
$tipoUsuario = $restauranteDAO->obtenerTipoUsuario($usuarioId);

// Filtrar restaurantes segun el tipo de usuario
if ($tipoUsuario == 2) {
    $restaurantes = $restauranteDAO->obtenerRestaurantesPorPropietario($usuarioId);
} else {
    $restaurantes = $restauranteDAO->obtenerRestaurantes();
}

// Obtener los tipos de comida disponibles
$query = "SELECT * FROM tipos_comida";
$stmt = $conexion->prepare($query);
$stmt->execute();
$tiposComida = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreRestaurante = $_POST['nombre'];
    $direccionRestaurante = $_POST['direccion'];
    $numero_direccionRestaurante = $_POST['numero_direccion'];
    $ciudadRestaurante = $_POST['ciudad'];
    $telefonoRestaurante = $_POST['telefono'];
    $emailRestaurante = $_POST['email'];
    $capacidadTotal = $_POST['capacidad_total'];
    $urlMenuPdf = $_POST['url_menu_pdf'];
    $horarioApertura = $_POST['horario_apertura'];
    $horarioCierre = $_POST['horario_cierre'];
    $descripcion = $_POST['descripcion'];
    $precioMedio = $_POST['precio_medio'];
    $fotoSubida = null;
    $menuSubido = null;
    $fotosRestaurante = [];

    // Subida de la foto de portada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpetaFotos = "../../resource/img/";

        if (!is_dir($carpetaFotos)) {
            mkdir($carpetaFotos, 0777, true);
        }

        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = $carpetaFotos . $nombreArchivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $fotoSubida = "../../resource/img/" . $nombreArchivo;
        } else {
            $mensaje = "Error al subir la foto de portada.";
        }
    }

    // Subida del menu PDF
    if (isset($_FILES['menu_pdf']) && $_FILES['menu_pdf']['error'] === UPLOAD_ERR_OK) {
        $carpetaMenus = "../../resource/menus/";

        if (!is_dir($carpetaMenus)) {
            mkdir($carpetaMenus, 0777, true);
        }

        $extensionMenu = pathinfo($_FILES['menu_pdf']['name'], PATHINFO_EXTENSION);

        if (strtolower($extensionMenu) === 'pdf') {
            $nombreMenu = uniqid() . '.' . $extensionMenu;
            $rutaDestinoMenu = $carpetaMenus . $nombreMenu;

            if (move_uploaded_file($_FILES['menu_pdf']['tmp_name'], $rutaDestinoMenu)) {
                $menuSubido = "../../resource/menus/" . $nombreMenu;
            } else {
                $mensaje = "Error al subir el menú.";
            }
        } else {
            $mensaje = "Solo se permiten archivos PDF para el menú.";
        }
    }

    // Subida de fotos del restaurante
    if (isset($_FILES['fotos_restaurante']) && count($_FILES['fotos_restaurante']['name']) > 0) {
        $carpetaRestauranteFotos = "../../resource/restaurante_fotos/";

        if (!is_dir($carpetaRestauranteFotos)) {
            mkdir($carpetaRestauranteFotos, 0777, true);
        }

        for ($i = 0; $i < count($_FILES['fotos_restaurante']['name']); $i++) {
            if ($_FILES['fotos_restaurante']['error'][$i] === UPLOAD_ERR_OK) {
                $extensionFoto = pathinfo($_FILES['fotos_restaurante']['name'][$i], PATHINFO_EXTENSION);
                $nombreArchivoFoto = uniqid() . '.' . $extensionFoto;
                $rutaDestinoFoto = $carpetaRestauranteFotos . $nombreArchivoFoto;

                if (move_uploaded_file($_FILES['fotos_restaurante']['tmp_name'][$i], $rutaDestinoFoto)) {
                    $fotosRestaurante[] = "../../resource/restaurante_fotos/" . $nombreArchivoFoto;
                } else {
                    $mensaje = "Error al subir una de las fotos del restaurante.";
                }
            }
        }
    }

    // Consulta para insertar los datos del restaurante
    $query = "INSERT INTO restaurantes (nombre, direccion, numero_direccion, ciudad, telefono, email, capacidad_total, url_menu_pdf, horario_apertura, horario_cierre, descripcion, Url_Foto, propietario_id, precio_medio) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$nombreRestaurante, $direccionRestaurante, $numero_direccionRestaurante, $ciudadRestaurante, $telefonoRestaurante, $emailRestaurante, $capacidadTotal, $menuSubido, $horarioApertura, $horarioCierre, $descripcion, $fotoSubida, $usuarioId, $precioMedio]);

    $restauranteId = $conexion->lastInsertId();

    // Insertar las fotos adicionales en la base de dato
    foreach ($fotosRestaurante as $foto) {
        $query = "INSERT INTO fotos_restaurante (restaurante_id, url_foto) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$restauranteId, $foto]);
    }

    // Introducir los valores en las tablas de horarios
    if (isset($_POST['horarios_comida'])) {
        foreach ($_POST['horarios_comida'] as $horaComida) {
            $query = "INSERT INTO horarios_comidas (restaurante_id, hora_inicio, tipo) VALUES (?, ?, 1)"; // 1 para comida
            $stmt = $conexion->prepare($query);
            $stmt->execute([$restauranteId, $horaComida]);
        }
    }

    if (isset($_POST['horarios_cena'])) {
        foreach ($_POST['horarios_cena'] as $horaCena) {
            $query = "INSERT INTO horarios_comidas (restaurante_id, hora_inicio, tipo) VALUES (?, ?, 2)"; // 2 para cena
            $stmt = $conexion->prepare($query);
            $stmt->execute([$restauranteId, $horaCena]);
        }
    }

    // Introducir los valores en la tabla de tipos de comida
    if (isset($_POST['tipos_comida'])) {
        foreach ($_POST['tipos_comida'] as $tipoComidaId) {
            $query = "INSERT INTO restaurantes_tipos_comida (restaurante_id, tipo_comida_id) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$restauranteId, $tipoComidaId]);
        }
    }

    header("Location: ../Views/inicio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Restaurante</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgrviSi468oIFSgDp1-frZpbftXhKU25Q&libraries=places" async defer></script>
    <script>
        function initAutocomplete() {
            var input = document.getElementById('direccion');
            var autocomplete = new google.maps.places.Autocomplete(input);

            document.getElementById('numero_direccion').disabled = true;
            document.getElementById('ciudad').disabled = true;

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    console.log("No se encontró una dirección válida");
                    return;
                }

                var direccion = place.formatted_address.split(",")[0];
                var numero_direccion = place.address_components.find(component => component.types.includes("street_number"))?.long_name || '';
                var ciudad = place.address_components.find(component => component.types.includes("locality"))?.long_name || '';

                document.getElementById('direccion').value = direccion;
                document.getElementById('numero_direccion').value = numero_direccion;
                document.getElementById('ciudad').value = ciudad;

                document.getElementById('numero_direccion').disabled = false;
                document.getElementById('ciudad').disabled = false;
            });
        }

        function agregarHorario(idDiv) {
            const contenedor = document.getElementById(idDiv);

            const nuevoInput = document.createElement('input');
            nuevoInput.type = 'time';
            nuevoInput.name = idDiv + '[]';
            nuevoInput.classList.add('form-control');
            nuevoInput.required = true;

            const saltoLinea = document.createElement('br');

            contenedor.appendChild(nuevoInput);
            contenedor.appendChild(saltoLinea);
        }

        $(document).ready(function() {
            initAutocomplete();
            $('#tipos_comida').select2();
        });
    </script>
</head>
<body>
<div class="header">
    <h1>Añadir Restaurante</h1>
</div>
<div class="buttons">
    <a href="inicio.php" class="button">Volver a la Lista de Restaurantes</a>
</div>
<div class="formulario" style="min-width: 50%">
    <form action="agregar_restaurante.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <label for="nombre">Nombre de restaurante:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required><br>

                <label for="capacidad_total">Capacidad Total:</label>
                <input type="number" id="capacidad_total" name="capacidad_total" class="form-control" required><br>

                <label for="menu_pdf">Subir Menú (PDF):</label>
                <input type="file" id="menu_pdf" name="menu_pdf" accept="application/pdf" class="form-control"><br>

                <label for="tipos_comida">Tipos de Comida:</label>
                <select name="tipos_comida[]" id="tipos_comida" class="form-control" multiple required>
                    <?php foreach ($tiposComida as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>"><?= $tipo['nombre'] ?></option>
                    <?php endforeach; ?>
                </select><br>

                <label for="precio_medio">Precio Medio por Persona (€):</label>
                <input type="number" id="precio_medio" name="precio_medio" class="form-control" required><br>

                <label for="foto">Foto portada:</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-control"><br>

                <label for="fotos">Fotos del Restaurante:</label>
                <input type="file" id="fotos" name="fotos_restaurante[]" accept="image/*" class="form-control" multiple><br>

            </div>

            <div class="col-md-6">
                <label for="direccion">Dirección Completa:</label>
                <input type="text" id="direccion" name="direccion" class="form-control" required placeholder="Escribe la dirección"><br>

                <label for="numero">Número:</label>
                <input type="text" id="numero_direccion" name="numero_direccion" class="form-control" required><br>

                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" class="form-control" required><br>

                <label for="horario_apertura">Horario de Apertura:</label>
                <input type="time" id="horario_apertura" name="horario_apertura" class="form-control" required><br>

                <label for="horario_cierre">Horario de Cierre:</label>
                <input type="time" id="horario_cierre" name="horario_cierre" class="form-control" required><br>

                <label for="horarios_comida">Horarios de Comida:</label>
                <div id="horarios_comida">
                    <input type="time" name="horarios_comida[]" class="form-control" required><br>
                </div>
                <button type="button" onclick="agregarHorario('horarios_comida')" class="btn btn-primary">Agregar Horario de Comida</button><br><br>

                <label for="horarios_cena">Horarios de Cena:</label>
                <div id="horarios_cena">
                    <input type="time" name="horarios_cena[]" class="form-control " required><br>
                </div>
                <button type="button" onclick="agregarHorario('horarios_cena')" class="btn btn-primary">Agregar Horario de Cena</button><br><br>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50"></textarea><br><br>
            </div>
        </div>

        <input type="submit" value="Guardar" class="btn btn-success">
    </form>
</div>



</body>
</html>