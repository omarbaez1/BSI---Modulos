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

// Buscar un registro
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $sql = "SELECT * FROM celulares 
            WHERE COD_CELULAR LIKE :search_query 
            OR CEDULA_TEC LIKE :search_query 
            OR NOMBRE_COMPLETO LIKE :search_query 
            OR MARCA LIKE :search_query 
            OR MODELO LIKE :search_query 
            OR PROPIETARIO LIKE :search_query 
            OR IMEI1 LIKE :search_query 
            OR IMEI2 LIKE :search_query 
            OR NUMERO_ASIGNADO LIKE :search_query 
            OR FECHA_INGRESO LIKE :search_query 
            OR ESTADO LIKE :search_query 
            OR FECHA_ASIGNACION LIKE :search_query 
            OR OBSERVACION LIKE :search_query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search_query' => "%$search_query%"]);
    $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Mostrar todos los registros por defecto
    $sql = "SELECT * FROM celulares";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Celulares</title>
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
    <h1>Gestión de Celulares</h1>

    <!-- Formulario de búsqueda -->
    <h2>Buscar Celular</h2>
    <form method="post">
        <input type="text" name="search_query" placeholder="Buscar en cualquier campo">
        <input type="submit" name="search" value="Buscar">
    </form>

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
