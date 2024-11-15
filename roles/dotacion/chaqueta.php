<?php 
// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'epp';
$username = 'root';
$password = '';

// Incluye el archivo de conexión a la base de datos
include 'CONEXIONES.php';

// Añadir chaqueta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['periodo'], $_POST['motivo'], $_POST['cha_can'], $_POST['cha_talla'], $_POST['cha_con'], $_POST['entrega'], $_POST['observacion'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo']; 
        $cha_can = $_POST['cha_can'];
        $cha_talla = $_POST['cha_talla'];
        $cha_con = $_POST['cha_con'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "INSERT INTO chaqueta (CEDULA, NOMBRE, FECHA_ENTREGA, PERIODO, MOTIVO, CHA_CAN, CHA_TALLA, CHA_CON,ENTREGA, OBSERVACION) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssissss", $cedula, $nombre, $fecha_entrega, $periodo, $motivo, $cha_can, $cha_talla, $cha_con,$entrega, $observacion);
            if ($stmt->execute()) {
                header('Location: chaqueta.php'); // Redirige de vuelta a la lista de chaquetas
                exit;
            } else {
                echo "<p>Error al insertar los datos: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
        }

        $conn->close();
    } else {
        echo "<p>Datos del formulario incompletos.</p>";
    }
}

// Obtener chaquetas con búsqueda
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inicializar variables para búsqueda
    $cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
    $cha_con = isset($_GET['cha_con']) ? $_GET['cha_con'] : '';

    // Búsqueda dinámica
    $sql = "SELECT * FROM chaqueta WHERE 1=1";
    $params = [];

    // Agregar condiciones dinámicas para la búsqueda
    if (!empty($cedula)) {
        $sql .= " AND LOWER(CEDULA) LIKE LOWER(:cedula)";
        $params[':cedula'] = '%' . $cedula . '%';
    }
    if (!empty($cha_con)) {
        $sql .= " AND LOWER(CHA_CON) LIKE LOWER(:cha_con)";
        $params[':cha_con'] = '%' . $cha_con . '%';
    }

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta con los parámetros si se proporcionan
    $stmt->execute($params);

    $chaqueta = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $noRecordsFound = empty($chaqueta); // Verificar si no hay registros
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <style>

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Chaqueta</title>
    <link rel="stylesheet" href="estilo_u.css">
</head>
<body>
<button class="toggle-button"  onclick="window.location.href='../../inicio_sesion/dotacion.php';">Página Principal</button>


    <!-- Formulario de Búsqueda -->
    <h1>Busqueda Chaquetas</h1>
    <form method="GET" action="">
        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($cedula); ?>">
        
        <label for="cha_con">Consecutivo Chaqueta:</label>
        <input type="text" name="cha_con" id="cha_con" value="<?php echo htmlspecialchars($cha_con); ?>">
        
        <button type="submit" class="btn">Buscar</button>
    </form>

    <!-- Añadir chaqueta -->
    <h1>Gestión de Chaquetas</h1>
    <div class="center">
        <button id="toggleAddForm">Agregar Chaqueta</button>
    </div>
    
    <div class="form-container" id="addForm">
        <form method="POST" action="">
            <label for="cedula">Cédula:</label>
            <input type="number" id="cedula" name="cedula" required>
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" required>
            
            <label for="periodo">Período:</label>
            <input type="text" id="periodo" name="periodo">
            
            <label for="motivo">Motivo:</label>
            <input type="text" id="motivo" name="motivo">
            
            <label for="cha_can">Cantidad:</label>
            <input type="number" id="cha_can" name="cha_can">
            
            <label for="cha_talla">Talla:</label>
            <input type="text" id="cha_talla" name="cha_talla">
            
            <label for="cha_con">Consecutivo Chaqueta:</label>
            <input type="number" id="cha_con" name="cha_con" required>
            
            <label for="entrega">Entregado Por::</label>
            <textarea id="entrega" name="entrega"></textarea>

            <label for="observacion">Observación:</label>
            <textarea id="observacion" name="observacion"></textarea>
            
            <div class="button-centered">
                <input class="button" type="submit" value="Agregar Chaqueta">
                <a href="chaqueta.php" class="button">Cancelar</a>
            </div>
        </form>
    </div>

    <h1>Listado de Chaquetas</h1>
    <table>
        <thead>
            <tr>
                <th>Consecutivo</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Fecha Entrega</th>
                <th>Periodo</th>
                <th>Motivo</th>
                <th>Cantidad</th>
                <th>Talla</th>
                <th>Entregado Por</th>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($noRecordsFound): ?>
                <tr>
                    <td colspan="10" style="text-align:center;">No existen registros</td>
                </tr>
            <?php else: ?>
                <?php foreach ($chaqueta as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['CHA_CON']); ?></td>
                    <td><?php echo htmlspecialchars($item['CEDULA']); ?></td>
                    <td><?php echo htmlspecialchars($item['NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($item['FECHA_ENTREGA']); ?></td>
                    <td><?php echo htmlspecialchars($item['PERIODO']); ?></td>
                    <td><?php echo htmlspecialchars($item['MOTIVO']); ?></td>
                    <td><?php echo htmlspecialchars($item['CHA_CAN']); ?></td>
                    <td><?php echo htmlspecialchars($item['CHA_TALLA']); ?></td>
                    <td><?php echo htmlspecialchars($item['ENTREGA']); ?></td>
                    <td><?php echo htmlspecialchars($item['OBSERVACION']); ?></td>
                    <td>
                        <a href="editar_chaqueta.php?cha_con=<?php echo urlencode($item['CHA_CON']); ?>">Editar</a>
                        <a href="eliminar_chaquetas.php?cha_con=<?php echo urlencode($item['CHA_CON']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta chaqueta?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        document.getElementById('toggleAddForm').addEventListener('click', function() {
            const formContainer = document.getElementById('addForm');
            formContainer.classList.toggle('active');
        });
    </script>
</body>
</html>
