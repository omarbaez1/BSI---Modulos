<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");




// Procesar inserción de nuevo cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['idCambioEditar'])) {
    $idCambioNuevo = $_POST['idCambioNuevo'];
    $cedulaTecNuevo = $_POST['cedulaTecNuevo'];
    $nombreCompletoNuevo = $_POST['nombreCompletoNuevo'];
    $codHerramientaNuevo = $_POST['codHerramientaNuevo'];
    $tipoCambioNuevo = $_POST['tipoCambioNuevo'];
    $fechaCambioNuevo = $_POST['fechaCambioNuevo'];
    $observacionNuevo = $_POST['observacionNuevo'];

    $pdo = conexion();



    // Usar un bloque try-catch para manejar errores de duplicado
    try {
        $sql = "INSERT INTO cambios_herramientas (ID_CAMBIO, CEDULA_TEC, NOMBRE_COMPLETO, COD_HERRAMIENTA, TIPO_CAMBIO, FECHA_CAMBIO, OBSERVACION) VALUES (:idCambio, :cedulaTec, :nombreCompleto, :codHerramienta, :tipoCambio, :fechaCambio, :observacion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idCambio' => $idCambioNuevo,
            ':cedulaTec' => $cedulaTecNuevo,
            ':nombreCompleto' => $nombreCompletoNuevo,
            ':codHerramienta' => $codHerramientaNuevo,
            ':tipoCambio' => $tipoCambioNuevo,
            ':fechaCambio' => $fechaCambioNuevo,
            ':observacion' => $observacionNuevo
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Código de error para violación de clave única
            $mensajeError = "Error: Ya existe un cambio con el mismo ID.";
        } else {
            $mensajeError = "Error: " . $e->getMessage(); // Para otros errores de base de datos
        }
    }



    // Verificar si existe un registro con el mismo ID_CAMBIO
    $checkSql = "SELECT COUNT(*) FROM cambios_herramientas WHERE ID_CAMBIO = :idCambio";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':idCambio' => $idCambioNuevo]);
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        $mensajeError = "Error: Ya existe un cambio con el mismo ID.";
    } else {
        // Si no hay duplicado, procede a la inserción
        $sql = "INSERT INTO cambios_herramientas (ID_CAMBIO, CEDULA_TEC, NOMBRE_COMPLETO, COD_HERRAMIENTA, TIPO_CAMBIO, FECHA_CAMBIO, OBSERVACION) VALUES (:idCambio, :cedulaTec, :nombreCompleto, :codHerramienta, :tipoCambio, :fechaCambio, :observacion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idCambio' => $idCambioNuevo,
            ':cedulaTec' => $cedulaTecNuevo,
            ':nombreCompleto' => $nombreCompletoNuevo,
            ':codHerramienta' => $codHerramientaNuevo,
            ':tipoCambio' => $tipoCambioNuevo,
            ':fechaCambio' => $fechaCambioNuevo,
            ':observacion' => $observacionNuevo
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Procesar inserción de nuevo cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['idCambioEditar'])) {
    $idCambioNuevo = $_POST['idCambioNuevo'];
    $cedulaTecNuevo = $_POST['cedulaTecNuevo'];
    $nombreCompletoNuevo = $_POST['nombreCompletoNuevo'];
    $codHerramientaNuevo = $_POST['codHerramientaNuevo'];
    $tipoCambioNuevo = $_POST['tipoCambioNuevo'];
    $fechaCambioNuevo = $_POST['fechaCambioNuevo'];
    $observacionNuevo = $_POST['observacionNuevo'];

    $pdo = conexion();
    $sql = "INSERT INTO cambios_herramientas (ID_CAMBIO, CEDULA_TEC, NOMBRE_COMPLETO, COD_HERRAMIENTA, TIPO_CAMBIO, FECHA_CAMBIO, OBSERVACION) VALUES (:idCambio, :cedulaTec, :nombreCompleto, :codHerramienta, :tipoCambio, :fechaCambio, :observacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idCambio' => $idCambioNuevo,
        ':cedulaTec' => $cedulaTecNuevo,
        ':nombreCompleto' => $nombreCompletoNuevo,
        ':codHerramienta' => $codHerramientaNuevo,
        ':tipoCambio' => $tipoCambioNuevo,
        ':fechaCambio' => $fechaCambioNuevo,
        ':observacion' => $observacionNuevo
    ]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Procesar edición de cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCambioEditar'])) {
    $idCambioEditar = $_POST['idCambioEditar'];
    $cedulaTecEditar = $_POST['cedulaTecEditar'];
    $nombreCompletoEditar = $_POST['nombreCompletoEditar'];
    $codHerramientaEditar = $_POST['codHerramientaEditar'];
    $tipoCambioEditar = $_POST['tipoCambioEditar'];
    $fechaCambioEditar = $_POST['fechaCambioEditar'];
    $observacionEditar = $_POST['observacionEditar'];

    $pdo = conexion();
    $sql = "UPDATE cambios_herramientas SET CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, COD_HERRAMIENTA = :codHerramienta, TIPO_CAMBIO = :tipoCambio, FECHA_CAMBIO = :fechaCambio, OBSERVACION = :observacion WHERE ID_CAMBIO = :idCambio";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cedulaTec' => $cedulaTecEditar,
        ':nombreCompleto' => $nombreCompletoEditar,
        ':codHerramienta' => $codHerramientaEditar,
        ':tipoCambio' => $tipoCambioEditar,
        ':fechaCambio' => $fechaCambioEditar,
        ':observacion' => $observacionEditar,
        ':idCambio' => $idCambioEditar
    ]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Procesar eliminación de cambio
if (isset($_GET['eliminar'])) {
    $idCambioEliminar = $_GET['eliminar'];
    $pdo = conexion();
    $sql = "DELETE FROM cambios_herramientas WHERE ID_CAMBIO = :idCambio";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idCambio' => $idCambioEliminar]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Cabecera para controlar caché
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cambios de Herramientas</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        /* Estilos para la tabla y el contenedor */
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        /* Estilos para el slide de agregar nuevo */
        .slide-container {
            display: none;
            margin-top: 20px;
            
        }
        
        

        .slide-btngc {
            background-color: #007ba7; /* Color del botón */
            border: none;
            color: white;
            padding: 10px 20px; /* Tamaño del botón */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px; /* Tamaño de la fuente */
            margin: 6px 2px;
            cursor: pointer;
            border-radius: 5px; /* Esquinas redondeadas */
            transition: background-color 0.3s; /* Transición suave */
            position: absolute; /* Posicionamiento absoluto */
            top: 1260px; /* Espaciado desde la parte superior */
            left: 695px; /* Espaciado desde la izquierda */
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
        
    </style>
    <script>
        function toggleSlide() {
            var slide = document.getElementById("slide-container");
            if (slide.style.display === "none") {
                slide.style.display = "block";
            } else {
                slide.style.display = "none";
            }
        }

        function toggleEdit(idCambio) {
            var editForm = document.getElementById("edit-container");
            var forms = document.getElementsByClassName("edit-form");
            for (var i = 0; i < forms.length; i++) {
                if (forms[i].dataset.idCambio === idCambio) {
                    forms[i].style.display = "block";
                } else {
                    forms[i].style.display = "none";
                }
            }
        }
    </script>
</head>

<body>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.33; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">Cambios de Herramientas</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/herramientas.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>

<?php if (!empty($mensajeError)): ?>
    <p class="error-message"><?php echo $mensajeError; ?></p>
<?php endif; ?>

    <br>
    <button class="toggle-button"  onclick="window.location.href='../../inicio_sesion/administrador.php';">Página Principal</button>

    <h2>Buscar Cambios de Herramientas</h2>

    

    <!-- Formulario de búsqueda -->
    <form method="GET" action="">
        <label for="idCambio">ID Cambio:</label>
        <input type="text" name="idCambio" id="idCambio">

        <label for="codHerramienta">Código de Herramienta:</label>
        <input type="text" name="codHerramienta" id="codHerramienta">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec">

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto">

        <input type="submit" value="Buscar">
    </form>
        <!-- Botón para añadir nuevo cambio -->
    <button class="toggle-button" onclick="toggleSlide()">Añadir Nuevo Cambio</button>

<!-- Contenedor deslizante para añadir nuevo cambio -->
<div id="slide-container" class="slide-container">
    <h2>Añadir Nuevo Cambio</h2>
    <form method="POST" action="">
        <label for="idCambioNuevo">ID Cambio:</label>
        <input type="text" name="idCambioNuevo" id="idCambioNuevo" required>

        <label for="cedulaTecNuevo">Cédula Técnico:</label>
        <input type="text" name="cedulaTecNuevo" id="cedulaTecNuevo" required>

        <label for="nombreCompletoNuevo">Nombre Completo:</label>
        <input type="text" name="nombreCompletoNuevo" id="nombreCompletoNuevo" required>

        <label for="codHerramientaNuevo">Código Herramienta:</label>
        <input type="text" name="codHerramientaNuevo" id="codHerramientaNuevo" required>

        <label for="tipoCambioNuevo">Tipo Cambio:</label>
        <input type="text" name="tipoCambioNuevo" id="tipoCambioNuevo" required>

        <label for="fechaCambioNuevo">Fecha Cambio:</label>
        <input type="date" name="fechaCambioNuevo" id="fechaCambioNuevo" required>

        <label for="observacionNuevo">Observación:</label>
        <textarea name="observacionNuevo" id="observacionNuevo"></textarea>

        <input type="submit" value="Guardar Cambio">
    </form>
</div>
    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
        <?php

$mensajeError = ''; // Variable para almacenar mensajes de error

        // Conexión a la base de datos
        function conexion()
        {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error de conexión: ' . $e->getMessage();
                die();
            }
        }

        // Función para buscar cambios de herramientas
        function buscar_cambios($idCambio = null, $codHerramienta = null, $cedulaTec = null, $nombreCompleto = null)
        {
            $pdo = conexion();
            $sql = "SELECT * FROM cambios_herramientas WHERE 1=1";

            if (!empty($idCambio)) {
                $sql .= " AND LOWER(ID_CAMBIO) LIKE LOWER(:idCambio)";
            }
            if (!empty($codHerramienta)) {
                $sql .= " AND LOWER(COD_HERRAMIENTA) LIKE LOWER(:codHerramienta)";
            }
            if (!empty($cedulaTec)) {
                $sql .= " AND LOWER(CEDULA_TEC) LIKE LOWER(:cedulaTec)";
            }
            if (!empty($nombreCompleto)) {
                $sql .= " AND LOWER(NOMBRE_COMPLETO) LIKE LOWER(:nombreCompleto)";
            }

            $stmt = $pdo->prepare($sql);

            if (!empty($idCambio)) {
                $stmt->bindValue(':idCambio', '%' . $idCambio . '%');
            }
            if (!empty($codHerramienta)) {
                $stmt->bindValue(':codHerramienta', '%' . $codHerramienta . '%');
            }
            if (!empty($cedulaTec)) {
                $stmt->bindValue(':cedulaTec', '%' . $cedulaTec . '%');
            }
            if (!empty($nombreCompleto)) {
                $stmt->bindValue(':nombreCompleto', '%' . $nombreCompleto . '%');
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }




        
        // Obtener valores del formulario
        $idCambio = isset($_GET['idCambio']) ? $_GET['idCambio'] : null;
        $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
        $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
        $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;

        // Mostrar los resultados
        $cambios = buscar_cambios($idCambio, $codHerramienta, $cedulaTec, $nombreCompleto);

        if (!empty($cambios)) {
            echo '<div class="table-container">';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID Cambio</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>Código Herramienta</th>';
            echo '<th>ID Usuario</th>';
            echo '<th>Tipo Cambio</th>';
            echo '<th>Fecha Cambio</th>';
            echo '<th>Observación</th>';
            echo '<th>Acciones</th>'; // Nueva columna para editar y eliminar
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($cambios as $cambio) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($cambio['ID_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['COD_HERRAMIENTA']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['ID_USUARIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['TIPO_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['FECHA_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['OBSERVACION']) . '</td>';
                // Botones de Editar y Eliminar
                echo '<td>';
                echo '<a onclick="toggleEdit(\'' . $cambio['ID_CAMBIO'] . '\')">Editar</a> | ';
                echo '<a href="?eliminar=' . $cambio['ID_CAMBIO'] . '" onclick="return confirm(\'¿Estás seguro de eliminar este cambio?\')">Eliminar</a>';
                echo '</td>';
                echo '</tr>';
                // Formulario de edición
                echo '<tr class="edit-form" data-id-cambio="' . $cambio['ID_CAMBIO'] . '" style="display: none;">';
                echo '<td colspan="9">';
                echo '<form method="POST" action="">';
                echo '<input type="hidden" name="idCambioEditar" value="' . htmlspecialchars($cambio['ID_CAMBIO']) . '">';
                echo '<label for="cedulaTecEditar">Cédula Técnico:</label>';
                echo '<input type="text" name="cedulaTecEditar" id="cedulaTecEditar" value="' . htmlspecialchars($cambio['CEDULA_TEC']) . '" required>';

                echo '<label for="nombreCompletoEditar">Nombre Completo:</label>';
                echo '<input type="text" name="nombreCompletoEditar" id="nombreCompletoEditar" value="' . htmlspecialchars($cambio['NOMBRE_COMPLETO']) . '" required>';

                echo '<label for="codHerramientaEditar">Código Herramienta:</label>';
                echo '<input type="text" name="codHerramientaEditar" id="codHerramientaEditar" value="' . htmlspecialchars($cambio['COD_HERRAMIENTA']) . '" required>';

                echo '<label for="tipoCambioEditar">Tipo Cambio:</label>';
                echo '<input type="text" name="tipoCambioEditar" id="tipoCambioEditar" value="' . htmlspecialchars($cambio['TIPO_CAMBIO']) . '" required>';

                echo '<label for="fechaCambioEditar">Fecha Cambio:</label>';
                echo '<input type="date" name="fechaCambioEditar" id="fechaCambioEditar" value="' . htmlspecialchars($cambio['FECHA_CAMBIO']) . '" required>';

                echo '<label for="observacionEditar">Observación:</label>';
                echo '<textarea name="observacionEditar" id="observacionEditar">' . htmlspecialchars($cambio['OBSERVACION']) . '</textarea>';

                echo '<input type="submit" name="guardarCambio" value="Guardar Cambios">';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>No se encontraron cambios.</p>';
        }

        // Añadir nuevo cambio
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['idCambioEditar'])) {
            $idCambioNuevo = $_POST['idCambioNuevo'];
            $cedulaTecNuevo = $_POST['cedulaTecNuevo'];
            $nombreCompletoNuevo = $_POST['nombreCompletoNuevo'];
            $codHerramientaNuevo = $_POST['codHerramientaNuevo'];
            $tipoCambioNuevo = $_POST['tipoCambioNuevo'];
            $fechaCambioNuevo = $_POST['fechaCambioNuevo'];
            $observacionNuevo = $_POST['observacionNuevo'];

            $pdo = conexion();
            $sql = "INSERT INTO cambios_herramientas (ID_CAMBIO, CEDULA_TEC, NOMBRE_COMPLETO, COD_HERRAMIENTA, TIPO_CAMBIO, FECHA_CAMBIO, OBSERVACION) VALUES (:idCambio, :cedulaTec, :nombreCompleto, :codHerramienta, :tipoCambio, :fechaCambio, :observacion)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':idCambio' => $idCambioNuevo,
                ':cedulaTec' => $cedulaTecNuevo,
                ':nombreCompleto' => $nombreCompletoNuevo,
                ':codHerramienta' => $codHerramientaNuevo,
                ':tipoCambio' => $tipoCambioNuevo,
                ':fechaCambio' => $fechaCambioNuevo,
                ':observacion' => $observacionNuevo
            ]);
            echo "<p>Cambio añadido con éxito.</p>";
            // Redirige a la misma página para evitar reenvío del formulario
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Editar cambio
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCambioEditar'])) {
            $idCambioEditar = $_POST['idCambioEditar'];
            $cedulaTecEditar = $_POST['cedulaTecEditar'];
            $nombreCompletoEditar = $_POST['nombreCompletoEditar'];
            $codHerramientaEditar = $_POST['codHerramientaEditar'];
            $tipoCambioEditar = $_POST['tipoCambioEditar'];
            $fechaCambioEditar = $_POST['fechaCambioEditar'];
            $observacionEditar = $_POST['observacionEditar'];

            $pdo = conexion();
            $sql = "UPDATE cambios_herramientas SET CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, COD_HERRAMIENTA = :codHerramienta, TIPO_CAMBIO = :tipoCambio, FECHA_CAMBIO = :fechaCambio, OBSERVACION = :observacion WHERE ID_CAMBIO = :idCambio";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cedulaTec' => $cedulaTecEditar,
                ':nombreCompleto' => $nombreCompletoEditar,
                ':codHerramienta' => $codHerramientaEditar,
                ':tipoCambio' => $tipoCambioEditar,
                ':fechaCambio' => $fechaCambioEditar,
                ':observacion' => $observacionEditar,
                ':idCambio' => $idCambioEditar
            ]);
            echo "<p>Cambio actualizado con éxito.</p>";
            // Redirige a la misma página para evitar reenvío del formulario
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Eliminar cambio
        if (isset($_GET['eliminar'])) {
            $idCambioEliminar = $_GET['eliminar'];
            $pdo = conexion();
            $sql = "DELETE FROM cambios_herramientas WHERE ID_CAMBIO = :idCambio";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':idCambio' => $idCambioEliminar]);
            echo "<p>Cambio eliminado con éxito.</p>";
            // Redirige a la misma página para actualizar la lista de cambios
            exit;
        }
        ?>
    </div>

    <!-- Mostrar mensaje de error en HTML -->
<?php if (!empty($mensajeError)): ?>
    <p class="error-message"><?php echo $mensajeError; ?></p>
<?php endif; ?>
</body>

</html>