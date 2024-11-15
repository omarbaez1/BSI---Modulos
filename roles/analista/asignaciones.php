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
    <title>Gestión de Asignaciones</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 90%;
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
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.33; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0;">ASIGNACIONES</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/asignaciones.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
<br>
<br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/analista.php';">Página Principal</button>

<br>
<h2>BUSCAR ASIGNACIONES</h2>

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
    </select><br>

    <input type="submit" name="search" value="Buscar"><br>

</form>

<!-- Botón para Añadir Nueva Asignación -->
<br>
<button onclick="toggleForm()" class="toggle-button">Añadir Asignación</button>

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
        <textarea name="observacion" id="observacion"></textarea><br>

        <button type="submit" class="btn">Añadir Nueva Asignación</button><br>
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