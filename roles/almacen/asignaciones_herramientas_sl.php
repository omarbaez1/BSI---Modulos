<?php
// herramienta.php

// Iniciar la sesión para manejar mensajes de éxito o error
session_start();

// Función para conectar a la base de datos
function conexion() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Manejo de errores de conexión
        die('Error de conexión: ' . $e->getMessage());
    }
}

// Manejar la eliminación de una herramienta
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codHerramienta'])) {
    $codHerramienta = $_GET['codHerramienta'];
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("DELETE FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->execute();
        $_SESSION['message'] = "Herramienta eliminada exitosamente.";
        $_SESSION['msg_type'] = "success";
        header("Location: herramienta.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al eliminar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("Location: herramienta.php");
        exit();
    }
}

// Manejar la adición de una nueva herramienta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Obtener y sanitizar los datos del formulario
    $codHerramientaAdd = trim($_POST['codHerramientaAdd']);
    $herramientaAdd = trim($_POST['herramientaAdd']);
    $buenoEstadoAdd = intval($_POST['buenoEstadoAdd']);
    $estadoRegularAdd = intval($_POST['estadoRegularAdd']);
    $malEstadoAdd = intval($_POST['malEstadoAdd']);
    $cantidadAsignadaAdd = intval($_POST['cantidadAsignadaAdd']);

    // Calcular 'Total Almacén' y 'Existencia BSI'
    $totalAlmacenAdd = $buenoEstadoAdd + $estadoRegularAdd + $malEstadoAdd;
    $existenciaBsiAdd = $totalAlmacenAdd + $cantidadAsignadaAdd;

    try {
        $pdo = conexion();
        // Verificar si el código de herramienta ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramientaAdd);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['message'] = "El código de herramienta ya existe.";
            $_SESSION['msg_type'] = "warning";
        } else {
            // Insertar la nueva herramienta
            $stmt = $pdo->prepare("INSERT INTO `herramienta` (`COD_HERRAMIENTA`, `HERRAMIENTA`, `BUEN_ESTADO`, `ESTADO_REGULAR`, `MAL_ESTADO`, `TOTAL_ALMACEN`, `CANTIDAD_ASIGNADA`, `EXISTENCIA_BSI`) VALUES (:codHerramienta, :herramienta, :buenoEstado, :estadoRegular, :malEstado, :totalAlmacen, :cantidadAsignada, :existenciaBsi)");
            $stmt->bindParam(':codHerramienta', $codHerramientaAdd);
            $stmt->bindParam(':herramienta', $herramientaAdd);
            $stmt->bindParam(':buenoEstado', $buenoEstadoAdd);
            $stmt->bindParam(':estadoRegular', $estadoRegularAdd);
            $stmt->bindParam(':malEstado', $malEstadoAdd);
            $stmt->bindParam(':totalAlmacen', $totalAlmacenAdd);
            $stmt->bindParam(':cantidadAsignada', $cantidadAsignadaAdd);
            $stmt->bindParam(':existenciaBsi', $existenciaBsiAdd);
            $stmt->execute();
            $_SESSION['message'] = "Herramienta agregada exitosamente.";
            $_SESSION['msg_type'] = "success";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al agregar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: herramienta.php");
    exit();
}

// Manejar la edición de una herramienta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    // Obtener y sanitizar los datos del formulario de edición
    $codHerramienta = trim($_POST['codHerramienta']);
    $herramienta = trim($_POST['herramienta']);
    $buenoEstado = intval($_POST['buenoEstado']);
    $estadoRegular = intval($_POST['estadoRegular']);
    $malEstado = intval($_POST['malEstado']);
    $cantidadAsignada = intval($_POST['cantidadAsignada']);

    // Calcular 'Total Almacén' y 'Existencia BSI'
    $totalAlmacen = $buenoEstado + $estadoRegular + $malEstado;
    $existenciaBsi = $totalAlmacen + $cantidadAsignada;

    try {
        $pdo = conexion();
        // Actualizar la herramienta
        $stmt = $pdo->prepare("UPDATE `herramienta` SET `HERRAMIENTA` = :herramienta, `BUEN_ESTADO` = :buenoEstado, `ESTADO_REGULAR` = :estadoRegular, `MAL_ESTADO` = :malEstado, `TOTAL_ALMACEN` = :totalAlmacen, `CANTIDAD_ASIGNADA` = :cantidadAsignada, `EXISTENCIA_BSI` = :existenciaBsi WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->bindParam(':herramienta', $herramienta);
        $stmt->bindParam(':buenoEstado', $buenoEstado);
        $stmt->bindParam(':estadoRegular', $estadoRegular);
        $stmt->bindParam(':malEstado', $malEstado);
        $stmt->bindParam(':totalAlmacen', $totalAlmacen);
        $stmt->bindParam(':cantidadAsignada', $cantidadAsignada);
        $stmt->bindParam(':existenciaBsi', $existenciaBsi);
        $stmt->execute();
        $_SESSION['message'] = "Herramienta actualizada exitosamente.";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al actualizar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: herramienta.php");
    exit();
}

// Obtener el formulario de edición si se solicita
$editHerramienta = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codHerramienta'])) {
    $codHerramienta = $_GET['codHerramienta'];
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("SELECT * FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->execute();
        $editHerramienta = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$editHerramienta) {
            $_SESSION['message'] = "Herramienta no encontrada.";
            $_SESSION['msg_type'] = "warning";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al obtener la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

?>

<?php 
// Conexión a la base de datos
function conexion_administrador() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . htmlspecialchars($e->getMessage());
        die();
    }
}

$pdo = conexion_administrador();

// Procesar la adición de un nuevo registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    // Obtener datos del formulario
    $idAsignacion = $_POST['idAsignacion'];
    $cedulaTec = $_POST['cedulaTec'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $idUsuario = $_POST['idUsuario'];
    $fechaAsignacion = $_POST['fechaAsignacion'];
    $tipoAsignacion = $_POST['tipoAsignacion'];
    $idItemAsignado = $_POST['idItemAsignado'];
    $cantidadAsignada = $_POST['cantidadAsignada'];
    $estadoHerramienta = $_POST['estadoHerramienta'];
    $observacion = $_POST['observacion'];

    // Insertar nuevo registro
    $sql = "INSERT INTO asignaciones (ID_ASIGNACION, CEDULA_TEC, NOMBRE_COMPLETO, ID_USUARIO, FECHA_ASIGNACION, TIPO_ASIGNACION, ID_ITEM_ASIGNADO, CANTIDAD_ASIGNADA, ESTADO_HERRAMIENTA, OBSERVACION) 
            VALUES (:idAsignacion, :cedulaTec, :nombreCompleto, :idUsuario, :fechaAsignacion, :tipoAsignacion, :idItemAsignado, :cantidadAsignada, :estadoHerramienta, :observacion)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idAsignacion' => $idAsignacion,
        ':cedulaTec' => $cedulaTec,
        ':nombreCompleto' => $nombreCompleto,
        ':idUsuario' => $idUsuario,
        ':fechaAsignacion' => $fechaAsignacion,
        ':tipoAsignacion' => $tipoAsignacion,
        ':idItemAsignado' => $idItemAsignado,
        ':cantidadAsignada' => $cantidadAsignada,
        ':estadoHerramienta' => $estadoHerramienta,
        ':observacion' => $observacion
    ]);

    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la edición de un registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    // Obtener datos del formulario
    $idAsignacion = $_POST['idAsignacion'];
    $cedulaTec = $_POST['cedulaTec'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $idUsuario = $_POST['idUsuario'];
    $fechaAsignacion = $_POST['fechaAsignacion'];
    $tipoAsignacion = $_POST['tipoAsignacion'];
    $idItemAsignado = $_POST['idItemAsignado'];
    $cantidadAsignada = $_POST['cantidadAsignada'];
    $estadoHerramienta = $_POST['estadoHerramienta'];
    $observacion = $_POST['observacion'];

    // Actualizar el registro
    $sql = "UPDATE asignaciones 
            SET CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, ID_USUARIO = :idUsuario, FECHA_ASIGNACION = :fechaAsignacion, TIPO_ASIGNACION = :tipoAsignacion, ID_ITEM_ASIGNADO = :idItemAsignado, CANTIDAD_ASIGNADA = :cantidadAsignada, ESTADO_HERRAMIENTA = :estadoHerramienta, OBSERVACION = :observacion
            WHERE ID_ASIGNACION = :idAsignacion";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idAsignacion' => $idAsignacion,
        ':cedulaTec' => $cedulaTec,
        ':nombreCompleto' => $nombreCompleto,
        ':idUsuario' => $idUsuario,
        ':fechaAsignacion' => $fechaAsignacion,
        ':tipoAsignacion' => $tipoAsignacion,
        ':idItemAsignado' => $idItemAsignado,
        ':cantidadAsignada' => $cantidadAsignada,
        ':estadoHerramienta' => $estadoHerramienta,
        ':observacion' => $observacion
    ]);

    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la eliminación de un registro
if (isset($_GET['delete'])) {
    $idAsignacion = $_GET['delete'];

    $sql = "DELETE FROM asignaciones WHERE ID_ASIGNACION = :idAsignacion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idAsignacion' => $idAsignacion]);

    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Obtener los datos del registro para edición, si se solicita
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $idAsignacion = $_GET['edit'];

    $sql = "SELECT * FROM asignaciones WHERE ID_ASIGNACION = :idAsignacion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idAsignacion' => $idAsignacion]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($editData) {
        $editMode = true;
    }
}

// Inicializar variables para búsqueda
$cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : '';
$nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : '';
$estadoHerramienta = isset($_GET['estadoHerramienta']) ? $_GET['estadoHerramienta'] : '';

// Búsqueda dinámica
$sql = "SELECT * FROM asignaciones WHERE 1=1";
$params = [];

// Agregar condiciones dinámicas para la búsqueda
if (!empty($cedulaTec)) {
    $sql .= " AND LOWER(CEDULA_TEC) LIKE LOWER(:cedulaTec)";
    $params[':cedulaTec'] = '%' . $cedulaTec . '%';
}
if (!empty($nombreCompleto)) {
    $sql .= " AND LOWER(NOMBRE_COMPLETO) LIKE LOWER(:nombreCompleto)";
    $params[':nombreCompleto'] = '%' . $nombreCompleto . '%';
}
if (!empty($estadoHerramienta)) {
    $sql .= " AND LOWER(ESTADO_HERRAMIENTA) LIKE LOWER(:estadoHerramienta)";
    $params[':estadoHerramienta'] = '%' . $estadoHerramienta . '%';
}

// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con los parámetros si se proporcionan
$stmt->execute($params);

$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirección</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;


            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px; /* Espacio entre las columnas */
        }

        .column {
            width: 48%; /* Ancho de las columnas */
            box-sizing: border-box;
        }


        .button {
            background-color: #4CAF50; /* Verde */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
        .button:hover {
            background-color: #45a049;
        }

        .form-slide, .form-edit {
            display: none;
            margin: 20px 0;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .form-slide.active, .form-edit.active {
            display: block;
        }
        .btn-slide, .btn-close-edit {
            cursor: pointer;
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        .btn-slide:hover, .btn-close-edit:hover {
            background-color: #0056b3;
        }
        .btn {
            padding: 10px 15px;
            background-color: #28A745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
        .message.warning {
            background-color: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEEBA;
        }
        .message.danger {
            background-color: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        .input-field {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            box-sizing: border-box;
        }
        .form-group {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .table-container {
            overflow-x: auto;
        }

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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        #add-form {
            display: none;
            margin: 20px 0;
        }
    </style>

<script>
        function toggleForm() {
            var form = document.getElementById('add-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar este registro?');
        }
    </script>


</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white;">
    <h3 style="margin: 0; flex-grow: 1; text-align: center;">CAMBIOS DE HERRAMIENTAS</h3>
    <img src="herramientas.png" alt="Logo" style="width: 150px; height: auto; margin-left: 20px;">
</header>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/administrador.php';">Página Principal</button>

    <div class="container">
    <div class="column-content">
        <h2>Gestión de Herramientas</h2>

        <!-- Mostrar mensajes de sesión -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['msg_type']; ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['msg_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de Búsqueda -->
        <form method="GET" action="herramienta.php">
            <div class="form-group">
                <label for="codHerramienta">Código de Herramienta:</label>
                <input type="text" name="codHerramienta" id="codHerramienta" class="input-field" value="<?php echo isset($_GET['codHerramienta']) ? htmlspecialchars($_GET['codHerramienta']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="herramienta">Nombre de la Herramienta:</label>
                <input type="text" name="herramienta" id="herramienta" class="input-field" value="<?php echo isset($_GET['herramienta']) ? htmlspecialchars($_GET['herramienta']) : ''; ?>">
            </div>

            <input type="submit" value="Buscar" class="btn">
        </form>

        <!-- Botón para Mostrar/Ocultar el Formulario de Añadir -->
        <button class="btn-slide" onclick="toggleSlide()">Añadir Nueva Herramienta</button>
        <div id="formSlide" class="form-slide">
            <h3>Agregar Nueva Herramienta</h3>
            <form method="POST" action="herramienta.php">
                <input type="hidden" name="action" value="add">

                <label for="codHerramientaAdd">Código de Herramienta:</label>
                <input type="text" name="codHerramientaAdd" id="codHerramientaAdd" required><br><br>

                <label for="herramientaAdd">Nombre de la Herramienta:</label>
                <input type="text" name="herramientaAdd" id="herramientaAdd" required><br><br>

                <label for="buenoEstadoAdd">Buen Estado:</label>
                <input type="number" name="buenoEstadoAdd" id="buenoEstadoAdd" min="0" required><br><br>

                <label for="estadoRegularAdd">Estado Regular:</label>
                <input type="number" name="estadoRegularAdd" id="estadoRegularAdd" min="0" required><br><br>

                <label for="malEstadoAdd">Mal Estado:</label>
                <input type="number" name="malEstadoAdd" id="malEstadoAdd" min="0" required><br><br>

                <label for="cantidadAsignadaAdd">Cantidad Asignada:</label>
                <input type="number" name="cantidadAsignadaAdd" id="cantidadAsignadaAdd" min="0" required><br><br>

                <input type="submit" value="Agregar Herramienta" class="btn">
            </form>
        </div>

        <!-- Formulario de Edición (solo se muestra si se está editando) -->
        <?php if ($editHerramienta): ?>
            <div id="formEdit" class="form-edit active">
                <h3>Editar Herramienta</h3>
                <form method="POST" action="herramienta.php">
                    <input type="hidden" name="action" value="edit">

                    <label for="codHerramienta">Código de Herramienta:</label>
                    <input type="text" name="codHerramienta" id="codHerramienta" value="<?php echo htmlspecialchars($editHerramienta['COD_HERRAMIENTA']); ?>" readonly><br><br>

                    <label for="herramienta">Nombre de la Herramienta:</label>
                    <input type="text" name="herramienta" id="herramienta" value="<?php echo htmlspecialchars($editHerramienta['HERRAMIENTA']); ?>" required><br><br>

                    <label for="buenoEstado">Buen Estado:</label>
                    <input type="number" name="buenoEstado" id="buenoEstado" min="0" value="<?php echo htmlspecialchars($editHerramienta['BUEN_ESTADO']); ?>" required><br><br>

                    <label for="estadoRegular">Estado Regular:</label>
                    <input type="number" name="estadoRegular" id="estadoRegular" min="0" value="<?php echo htmlspecialchars($editHerramienta['ESTADO_REGULAR']); ?>" required><br><br>

                    <label for="malEstado">Mal Estado:</label>
                    <input type="number" name="malEstado" id="malEstado" min="0" value="<?php echo htmlspecialchars($editHerramienta['MAL_ESTADO']); ?>" required><br><br>

                    <label for="cantidadAsignada">Cantidad Asignada:</label>
                    <input type="number" name="cantidadAsignada" id="cantidadAsignada" min="0" value="<?php echo htmlspecialchars($editHerramienta['CANTIDAD_ASIGNADA']); ?>" required><br><br>

                    <input type="submit" value="Actualizar Herramienta" class="btn">
                    <button type="button" class="btn-close-edit" onclick="hideEditForm()">Cancelar</button>
                </form>
            </div>
        <?php endif; ?>

        <h2>Resultados de la Búsqueda</h2>

        <div class="table-container">
            <?php
            // Función para buscar herramientas por código y nombre
            function buscar_herramienta($codHerramienta = null, $herramienta = null) {
                $pdo = conexion();

                // Construir la consulta SQL con filtros opcionales
                $sql = "SELECT * FROM `herramienta` WHERE 1=1";
                
                if (!empty($codHerramienta)) {
                    $sql .= " AND `COD_HERRAMIENTA` = :codHerramienta";
                }
                
                if (!empty($herramienta)) {
                    $sql .= " AND `HERRAMIENTA` LIKE :herramienta";
                }

                $stmt = $pdo->prepare($sql);
                
                // Vincular los parámetros si existen
                if (!empty($codHerramienta)) {
                    $stmt->bindParam(':codHerramienta', $codHerramienta);
                }
                
                if (!empty($herramienta)) {
                    // Usar % para la búsqueda parcial en el nombre de la herramienta
                    $herramienta = "%$herramienta%";
                    $stmt->bindParam(':herramienta', $herramienta);
                }

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Obtener valores del formulario de búsqueda
            $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
            $herramienta = isset($_GET['herramienta']) ? $_GET['herramienta'] : null;

            // Buscar y obtener los resultados
            $herramientas = buscar_herramienta($codHerramienta, $herramienta);

            if (!empty($herramientas)) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código de Herramienta</th>';
                echo '<th>Nombre de la Herramienta</th>';
                echo '<th>Buen Estado</th>';
                echo '<th>Estado Regular</th>';
                echo '<th>Mal Estado</th>';
                echo '<th>Total Almacén</th>';
                echo '<th>Cantidad Asignada</th>';
                echo '<th>Existencia BSI</th>';
                echo '<th>Acciones</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($herramientas as $herr) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($herr['COD_HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['BUEN_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['ESTADO_REGULAR']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['MAL_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['TOTAL_ALMACEN']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['CANTIDAD_ASIGNADA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['EXISTENCIA_BSI']) . '</td>';
                    echo '<td>';
                    echo '<a href="herramienta.php?action=edit&codHerramienta=' . urlencode($herr['COD_HERRAMIENTA']) . '">Editar</a> | ';
                    echo '<a href="herramienta.php?action=delete&codHerramienta=' . urlencode($herr['COD_HERRAMIENTA']) . '" onclick="return confirm(\'¿Está seguro de que desea eliminar este registro?\')">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No se encontraron resultados.</p>';
            }
            ?>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar el formulario de añadir
        function toggleSlide() {
            var formSlide = document.getElementById('formSlide');
            formSlide.classList.toggle('active');
        }

        // Función para ocultar el formulario de edición
        function hideEditForm() {
            var formEdit = document.getElementById('formEdit');
            if (formEdit) {
                formEdit.classList.remove('active');
                // Redireccionar sin los parámetros de edición
                window.location.href = 'herramienta.php';
            }
        }
    </script>
    </div>

    <div class="column-content">
        <h2>Buscar Asignaciones</h2>

<!-- Formulario de Búsqueda -->
<form method="GET" action="">
    <label for="cedulaTec">Cédula Técnico:</label>
    <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($cedulaTec); ?>">

    <label for="nombreCompleto">Nombre Completo:</label>
    <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($nombreCompleto); ?>">

    <label for="estadoHerramienta">Estado Herramienta:</label>
    <select name="estadoHerramienta" id="estadoHerramienta">
        <option value="">Seleccione</option>
        <option value="Buen Estado" <?php if ($estadoHerramienta == 'Buen Estado') echo 'selected'; ?>>Buen Estado</option>
        <option value="Estado Regular" <?php if ($estadoHerramienta == 'Estado Regular') echo 'selected'; ?>>Estado Regular</option>
        <option value="Mal Estado" <?php if ($estadoHerramienta == 'Mal Estado') echo 'selected'; ?>>Mal Estado</option>
        <option value="No Asignado" <?php if ($estadoHerramienta == 'No Asignado') echo 'selected'; ?>>No Asignado</option>
        <!-- Agregar otras opciones según sea necesario -->
    </select>
    <button type="submit" class="btn">Buscar</button>
    <a href="celulares.php" class="btn" style="background-color: #6C757D; margin-left: 10px;">Limpiar</a>
</form>

<!-- Botón para Añadir Nueva Asignación -->
<button onclick="toggleForm()" class="btn">Añadir Nueva Asignación</button>

<!-- Formulario para Añadir Nueva Asignación -->
<div id="add-form">
    <form method="POST" action="">
        <input type="hidden" name="add" value="true">


        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" required>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" required>

        <label for="idUsuario">ID Usuario:</label>
        <input type="text" name="idUsuario" id="idUsuario" required>

        <label for="fechaAsignacion">Fecha Asignación:</label>
        <input type="date" name="fechaAsignacion" id="fechaAsignacion" required>

        <label for="tipoAsignacion">Tipo Asignación:</label>
        <input type="text" name="tipoAsignacion" id="tipoAsignacion" required>

        <label for="idItemAsignado">ID Item Asignado:</label>
        <input type="text" name="idItemAsignado" id="idItemAsignado" required>

        <label for="cantidadAsignada">Cantidad Asignada:</label>
        <input type="number" name="cantidadAsignada" id="cantidadAsignada" required>

        <label for="estadoHerramienta">Estado Herramienta:</label>
        <input type="text" name="estadoHerramienta" id="estadoHerramienta" required>

        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion"></textarea>

        <button type="submit" class="btn">Añadir</button>
    </form>
</div>

<!-- Formulario para Editar Asignación -->
<?php if ($editMode): ?>
    <h2>Editar Asignación</h2>
    <form method="POST" action="">
        <input type="hidden" name="edit" value="true">
        <input type="hidden" name="idAsignacion" value="<?php echo htmlspecialchars($editData['ID_ASIGNACION']); ?>">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($editData['CEDULA_TEC']); ?>" required>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($editData['NOMBRE_COMPLETO']); ?>" required>

        <label for="idUsuario">ID Usuario:</label>
        <input type="text" name="idUsuario" id="idUsuario" value="<?php echo htmlspecialchars($editData['ID_USUARIO']); ?>" required>

        <label for="fechaAsignacion">Fecha Asignación:</label>
        <input type="date" name="fechaAsignacion" id="fechaAsignacion" value="<?php echo htmlspecialchars($editData['FECHA_ASIGNACION']); ?>" required>

        <label for="tipoAsignacion">Tipo Asignación:</label>
        <input type="text" name="tipoAsignacion" id="tipoAsignacion" value="<?php echo htmlspecialchars($editData['TIPO_ASIGNACION']); ?>" required>

        <label for="idItemAsignado">ID Item Asignado:</label>
        <input type="text" name="idItemAsignado" id="idItemAsignado" value="<?php echo htmlspecialchars($editData['ID_ITEM_ASIGNADO']); ?>" required>

        <label for="cantidadAsignada">Cantidad Asignada:</label>
        <input type="number" name="cantidadAsignada" id="cantidadAsignada" value="<?php echo htmlspecialchars($editData['CANTIDAD_ASIGNADA']); ?>" required>

        <label for="estadoHerramienta">Estado Herramienta:</label>
        <input type="text" name="estadoHerramienta" id="estadoHerramienta" value="<?php echo htmlspecialchars($editData['ESTADO_HERRAMIENTA']); ?>" required>

        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion"><?php echo htmlspecialchars($editData['OBSERVACION']); ?></textarea>

        <button type="submit" class="btn">Actualizar</button>
    </form>
    <?php
// Función para actualizar la cantidad asignada y el buen estado en herramienta.php
function actualizarCantidadYEstado() {
    try {
        $pdo = conexion(); // Usar la función de conexión definida anteriormente

        // Primero, obtener todos los registros de asignaciones
        $stmt = $pdo->query("SELECT ID_ITEM_ASIGNADO, CANTIDAD_ASIGNADA FROM asignaciones");
        $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($asignaciones as $asignacion) {
            $idItemAsignado = $asignacion['ID_ITEM_ASIGNADO'];
            $cantidadAsignada = $asignacion['CANTIDAD_ASIGNADA'];

            // Obtener la información actual de la herramienta
            $stmt = $pdo->prepare("SELECT CANTIDAD_ASIGNADA, BUEN_ESTADO FROM herramienta WHERE COD_HERRAMIENTA = :idItemAsignado");
            $stmt->bindParam(':idItemAsignado', $idItemAsignado);
            $stmt->execute();
            $herramienta = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($herramienta) {
                $cantidadAsignadaActual = $herramienta['CANTIDAD_ASIGNADA'];
                $buenoEstadoActual = $herramienta['BUEN_ESTADO'];

                // Calcular nueva cantidad asignada y buen estado
                $nuevaCantidadAsignada = $cantidadAsignadaActual + $cantidadAsignada;
                $nuevoBuenoEstado = $buenoEstadoActual - $cantidadAsignada;

                // Actualizar los valores en la tabla herramienta
                $stmt = $pdo->prepare("UPDATE herramienta SET CANTIDAD_ASIGNADA = :nuevaCantidadAsignada, BUEN_ESTADO = :nuevoBuenoEstado WHERE COD_HERRAMIENTA = :idItemAsignado");
                $stmt->bindParam(':nuevaCantidadAsignada', $nuevaCantidadAsignada);
                $stmt->bindParam(':nuevoBuenoEstado', $nuevoBuenoEstado);
                $stmt->bindParam(':idItemAsignado', $idItemAsignado);
                $stmt->execute();
            }
        }

        echo "Actualizaciones realizadas con éxito.";
    } catch (PDOException $e) {
        echo "Error al actualizar datos: " . $e->getMessage();
    }
}
// Llamar a la función de actualización si se necesita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    actualizarCantidadYEstado();
    header("Location: herramienta.php"); // Redirigir a la misma página después de la actualización
    exit();
}
?>
<?php endif; ?>
<h2>Resultados de la Búsqueda</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID Asignación</th>
                <th>Cédula Técnico</th>
                <th>Nombre Completo</th>
                <th>ID Usuario</th>
                <th>Fecha Asignación</th>
                <th>Tipo Asignación</th>
                <th>ID Item Asignado</th>
                <th>Cantidad Asignada</th>
                <th>Estado Herramienta</th>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($asignaciones)): ?>
                <?php foreach ($asignaciones as $asignacion): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['CEDULA_TEC']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['NOMBRE_COMPLETO']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ID_USUARIO']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['FECHA_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['TIPO_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ID_ITEM_ASIGNADO']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['CANTIDAD_ASIGNADA']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ESTADO_HERRAMIENTA']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['OBSERVACION']); ?></td>
                        <td>
                            <a href="?edit=<?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?>" class="btn">Editar</a>
                            <a href="?delete=<?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?>" class="btn" onclick="return confirmDelete()">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11">No se encontraron resultados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</body>
</html>
