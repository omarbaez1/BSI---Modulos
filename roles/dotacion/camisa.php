<?php
// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'epp';
$username = 'root';
$password = '';

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

// Incluye el archivo de conexión a la base de datos
include 'CONEXIONES.php';

// Añadir camisa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['periodo'], $_POST['motivo'], $_POST['cam_can'], $_POST['cam_talla'], $_POST['cam_con'], $_POST['entrega'], $_POST['observacion'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo']; 
        $cam_can = $_POST['cam_can'];
        $cam_talla = $_POST['cam_talla'];
        $cam_con = $_POST['cam_con'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "INSERT INTO camisa (CEDULA, NOMBRE, FECHA_ENTREGA, PERIODO, MOTIVO, CAM_CAN, CAM_TALLA, CAM_CON,ENTREGA, OBSERVACION) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssissss", $cedula, $nombre, $fecha_entrega, $periodo, $motivo, $cam_can, $cam_talla, $cam_con,$entrega, $observacion);
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['PHP_SELF']); // Redirige a la misma página
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

// Eliminar camisa si se solicita
// Procesar la eliminación de un técnico
if (isset($_GET['delete'])) {
    $cam_con = $_GET['delete'];
    
    $sql = "DELETE FROM camisa WHERE CAM_CON = :cam_con";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cam_con' => $cam_con]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inicializar variables para búsqueda
    $cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
    $cam_con = isset($_GET['cam_con']) ? $_GET['cam_con'] : '';

    // Búsqueda dinámica
    $sql = "SELECT * FROM camisa WHERE 1=1";
    $params = [];

    // Agregar condiciones dinámicas para la búsqueda
    if (!empty($cedula)) {
        $sql .= " AND LOWER(CEDULA) LIKE LOWER(:cedula)";
        $params[':cedula'] = '%' . $cedula . '%';
    }
    if (!empty($cam_con)) {
        $sql .= " AND LOWER(CAM_CON) LIKE LOWER(:cam_con)";
        $params[':cam_con'] = '%' . $cam_con . '%';
    }

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta con los parámetros si se proporcionan
    $stmt->execute($params);

    $camisa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $noRecordsFound = empty($camisa); // Verificar si no hay registros
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <style>
/* Estilos generales */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f8ff; /* Color de fondo */
    margin: 0;
    padding: 20px;
    color: #333;
}

h1, h2 {
    color: #007acc; /* Color principal */
    text-shadow: 1px 1px 2px rgba(0, 122, 204, 0.5);
}

label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #005f99;
}

/* Estilos de entrada */
input[type="text"], input[type="number"], input[type="date"], textarea, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #007acc;
    border-radius: 8px;
    box-shadow: inset 0 0 5px rgba(0, 122, 204, 0.2);
    font-size: 16px;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus, input[type="number"]:focus, input[type="date"]:focus, textarea:focus, select:focus {
    border-color: #005f99;
    box-shadow: 0 0 8px rgba(0, 122, 204, 0.5);
}

/* Estilos de botón */
input[type="submit"], .toggle-button {
    background-color: #007acc;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0, 122, 204, 0.3);
    transition: all 0.3s ease;
    display: inline-block;
}

input[type="submit"]:hover, .toggle-button:hover {
    background-color: #005f99;
    transform: translateY(-3px);
}

input[type="submit"]:active, .toggle-button:active {
    background-color: #004a7a;
    transform: translateY(1px);
}

.toggle-button {
    margin-bottom: 20px;
}

.form-container {
    background-color: #e6f7ff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 122, 204, 0.1);
    display: none; /* Oculto por defecto */
}

.form-container.active {
    display: block; /* Mostrar cuando se activa */
}

/* Estilos para la tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 122, 204, 0.1);
}

table th, table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
}

table th {
    background-color: #007acc;
    color: white;
    font-size: 18px;
    position: relative;
}

table th::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    background: white;
    bottom: 0;
    left: 50%;
    transition: width 0.4s ease, left 0.4s ease;
}

table th:hover::after {
    width: 100%;
    left: 0;
}

table td {
    color: #555;
}

table tr:hover {
    background-color: #f0f8ff;
}

/* Enlaces */
a {
    color: #007acc;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

a:hover {
    color: #005f99;
}

/* Botones en la tabla */
.actions a {
    padding: 8px 12px;
    background-color: #007acc;
    color: white;
    border-radius: 20px;
    text-align: center;
    margin-right: 5px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: inline-block; /* Asegura que el botón tenga dimensiones */
}

.actions a:hover {
    background-color: #005f99;
    transform: translateY(-3px);
}

.actions a:active {
    background-color: #004a7a;
    transform: translateY(1px);
}

/* Estilos para mensajes de error o éxito */
.message {
    padding: 10px;
    margin: 20px 0;
    border-radius: 8px;
    color: white;
    font-weight: bold;
}

.message.success {
    background-color: #28a745;
}

.message.error {
    background-color: #dc3545;
}

/* Estilos para la búsqueda y la tabla */
.search-container {
    margin-bottom: 20px;
}

.search-container input {
    display: inline-block;
    width: calc(50% - 20px);
}

.search-container button {
    display: inline-block;
    width: 18%;
}
.button-centered {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.button-centered input[type="submit"] {
    background-color: #007acc;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0, 122, 204, 0.3);
    transition: all 0.3s ease;
    display: inline-block;
}


input[type="text"], input[type="number"], input[type="date"], textarea, select {
    width: 60%;
    padding: 12px;
    margin-bottom: 0px;
    border: 1px solid #007acc;
    border-radius: 8px;
    box-shadow: inset 0 0 px rgba(0, 122, 204, 0.2);
    font-size: 16px;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}




    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Camisa</title>
    <link rel="stylesheet" href="estilo_u.css">

</head>
<body>
<button class="toggle-button"  onclick="window.location.href='../../inicio_sesion/dotacion.php';">Página Principal</button>
<!-- Formulario de Búsqueda -->
<h1>Busqueda de Camisas</h1>
<form method="GET" action="">
    <label for="cedula">Cédula:</label>
    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($cedula); ?>">
    
    <label for="cam_con">Consecutivo Camisa:</label>
    <input type="text" name="cam_con" id="cam_con" value="<?php echo htmlspecialchars($cam_con); ?>">
    
    <button type="submit" class="btn">Buscar</button>
</form>

<h1>Gestión de Camisas</h1>

<!-- Añadir camisa -->
<div class="center">
    <button id="toggleAddForm">Agregar Camisa</button>
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
        
        <label for="cam_can">Cantidad:</label>
        <input type="number" id="cam_can" name="cam_can">
        
        <label for="cam_talla">Talla:</label>
        <input type="text" id="cam_talla" name="cam_talla">
        
        <label for="cam_con">Consecutivo Camisa:</label>
        <input type="number" id="cam_con" name="cam_con" required>

        <label for="cam_con">Entregado por:</label>
        <input type="text" id="entrega" name="entrega" required>
        
        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"></textarea>
        
        <div class="button-centered">
            <input class="button" type="submit" value="Agregar Camisa">
        </div>
    </form>
</div>

<h1>Listado de Camisas</h1>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Fecha de Entrega</th>
                <th>Período</th>
                <th>Motivo</th>
                <th>Cantidad</th>
                <th>Talla</th>
                <th>Consecutivo</th>
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
                <?php foreach ($camisa as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['CEDULA']); ?></td>
                    <td><?php echo htmlspecialchars($item['NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($item['FECHA_ENTREGA']); ?></td>
                    <td><?php echo htmlspecialchars($item['PERIODO']); ?></td>
                    <td><?php echo htmlspecialchars($item['MOTIVO']); ?></td>
                    <td><?php echo htmlspecialchars($item['CAM_CAN']); ?></td>
                    <td><?php echo htmlspecialchars($item['CAM_TALLA']); ?></td>
                    <td><?php echo htmlspecialchars($item['CAM_CON']); ?></td>
                    <td><?php echo htmlspecialchars($item['ENTREGA']); ?></td>
                    <td><?php echo htmlspecialchars($item['OBSERVACION']); ?></td>
                    <td>
                        <a href="?delete=<?php echo urlencode($item['CAM_CON']); ?>" onclick="return confirm('¿Estás seguro de que deseas editar esta camisa?');">Eliminar</a>
                        <a href="editar_camisa.php?cam_con=<?php echo urlencode($item['CAM_CON']); ?>" onclick="return confirm('¿Estás seguro de que deseas editar esta camisa?');">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('toggleAddForm').onclick = function() {
        var form = document.getElementById('addForm');
        form.classList.toggle('active');
    };
</script>
</body>
</html>
