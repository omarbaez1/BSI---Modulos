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
    $id_asignacion_material = $_POST['ID_ASIGNACION_MATERIAL'];
    $cedula_supervisor = $_POST['CEDULA_SUPERVISOR'];
    $cedula_tec = $_POST['CEDULA_TEC'];
    $nombre_completo = $_POST['NOMBRE_COMPLETO'];
    $cod_material = $_POST['COD_MATERIAL'];
    $id_usuario = $_POST['ID_USUARIO'];
    $nombre_material = $_POST['NOMBRE_MATERIAL'];
    $consecutivo_inicial = $_POST['CONSECUTIVO_INICIAL'];
    $consecutivo_final = $_POST['CONSECUTIVO_FINAL'];
    $cant_asig = $_POST['CANT_ASIG'];
    $fecha_asignacion = $_POST['FECHA_ASIGNACION'];
    $observacion = $_POST['OBSERVACION'];

    // Insertar nuevo registro
    $sql = "INSERT INTO materiales_asignados (ID_ASIGNACION_MATERIAL, CEDULA_SUPERVISOR, CEDULA_TEC, 
    NOMBRE_COMPLETO, COD_MATERIAL, ID_USUARIO, NOMBRE_MATERIAL, 
    CONSECUTIVO_INICIAL, CONSECUTIVO_FINAL, CANT_ASIG, FECHA_ASIGNACION, OBSERVACION) 
    VALUES (:id_asignacion_material, :cedula_supervisor, :cedula_tec, 
    :nombre_completo, :cod_material, :id_usuario, :nombre_material, 
    :consecutivo_inicial, :consecutivo_final, :cant_asig, :fecha_asignacion, :observacion)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_asignacion_material' => $id_asignacion_material,
        ':cedula_supervisor' => $cedula_supervisor,
        ':cedula_tec' => $cedula_tec,
        ':nombre_completo' => $nombre_completo,
        ':cod_material' => $cod_material,
        ':id_usuario' => $id_usuario,
        ':nombre_material' => $nombre_material,
        ':consecutivo_inicial' => $consecutivo_inicial,
        ':consecutivo_final' => $consecutivo_final,
        ':cant_asig' => $cant_asig,
        ':fecha_asignacion' => $fecha_asignacion,
        ':observacion' => $observacion,
    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la edición de un registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    // Obtener datos del formulario
    $id_asignacion_material = $_POST['ID_ASIGNACION_MATERIAL'];
    $cedula_supervisor = $_POST['CEDULA_SUPERVISOR'];
    $cedula_tec = $_POST['CEDULA_TEC'];
    $nombre_completo = $_POST['NOMBRE_COMPLETO'];
    $cod_material = $_POST['COD_MATERIAL'];
    $id_usuario = $_POST['ID_USUARIO'];
    $nombre_material = $_POST['NOMBRE_MATERIAL'];
    $consecutivo_inicial = $_POST['CONSECUTIVO_INICIAL'];
    $consecutivo_final = $_POST['CONSECUTIVO_FINAL'];
    $cant_asig = $_POST['CANT_ASIG'];
    $fecha_asignacion = $_POST['FECHA_ASIGNACION'];
    $observacion = $_POST['OBSERVACION'];

    // Actualizar el registro
    $sql = "UPDATE materiales_asignados SET 
    CEDULA_SUPERVISOR = :cedula_supervisor,
    CEDULA_TEC = :cedula_tec,
    NOMBRE_COMPLETO = :nombre_completo,
    COD_MATERIAL = :cod_material,
    ID_USUARIO = :id_usuario,
    NOMBRE_MATERIAL = :nombre_material,
    CONSECUTIVO_INICIAL = :consecutivo_inicial,
    CONSECUTIVO_FINAL = :consecutivo_final,
    CANT_ASIG = :cant_asig,
    FECHA_ASIGNACION = :fecha_asignacion,
    OBSERVACION = :observacion
    WHERE ID_ASIGNACION_MATERIAL = :id_asignacion_material";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_asignacion_material' => $id_asignacion_material,
        ':cedula_supervisor' => $cedula_supervisor,
        ':cedula_tec' => $cedula_tec,
        ':nombre_completo' => $nombre_completo,
        ':cod_material' => $cod_material,
        ':id_usuario' => $id_usuario,
        ':nombre_material' => $nombre_material,
        ':consecutivo_inicial' => $consecutivo_inicial,
        ':consecutivo_final' => $consecutivo_final,
        ':cant_asig' => $cant_asig,
        ':fecha_asignacion' => $fecha_asignacion,
        ':observacion' => $observacion,
    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la eliminación de un registro
if (isset($_GET['delete'])) {
    $id_asignacion_material = $_GET['delete'];
    
    $sql = "DELETE FROM materiales_asignados WHERE ID_ASIGNACION_MATERIAL = :id_asignacion_material";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_asignacion_material' => $id_asignacion_material]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Obtener los datos para edición, si se solicita
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $id_asignacion_material = $_GET['edit'];
    
    $sql = "SELECT * FROM materiales_asignados WHERE ID_ASIGNACION_MATERIAL = :id_asignacion_material";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_asignacion_material' => $id_asignacion_material]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($editData) {
        $editMode = true;
    }
}

// Inicializar variables para búsqueda
$cedula_supervisor = isset($_GET['CEDULA_SUPERVISOR']) ? $_GET['CEDULA_SUPERVISOR'] : '';

$cod_material =isset($_GET['COD_MATERIAL']) ? $_GET['COD_MATERIAL'] : '';

$cedula_tec =isset($_GET['CEDULA_TEC']) ? $_GET['CEDULA_TEC'] : '';
// Búsqueda dinámica
$sql = "SELECT * FROM materiales_asignados WHERE 1=1";
$params = [];

// Agregar condiciones dinámicas para la búsqueda
if (!empty($cedula_supervisor)) {
    $sql .= " AND LOWER(CEDULA_SUPERVISOR) LIKE LOWER(:cedula_supervisor)";
    $params[':cedula_supervisor'] = '%' . $cedula_supervisor . '%';
}
if (!empty($cod_material)) {
    $sql .= " AND LOWER(COD_MATERIAL) LIKE LOWER(:cod_material)";
    $params[':cod_material'] = '%' . $cod_material . '%';
}
if (!empty($cedula_tec)) {
    $sql .= " AND LOWER(CEDULA_TEC) LIKE LOWER(:cedula_tec)";
    $params[':cedula_tec'] = '%' . $cedula_tec . '%';
}
// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con los parámetros si se proporcionan
$stmt->execute($params);

$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Materiales</title>
    <link rel="stylesheet" href="pipe.css">
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
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">MATERIALES ASIGNADOS</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/precintoA.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/administrador.php';">Página Principal</button>

<h2>Búsqueda de Asignaciones</h2>

<!-- Formulario de Búsqueda -->
<form method="GET" action="">
    <label for="CEDULA_SUPERVISOR">Cédula Supervisor:</label>
    <input type="text" name="CEDULA_SUPERVISOR" id="CEDULA_SUPERVISOR" value="<?php echo htmlspecialchars($cedula_supervisor); ?>">
    <button type="submit" class="btn">Buscar</button>
</form>
<form method="GET" action="">
    <label for="COD_MATERIAL">Codigo Material:</label>
    <input type="text" name="COD_MATERIAL" id="COD_MATERIAL" value="<?php echo htmlspecialchars($cod_material); ?>">
    <button type="submit" class="btn">Buscar</button>
</form>
<form method="GET" action="">
    <label for="CEDULA_TEC">Cedula Tecnico:</label>
    <input type="text" name="CEDULA_TEC" id="CEDULA_TEC" value="<?php echo htmlspecialchars($cedula_tec); ?>">
    <button type="submit" class="btn">Buscar</button>
</form>


<?php if ($editMode): ?>
    <!-- Formulario para Editar Registro -->
    <h2>Editar Asignación</h2>
    <form method="POST" action="">
        <input type="hidden" name="edit" value="true">
        <input type="hidden" name="ID_ASIGNACION_MATERIAL" value="<?php echo htmlspecialchars($editData['ID_ASIGNACION_MATERIAL']); ?>">
        
        <label for="CEDULA_SUPERVISOR">Cédula Supervisor:</label>
        <input type="text" name="CEDULA_SUPERVISOR" value="<?php echo htmlspecialchars($editData['CEDULA_SUPERVISOR']); ?>">

        <label for="CEDULA_TEC">Cédula Técnico:</label>
        <input type="text" name="CEDULA_TEC" value="<?php echo htmlspecialchars($editData['CEDULA_TEC']); ?>">

        <label for="NOMBRE_COMPLETO">Nombre Completo:</label>
        <input type="text" name="NOMBRE_COMPLETO" value="<?php echo htmlspecialchars($editData['NOMBRE_COMPLETO']); ?>">
        
        <label for="COD_MATERIAL">Código Material:</label>
        <input type="text" name="COD_MATERIAL" value="<?php echo htmlspecialchars($editData['COD_MATERIAL']); ?>">
        
        <label for="ID_USUARIO">ID Usuario:</label>
        <input type="text" name="ID_USUARIO" value="<?php echo htmlspecialchars($editData['ID_USUARIO']); ?>">
        
        <label for="NOMBRE_MATERIAL">Nombre Material:</label>
        <input type="text" name="NOMBRE_MATERIAL" value="<?php echo htmlspecialchars($editData['NOMBRE_MATERIAL']); ?>">
        
        <label for="CONSECUTIVO_INICIAL">Consecutivo Inicial:</label>
        <input type="text" name="CONSECUTIVO_INICIAL" value="<?php echo htmlspecialchars($editData['CONSECUTIVO_INICIAL']); ?>">
        
        <label for="CONSECUTIVO_FINAL">Consecutivo Final:</label>
        <input type="text" name="CONSECUTIVO_FINAL" value="<?php echo htmlspecialchars($editData['CONSECUTIVO_FINAL']); ?>">
        
        <label for="CANT_ASIG">Cantidad Asignada:</label>
        <input type="text" name="CANT_ASIG" value="<?php echo htmlspecialchars($editData['CANT_ASIG']); ?>">
        
        <label for="FECHA_ASIGNACION">Fecha Asignación:</label>
        <input type="date" name="FECHA_ASIGNACION" value="<?php echo htmlspecialchars($editData['FECHA_ASIGNACION']); ?>">
        
        <label for="OBSERVACION">Observación:</label>
        <input type="text" name="OBSERVACION" value="<?php echo htmlspecialchars($editData['OBSERVACION']); ?>">
        
        <button type="submit" class="btn">Guardar Cambios</button>
    </form>
<?php endif; ?>

<!-- Formulario para Añadir Registro -->
<h2>Añadir Nueva Asignación</h2>
<button class="btn" onclick="toggleForm()">Añadir Asignación Material</button>
<div id="add-form" style="display:none;">
    <form method="POST" action="">
        <input type="hidden" name="add" value="true">

        <label for="CEDULA_SUPERVISOR">Cédula Supervisor:</label>
        <input type="text" name="CEDULA_SUPERVISOR" required>

        <label for="CEDULA_TEC">Cédula Técnico:</label>
        <input type="text" name="CEDULA_TEC" required>

        <label for="NOMBRE_COMPLETO">Nombre Completo:</label>
        <input type="text" name="NOMBRE_COMPLETO" required>

        <label for="COD_MATERIAL">Código Material:</label>
        <input type="text" name="COD_MATERIAL" required>

        <label for="ID_USUARIO">ID Usuario:</label>
        <input type="text" name="ID_USUARIO" required>

        <label for="NOMBRE_MATERIAL">Nombre Material:</label>
        <input type="text" name="NOMBRE_MATERIAL" required>

        <label for="CONSECUTIVO_INICIAL">Consecutivo Inicial:</label>
        <input type="text" name="CONSECUTIVO_INICIAL" required>

        <label for="CONSECUTIVO_FINAL">Consecutivo Final:</label>
        <input type="text" name="CONSECUTIVO_FINAL" required>

        <label for="CANT_ASIG">Cantidad Asignada:</label>
        <input type="text" name="CANT_ASIG" required>

        <label for="FECHA_ASIGNACION">Fecha Asignación:</label>
        <input type="date" name="FECHA_ASIGNACION" required>

        <label for="OBSERVACION">Observación:</label>
        <input type="text" name="OBSERVACION">

        <button type="submit" class="btn">Añadir Asignación</button>
    </form>
</div>

<!-- Mostrar las Asignaciones -->
<h2>Lista de Asignaciones</h2>
<div class="table-container">
    <table>
        <thead>
            <tr>  
                <th>Cédula Supervisor</th>
                <th>Cédula Técnico</th>
                <th>Nombre Completo</th>
                <th>Código Material</th>
                <th>ID Usuario</th>
                <th>Nombre Material</th>
                <th>Consecutivo Inicial</th>
                <th>Consecutivo Final</th>
                <th>Cantidad Asignada</th>
                <th>Fecha Asignación</th>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($registros): ?>
                <?php foreach ($registros as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['CEDULA_SUPERVISOR']); ?></td>
                        <td><?php echo htmlspecialchars($item['CEDULA_TEC']); ?></td>
                        <td><?php echo htmlspecialchars($item['NOMBRE_COMPLETO']); ?></td>
                        <td><?php echo htmlspecialchars($item['COD_MATERIAL']); ?></td>
                        <td><?php echo htmlspecialchars($item['ID_USUARIO']); ?></td>
                        <td><?php echo htmlspecialchars($item['NOMBRE_MATERIAL']); ?></td>
                        <td><?php echo htmlspecialchars($item['CONSECUTIVO_INICIAL']); ?></td>
                        <td><?php echo htmlspecialchars($item['CONSECUTIVO_FINAL']); ?></td>
                        <td><?php echo htmlspecialchars($item['CANT_ASIG']); ?></td>
                        <td><?php echo htmlspecialchars($item['FECHA_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($item['OBSERVACION']); ?></td>
                        <td>
                            <a href="?edit=<?php echo urlencode($item['ID_ASIGNACION_MATERIAL']); ?>" class="btn1">Editar</a>
                            <a href="?delete=<?php echo urlencode($item['ID_ASIGNACION_MATERIAL']); ?>" class="btn1" onclick="return confirmDelete();">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">No se encontraron asignaciones.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
