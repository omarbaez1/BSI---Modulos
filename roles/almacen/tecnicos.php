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

// Obtener la imagen según la cédula del técnico
if (isset($_GET['cedulaTec'])) {
    $cedulaTec = $_GET['cedulaTec'];
    
    try {
        $sql = "SELECT FOTO FROM tecnicos WHERE CEDULA_TEC = :cedulaTec";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cedulaTec' => $cedulaTec]);
        
        $tecnico = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tecnico && $tecnico['FOTO']) {
            header("Content-Type: image/jpeg");
            echo $tecnico['FOTO'];
        } else {
            header("Content-Type: image/png");
            readfile('imagenes/default.png'); // Imagen de ruta por defecto
        }
    } catch (PDOException $e) {
        echo 'Error al obtener la imagen: ' . htmlspecialchars($e->getMessage());
    }
}

// Procesar la adición de un nuevo técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $cedulaTec = $_POST['cedulaTec'];
    $estadoEnLaEmpresa = $_POST['estadoEnLaEmpresa'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $idSap = $_POST['idSap'];
    $cargo = $_POST['cargo'];
    $fechaIngreso = $_POST['fechaIngreso'];
    $foto = null;
    
    // Manejar la subida de la foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    // Insertar nuevo registro con la foto
    try {
        $sql = "INSERT INTO tecnicos (CEDULA_TEC, ESTADO_EN_LA_EMPRESA, NOMBRE_COMPLETO, ID_SAP, CARGO, FECHA_INGRESO, FOTO) 
                VALUES (:cedulaTec, :estadoEnLaEmpresa, :nombreCompleto, :idSap, :cargo, :fechaIngreso, :foto)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cedulaTec' => $cedulaTec,
            ':estadoEnLaEmpresa' => $estadoEnLaEmpresa,
            ':nombreCompleto' => $nombreCompleto,
            ':idSap' => $idSap,
            ':cargo' => $cargo,
            ':fechaIngreso' => $fechaIngreso,
            ':foto' => $foto
        ]);
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo 'Error al añadir técnico: ' . htmlspecialchars($e->getMessage());
    }
}

// Procesar la edición de un técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $cedulaTec = $_POST['cedulaTec'];
    $estadoEnLaEmpresa = $_POST['estadoEnLaEmpresa'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $idSap = $_POST['idSap'];
    $cargo = $_POST['cargo'];
    $fechaIngreso = $_POST['fechaIngreso'];
    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    // Actualizar el registro
    try {
        $sql = "UPDATE tecnicos 
                SET ESTADO_EN_LA_EMPRESA = :estadoEnLaEmpresa, NOMBRE_COMPLETO = :nombreCompleto, ID_SAP = :idSap, CARGO = :cargo, FECHA_INGRESO = :fechaIngreso" . 
                ($foto !== null ? ", FOTO = :foto" : "") . 
                " WHERE CEDULA_TEC = :cedulaTec";
        
        $stmt = $pdo->prepare($sql);
        $params = [
            ':cedulaTec' => $cedulaTec,
            ':estadoEnLaEmpresa' => $estadoEnLaEmpresa,
            ':nombreCompleto' => $nombreCompleto,
            ':idSap' => $idSap,
            ':cargo' => $cargo,
            ':fechaIngreso' => $fechaIngreso
        ];

        if ($foto !== null) {
            $params[':foto'] = $foto;
        }

        $stmt->execute($params);
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo 'Error al editar técnico: ' . htmlspecialchars($e->getMessage());
    }
}

// Procesar la eliminación de un técnico
if (isset($_GET['delete'])) {
    $cedulaTec = $_GET['delete'];
    
    try {
        $sql = "DELETE FROM tecnicos WHERE CEDULA_TEC = :cedulaTec";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cedulaTec' => $cedulaTec]);
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo 'Error al eliminar técnico: ' . htmlspecialchars($e->getMessage());
    }
}

// Obtener los datos del técnico para edición, si se solicita
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $cedulaTec = $_GET['edit'];
    
    try {
        $sql = "SELECT * FROM tecnicos WHERE CEDULA_TEC = :cedulaTec";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cedulaTec' => $cedulaTec]);
        $editData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($editData) {
            $editMode = true;
        }
    } catch (PDOException $e) {
        echo 'Error al obtener datos para edición: ' . htmlspecialchars($e->getMessage());
    }
}

// Inicializar variables para búsqueda unificada
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';

// Búsqueda dinámica en tres columnas
$sql = "SELECT * FROM tecnicos WHERE 1=1";
$params = [];

if (!empty($searchQuery)) {
    $sql .= " AND (LOWER(CEDULA_TEC) LIKE LOWER(:searchQuery) 
                OR LOWER(ESTADO_EN_LA_EMPRESA) LIKE LOWER(:searchQuery)
                OR LOWER(CARGO) LIKE LOWER(:searchQuery))";
    $params[':searchQuery'] = '%' . $searchQuery . '%';
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error al buscar técnicos: ' . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Técnicos</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    
    <style>
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
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">MATERIALES ASIGNADOS</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/tecnico.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>

    <button class="toggle-button" onclick="window.location.href='../../inicio_sesion/almacen.php';">Página Principal</button>
<h1>Técnicos</h1>

<!-- Formulario de Búsqueda Unificado -->
<form method="GET" action="">
    <label for="search_field">Buscar por:</label>
    <input type="text" name="searchQuery" id="searchQuery" placeholder="Buscar por Cédula, Estado o Cargo" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <br><br>
    <input type="submit" value="Buscar">
    <br>
</form>

<?php if ($editMode): ?>
    <!-- Formulario para Editar Técnico -->
    <h2>Editar Técnico</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="edit" value="true">
        <input type="hidden" name="cedulaTec" value="<?php echo htmlspecialchars($editData['CEDULA_TEC']); ?>">

        <label for="estadoEnLaEmpresa">Estado en la Empresa:</label>
        <input type="text" name="estadoEnLaEmpresa" id="estadoEnLaEmpresa" value="<?php echo htmlspecialchars($editData['ESTADO_EN_LA_EMPRESA']); ?>" required><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($editData['NOMBRE_COMPLETO']); ?>" required><br>

        <label for="idSap">ID SAP:</label>
        <input type="text" name="idSap" id="idSap" value="<?php echo htmlspecialchars($editData['ID_SAP']); ?>" required><br>

        <label for="cargo">Cargo:</label>
        <input type="text" name="cargo" id="cargo" value="<?php echo htmlspecialchars($editData['CARGO']); ?>" required><br>

        <label for="fechaIngreso">Fecha de Ingreso:</label>
        <input type="date" name="fechaIngreso" id="fechaIngreso" value="<?php echo htmlspecialchars($editData['FECHA_INGRESO']); ?>" required><br>

        <label for="foto">Foto (opcional):</label>
        <input type="file" name="foto" id="foto" accept="image/*"><br>
        <br>
        <input type="submit" class="toggle-button" value="Guardar Cambios">
    </form>
<?php else: ?>
    <!-- Formulario para Agregar Técnico -->
    <br>
    <button class="toggle-button" onclick="toggleForm()">Añadir Nuevo Técnico</button>
    <div id="add-form">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add" value="true">

            <label for="cedulaTec">Cédula:</label>
            <input type="text" name="cedulaTec" id="cedulaTec" required><br>

            <label for="estadoEnLaEmpresa">Estado en la Empresa:</label>
            <input type="text" name="estadoEnLaEmpresa" id="estadoEnLaEmpresa" required><br>

            <label for="nombreCompleto">Nombre Completo:</label>
            <input type="text" name="nombreCompleto" id="nombreCompleto" required><br>

            <label for="idSap">ID SAP:</label>
            <input type="text" name="idSap" id="idSap" required><br>

            <label for="cargo">Cargo:</label>
            <input type="text" name="cargo" id="cargo" required><br>

            <label for="fechaIngreso">Fecha de Ingreso:</label>
            <input type="date" name="fechaIngreso" id="fechaIngreso" required><br>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto" accept="image/*" required><br>

            <input type="submit" value="Agregar Técnico">
        </form>
    </div>
<?php endif; ?>

<!-- Tabla de Técnicos -->
<h2>Lista de Técnicos</h2>
<table>
    <tr>
        <th>Cédula</th>
        <th>Estado</th>
        <th>Nombre Completo</th>
        <th>ID SAP</th>
        <th>Cargo</th>
        <th>Fecha de Ingreso</th>
        <th>Foto</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($tecnicos as $tecnico): ?>
        <tr>
            <td><?php echo htmlspecialchars($tecnico['CEDULA_TEC']); ?></td>
            <td><?php echo htmlspecialchars($tecnico['ESTADO_EN_LA_EMPRESA']); ?></td>
            <td><?php echo htmlspecialchars($tecnico['NOMBRE_COMPLETO']); ?></td>
            <td><?php echo htmlspecialchars($tecnico['ID_SAP']); ?></td>
            <td><?php echo htmlspecialchars($tecnico['CARGO']); ?></td>
            <td><?php echo htmlspecialchars($tecnico['FECHA_INGRESO']); ?></td>
            <td>
                <?php if ($tecnico['FOTO']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($tecnico['FOTO']); ?>" alt="Foto" width="50">
                <?php else: ?>
                    <img src="imagenes/default.png" alt="Foto por defecto" width="50">
                <?php endif; ?>
            </td>
            <td>
                <a href="?edit=<?php echo htmlspecialchars($tecnico['CEDULA_TEC']); ?>">Editar</a>
                <a href="?delete=<?php echo htmlspecialchars($tecnico['CEDULA_TEC']); ?>" onclick="return confirmDelete();">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
