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

// Inicializar variable para las asignaciones
$asignaciones = [];

// Buscar un registro
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $sql = "SELECT * FROM asignaciones WHERE 
            ID_ASIGNACION LIKE :search_query OR
            CEDULA_TEC LIKE :search_query OR
            ID_USUARIO LIKE :search_query OR
            FECHA_ASIGNACION LIKE :search_query OR
            TIPO_ASIGNACION LIKE :search_query OR
            ID_ITEM_ASIGNADO LIKE :search_query OR
            CANTIDAD_ASIGNADA LIKE :search_query OR
            ESTADO_HERRAMIENTA LIKE :search_query OR
            OBSERVACION LIKE :search_query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search_query' => "%$search_query%"]);
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Editar un registro
if (isset($_POST['edit'])) {
    $sql = "UPDATE asignaciones SET CEDULA_TEC = :CEDULA_TEC, ID_USUARIO = :ID_USUARIO, FECHA_ASIGNACION = :FECHA_ASIGNACION, TIPO_ASIGNACION = :TIPO_ASIGNACION, ID_ITEM_ASIGNADO = :ID_ITEM_ASIGNADO, CANTIDAD_ASIGNADA = :CANTIDAD_ASIGNADA, ESTADO_HERRAMIENTA = :ESTADO_HERRAMIENTA, OBSERVACION = :OBSERVACION WHERE ID_ASIGNACION = :ID_ASIGNACION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':ID_ASIGNACION' => $_POST['ID_ASIGNACION'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':ID_USUARIO' => $_POST['ID_USUARIO'],
        ':FECHA_ASIGNACION' => $_POST['FECHA_ASIGNACION'],
        ':TIPO_ASIGNACION' => $_POST['TIPO_ASIGNACION'],
        ':ID_ITEM_ASIGNADO' => $_POST['ID_ITEM_ASIGNADO'],
        ':CANTIDAD_ASIGNADA' => $_POST['CANTIDAD_ASIGNADA'],
        ':ESTADO_HERRAMIENTA' => $_POST['ESTADO_HERRAMIENTA'],
        ':OBSERVACION' => $_POST['OBSERVACION']
    ]);
    header('Location: asignaciones.php'); // Redirige a la misma página para evitar reenvíos de formulario
}

// Eliminar un registro
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM asignaciones WHERE ID_ASIGNACION = :ID_ASIGNACION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ID_ASIGNACION' => $_GET['delete']]);
    header('Location: asignaciones.php'); // Redirige a la misma página
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Asignaciones</title>
    <link rel="stylesheet" href="../css/estilos_materiales.css">
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
        .toggle-button {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
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
    <h1>Gestión de Asignaciones</h1>

    <!-- Formulario de búsqueda -->
    <h2>Buscar Asignación</h2>
    <form method="post">
        <input type="text" name="search_query" placeholder="Buscar por cualquier campo">
        <input type="submit" name="search" value="Buscar">
    </form>

    <?php if (!empty($asignaciones)): ?>
        <!-- Listar asignaciones si hay resultados de la búsqueda -->
        <h2>Resultados de la búsqueda</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Asignación</th>
                    <th>Cédula Técnico</th>
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
                <?php foreach ($asignaciones as $asignacion): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['CEDULA_TEC']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ID_USUARIO']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['FECHA_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['TIPO_ASIGNACION']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ID_ITEM_ASIGNADO']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['CANTIDAD_ASIGNADA']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['ESTADO_HERRAMIENTA']); ?></td>
                        <td><?php echo htmlspecialchars($asignacion['OBSERVACION']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Mensaje si no hay resultados de búsqueda -->
        <p>No se encontraron asignaciones.</p>
    <?php endif; ?>
</body>
</html>
