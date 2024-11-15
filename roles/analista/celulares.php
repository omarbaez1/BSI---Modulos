<?php
// Conexión a la base de datos
function conexion_administrador() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        die();
    }
}

$pdo = conexion_administrador();

// Inicializar variables de búsqueda
$search_query = '';
$search_field = 'COD_CELULAR';

// Buscar registros
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $search_field = $_POST['search_field'] ?? 'COD_CELULAR'; // Usar un valor predeterminado
    $allowed_fields = ['COD_CELULAR', 'CEDULA_TEC', 'NOMBRE_COMPLETO', 'MARCA', 'MODELO', 'PROPIETARIO', 'IMEI1', 'IMEI2', 'NUMERO_ASIGNADO', 'FECHA_INGRESO', 'ESTADO', 'FECHA_ASIGNACION', 'OBSERVACION'];

    // Validar el campo de búsqueda
    if (in_array($search_field, $allowed_fields)) {
        $sql = "SELECT * FROM celulares WHERE $search_field LIKE :search_query";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':search_query' => "%$search_query%"]);
        $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Si el campo de búsqueda no es válido, mostrar todos los registros
        $sql = "SELECT * FROM celulares";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Mostrar todos los registros por defecto
    $sql = "SELECT * FROM celulares";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Añadir un nuevo registro
if (isset($_POST['add'])) {
    $sql = "INSERT INTO celulares (COD_CELULAR, CEDULA_TEC, NOMBRE_COMPLETO, MARCA, MODELO, PROPIETARIO, IMEI1, IMEI2, NUMERO_ASIGNADO, FECHA_INGRESO, ESTADO, FECHA_ASIGNACION, OBSERVACION) VALUES (:COD_CELULAR, :CEDULA_TEC, :NOMBRE_COMPLETO, :MARCA, :MODELO, :PROPIETARIO, :IMEI1, :IMEI2, :NUMERO_ASIGNADO, :FECHA_INGRESO, :ESTADO, :FECHA_ASIGNACION, :OBSERVACION)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':COD_CELULAR' => $_POST['COD_CELULAR'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':NOMBRE_COMPLETO' => $_POST['NOMBRE_COMPLETO'],
        ':MARCA' => $_POST['MARCA'],
        ':MODELO' => $_POST['MODELO'],
        ':PROPIETARIO' => $_POST['PROPIETARIO'],
        ':IMEI1' => $_POST['IMEI1'],
        ':IMEI2' => $_POST['IMEI2'],
        ':NUMERO_ASIGNADO' => $_POST['NUMERO_ASIGNADO'],
        ':FECHA_INGRESO' => $_POST['FECHA_INGRESO'],
        ':ESTADO' => $_POST['ESTADO'],
        ':FECHA_ASIGNACION' => $_POST['FECHA_ASIGNACION'],
        ':OBSERVACION' => $_POST['OBSERVACION']
    ]);
}

// Editar un registro
if (isset($_POST['edit'])) {
    $sql = "UPDATE celulares SET CEDULA_TEC = :CEDULA_TEC, NOMBRE_COMPLETO = :NOMBRE_COMPLETO, MARCA = :MARCA, MODELO = :MODELO, PROPIETARIO = :PROPIETARIO, IMEI1 = :IMEI1, IMEI2 = :IMEI2, NUMERO_ASIGNADO = :NUMERO_ASIGNADO, FECHA_INGRESO = :FECHA_INGRESO, ESTADO = :ESTADO, FECHA_ASIGNACION = :FECHA_ASIGNACION, OBSERVACION = :OBSERVACION WHERE COD_CELULAR = :COD_CELULAR";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':COD_CELULAR' => $_POST['COD_CELULAR'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':NOMBRE_COMPLETO' => $_POST['NOMBRE_COMPLETO'],
        ':MARCA' => $_POST['MARCA'],
        ':MODELO' => $_POST['MODELO'],
        ':PROPIETARIO' => $_POST['PROPIETARIO'],
        ':IMEI1' => $_POST['IMEI1'],
        ':IMEI2' => $_POST['IMEI2'],
        ':NUMERO_ASIGNADO' => $_POST['NUMERO_ASIGNADO'],
        ':FECHA_INGRESO' => $_POST['FECHA_INGRESO'],
        ':ESTADO' => $_POST['ESTADO'],
        ':FECHA_ASIGNACION' => $_POST['FECHA_ASIGNACION'],
        ':OBSERVACION' => $_POST['OBSERVACION']
    ]);
    header('Location: celulares.php'); // Redirige a la misma página para evitar reenvíos de formulario
}

// Eliminar un registro
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM celulares WHERE COD_CELULAR = :COD_CELULAR";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':COD_CELULAR' => $_GET['delete']]);
    header('Location: celulares.php'); // Redirige a la misma página
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Celulares</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        /* Estilo para el formulario de añadir */
        .form-container {
            display: none;
            background: #f4f4f4;
            padding: 20px;
            border: 1px solid #ddd;
            margin-top: 20px;
        }
        .form-container.active {
            display: block;
        }


    </style>
    <script>
        function toggleForm() {
            var form = document.getElementById('form-container');
            if (form.classList.contains('active')) {
                form.classList.remove('active');
            } else {
                form.classList.add('active');
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
        <h3 style="margin: 0; font-size: 30px;">CELULARES</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/celularr.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/analista.php';">Página Principal</button>
    <h1>Celulares</h1>
    <!-- Formulario de búsqueda -->
    <form method="post">
        <label for="search_field">Buscar por:</label>
        <select name="search_field" id="search_field">
            <option value="COD_CELULAR" <?php echo $search_field === 'COD_CELULAR' ? 'selected' : ''; ?>>Código Celular</option>
            <option value="CEDULA_TEC" <?php echo $search_field === 'CEDULA_TEC' ? 'selected' : ''; ?>>Cédula Técnico</option>
            <option value="NOMBRE_COMPLETO" <?php echo $search_field === 'NOMBRE_COMPLETO' ? 'selected' : ''; ?>>Nombre Completo</option>
            <option value="MARCA" <?php echo $search_field === 'MARCA' ? 'selected' : ''; ?>>Marca</option>
            <option value="MODELO" <?php echo $search_field === 'MODELO' ? 'selected' : ''; ?>>Modelo</option>
            <option value="PROPIETARIO" <?php echo $search_field === 'PROPIETARIO' ? 'selected' : ''; ?>>Propietario</option>
            <option value="IMEI1" <?php echo $search_field === 'IMEI1' ? 'selected' : ''; ?>>IMEI1</option>
            <option value="IMEI2" <?php echo $search_field === 'IMEI2' ? 'selected' : ''; ?>>IMEI2</option>
            <option value="NUMERO_ASIGNADO" <?php echo $search_field === 'NUMERO_ASIGNADO' ? 'selected' : ''; ?>>Número Asignado</option>
            <option value="FECHA_INGRESO" <?php echo $search_field === 'FECHA_INGRESO' ? 'selected' : ''; ?>>Fecha Ingreso</option>
            <option value="ESTADO" <?php echo $search_field === 'ESTADO' ? 'selected' : ''; ?>>Estado</option>
            <option value="FECHA_ASIGNACION" <?php echo $search_field === 'FECHA_ASIGNACION' ? 'selected' : ''; ?>>Fecha Asignación</option>
            <option value="OBSERVACION" <?php echo $search_field === 'OBSERVACION' ? 'selected' : ''; ?>>Observación</option>
        </select><br>
        <input type="text" name="search_query" placeholder="Buscar..." value="<?php echo htmlspecialchars($search_query); ?>"><br>
        <br>
        <input type="submit" name="search" value="Buscar">


    </form>
    <br>
     <!-- Botón para mostrar/ocultar el formulario de añadir -->
     <button class="toggle-button" onclick="toggleForm()">Añadir Celular</button>

    <!-- Formulario para añadir un nuevo celular -->
    <div id="form-container" class="form-container">
        <h2>Añadir Nuevo Celular</h2>
        <form method="post">
            <label>Código Celular:</label><br>
            <input type="text" name="COD_CELULAR" required><br>
            <label>Cédula Técnico:</label><br>
            <input type="number" name="CEDULA_TEC"><br>
            <label>Nombre Completo:</label><br>
            <input type="text" name="NOMBRE_COMPLETO" required><br>
            <label>Marca:</label><br>
            <input type="text" name="MARCA" required><br>
            <label>Modelo:</label><br>
            <input type="text" name="MODELO" required><br>
            <label>Propietario:</label><br>
            <input type="text" name="PROPIETARIO" required><br>
            <label>IMEI1:</label><br>
            <input type="text" name="IMEI1" required><br>
            <label>IMEI2:</label><br>
            <input type="text" name="IMEI2"><br>
            <label>Número Asignado:</label><br>
            <input type="number" name="NUMERO_ASIGNADO" required><br>
            <label>Fecha Ingreso:</label><br>
            <input type="date" name="FECHA_INGRESO" required><br>
            <label>Estado:</label><br>
            <input type="text" name="ESTADO" required><br>
            <label>Fecha Asignación:</label><br>
            <input type="date" name="FECHA_ASIGNACION" required><br>
            <label>Observación:</label><br>
            <textarea name="OBSERVACION"></textarea><br>
            <input type="submit" name="add" value="Añadir Nuevo Celular">
        </form>
    </div>
<br>
    <!-- Listar celulares -->
    <h2>Lista de Celulares</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Código Celular</th>
                <th>Cédula Técnico</th>
                <th>Nombre Completo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Propietario</th>
                <th>IMEI1</th>
                <th>IMEI2</th>
                <th>Número Asignado</th>
                <th>Fecha Ingreso</th>
                <th>Estado</th>
                <th>Fecha Asignación</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($celulares as $celular): ?>
                <tr>
                    <td><?php echo htmlspecialchars($celular['COD_CELULAR']); ?></td>
                    <td><?php echo htmlspecialchars($celular['CEDULA_TEC']); ?></td>
                    <td><?php echo htmlspecialchars($celular['NOMBRE_COMPLETO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['MARCA']); ?></td>
                    <td><?php echo htmlspecialchars($celular['MODELO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['PROPIETARIO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['IMEI1']); ?></td>
                    <td><?php echo htmlspecialchars($celular['IMEI2']); ?></td>
                    <td><?php echo htmlspecialchars($celular['NUMERO_ASIGNADO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['FECHA_INGRESO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['ESTADO']); ?></td>
                    <td><?php echo htmlspecialchars($celular['FECHA_ASIGNACION']); ?></td>
                    <td><?php echo htmlspecialchars($celular['OBSERVACION']); ?></td>
                  
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
