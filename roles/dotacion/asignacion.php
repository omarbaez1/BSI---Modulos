<?php  
// Conexión a la base de datos
function conexion_administrador() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=epp', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . htmlspecialchars($e->getMessage());
        die();
    }
}

$pdo = conexion_administrador();

// Procesar la adición de un nuevo técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    // Obtener datos del formulario
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $cam_con = $_POST['cam_con'];
    $pan_con = $_POST['pan_con'];
    $ove_con = $_POST['ove_con'];
    $cha_con = $_POST['cha_con'];
    
    // Insertar nuevo registro
    $sql = "INSERT INTO asignacion (CEDULA, NOMBRE, CAM_CON, PAN_CON, OVE_CON, CHA_CON) 
            VALUES (:cedula, :nombre, :cam_con, :pan_con, :ove_con, :cha_con)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cedula' => $cedula,
        ':nombre' => $nombre,
        ':cam_con' => $cam_con,
        ':pan_con' => $pan_con,
        ':ove_con' => $ove_con,
        ':cha_con' => $cha_con
    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la edición de un técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    // Obtener datos del formulario
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $cam_con = $_POST['cam_con'];
    $pan_con = $_POST['pan_con'];
    $ove_con = $_POST['ove_con'];
    $cha_con = $_POST['cha_con'];
    
    // Actualizar el registro
    $sql = "UPDATE asignacion 
            SET NOMBRE = :nombre, CAM_CON = :cam_con, PAN_CON = :pan_con, OVE_CON = :ove_con, CHA_CON = :cha_con
            WHERE CEDULA = :cedula";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cedula' => $cedula,
        ':nombre' => $nombre,
        ':cam_con' => $cam_con,
        ':pan_con' => $pan_con,
        ':ove_con' => $ove_con,
        ':cha_con' => $cha_con,
    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la eliminación de un técnico
if (isset($_GET['delete'])) {
    $cedula = $_GET['delete'];
    
    $sql = "DELETE FROM asignacion WHERE CEDULA = :cedula";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cedula' => $cedula]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Obtener los datos del técnico para edición, si se solicita
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $cedula = $_GET['edit'];
    
    $sql = "SELECT * FROM asignacion WHERE CEDULA = :cedula";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cedula' => $cedula]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($editData) {
        $editMode = true;
    }
}

// Inicializar variables para búsqueda
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
$cam_con = isset($_GET['cam_con']) ? $_GET['cam_con'] : '';

// Búsqueda dinámica
$sql = "SELECT * FROM asignacion WHERE 1=1";
$params = [];

// Agregar condiciones dinámicas para la búsqueda
if (!empty($cedula)) {
    $sql .= " AND LOWER(CEDULA) LIKE LOWER(:cedula)";
    $params[':cedula'] = '%' . $cedula . '%';
}
if (!empty($cam_con)) {
    $sql .= " AND LOWER(cam_con) LIKE LOWER(:cam_con)";
    $params[':cam_con'] = '%' . $cam_con . '%';
}

// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con los parámetros si se proporcionan
$stmt->execute($params);

$asignacion = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Técnicos</title>
    <link rel="stylesheet" href="css/estilos_materiales.css">
    <style>
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

<h2>Buscar Técnicos</h2>

<!-- Formulario de Búsqueda -->
<form method="GET" action="">
    <label for="cedula">Cédula:</label>
    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($cedula); ?>">
    
    <label for="cam_con">Consecutivo Camisa:</label>
    <input type="text" name="cam_con" id="cam_con" value="<?php echo htmlspecialchars($cam_con); ?>">
    
    <button type="submit" class="btn">Buscar</button>
</form>

<?php if ($editMode): ?>
    <!-- Formulario para Editar Técnico -->
    <h2>Editar asignación</h2>
    <form method="POST" action="">
        <input type="hidden" name="edit" value="true">
        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($editData['CEDULA']); ?>">

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombre" id="nombreCompleto" value="<?php echo htmlspecialchars($editData['NOMBRE']); ?>" required>
        
        <label for="cam_con">Consecutivo Camisa:</label>
        <input type="text" name="cam_con" id="cam_con" value="<?php echo htmlspecialchars($editData['CAM_CON']); ?>" required>
        
        <label for="pan_con">Consecutivo Pantalón:</label>
        <input type="text" name="pan_con" id="pan_con" value="<?php echo htmlspecialchars($editData['PAN_CON']); ?>" required>
        
        <label for="ove_con">Consecutivo Overol:</label>
        <input type="text" name="ove_con" id="ove_con" value="<?php echo htmlspecialchars($editData['OVE_CON']); ?>" required>
        
        <label for="cha_con">Consecutivo Chaqueta:</label>
        <input type="text" name="cha_con" id="cha_con" value="<?php echo htmlspecialchars($editData['CHA_CON']); ?>" required>

        <button type="submit" class="btn">Actualizar</button>
    </form>
<?php else: ?>
    <!-- Formulario para Agregar Técnico -->
    <h2>Agregar Técnico</h2>
    <button class="btn" onclick="toggleForm()">Agregar Nuevo Técnico</button>
    <div id="add-form">
        <form method="POST" action="">
            <input type="hidden" name="add" value="true">

            <label for="cedula">Cédula:</label>
            <input type="text" name="cedula" id="cedula" required>
            
            <label for="nombre">Nombre Completo:</label>
            <input type="text" name="nombre" id="nombre" required>
            
            <label for="cam_con">Consecutivo Camisa:</label>
            <input type="text" name="cam_con" id="cam_con" required>
            
            <label for="pan_con">Consecutivo Pantalón:</label>
            <input type="text" name="pan_con" id="pan_con" required>
            
            <label for="ove_con">Consecutivo Overol:</label>
            <input type="text" name="ove_con" id="ove_con" required>
            
            <label for="cha_con">Consecutivo Chaqueta:</label>
            <input type="text" name="cha_con" id="cha_con" required>

            <button type="submit" class="btn">Agregar</button>
        </form>
    </div>
<?php endif; ?>

<h2>Lista de Técnicos</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Consecutivo Camisa</th>
                <th>Consecutivo Pantalón</th>
                <th>Consecutivo Overol</th>
                <th>Consecutivo Chaqueta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($asignacion as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['CEDULA']); ?></td>
                    <td><?php echo htmlspecialchars($row['NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($row['CAM_CON']); ?></td>
                    <td><?php echo htmlspecialchars($row['PAN_CON']); ?></td>
                    <td><?php echo htmlspecialchars($row['OVE_CON']); ?></td>
                    <td><?php echo htmlspecialchars($row['CHA_CON']); ?></td>
                    <td>
                        <a href="?edit=<?php echo htmlspecialchars($row['CEDULA']); ?>" class="btn">Editar</a>
                        <a href="?delete=<?php echo htmlspecialchars($row['CEDULA']); ?>" class="btn" onclick="return confirmDelete();">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
