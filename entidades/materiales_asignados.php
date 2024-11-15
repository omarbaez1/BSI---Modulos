<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Materiales Asignados</title>
    <link rel="stylesheet" href="../css/estilos_materiales_asignados.css">
    <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>

    <h2>Buscar Materiales Asignados</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codMaterial">Código de Material:</label>
        <input type="text" name="codMaterial" id="codMaterial"><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto"><br><br>

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <?php
    // Conexión a la base de datos
    function conexion(){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            die();
        }
    }

    // Función para buscar materiales asignados por código de material, cédula del técnico y nombre completo
    function buscar_materiales_asignados($codMaterial = null, $cedulaTec = null, $nombreCompleto = null){
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM materiales_asignados WHERE 1=1";
        
        if (!empty($codMaterial)) {
            $sql .= " AND COD_MATERIAL = :codMaterial";
        }
        
        if (!empty($cedulaTec)) {
            $sql .= " AND CEDULA_TEC = :cedulaTec";
        }

        if (!empty($nombreCompleto)) {
            $sql .= " AND NOMBRE_COMPLETO LIKE :nombreCompleto";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($codMaterial)) {
            $stmt->bindParam(':codMaterial', $codMaterial);
        }
        
        if (!empty($cedulaTec)) {
            $stmt->bindParam(':cedulaTec', $cedulaTec);
        }

        if (!empty($nombreCompleto)) {
            // Usar % para la búsqueda parcial en el nombre completo
            $nombreCompleto = "%$nombreCompleto%";
            $stmt->bindParam(':nombreCompleto', $nombreCompleto);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener valores del formulario
    $codMaterial = isset($_GET['codMaterial']) ? $_GET['codMaterial'] : null;
    $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
    $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;

    // Buscar y mostrar los resultados
    $asignaciones = buscar_materiales_asignados($codMaterial, $cedulaTec, $nombreCompleto);

    if (!empty($asignaciones)) {
        echo '<div class="table-container">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Código de Material</th>';
        echo '<th>Nombre del Material</th>';
        echo '<th>Cantidad Asignada</th>';
        echo '<th>Cédula Técnico</th>';
        echo '<th>Nombre Completo</th>';
        echo '<th>Fecha Asignación</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach($asignaciones as $asignacion) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($asignacion['COD_MATERIAL']) . '</td>';
            echo '<td>' . htmlspecialchars($asignacion['NOMBRE_MATERIAL']) . '</td>';
            echo '<td>' . htmlspecialchars($asignacion['CANT_ASIG']) . '</td>';
            echo '<td>' . htmlspecialchars($asignacion['CEDULA_TEC']) . '</td>';
            echo '<td>' . htmlspecialchars($asignacion['NOMBRE_COMPLETO']) . '</td>';
            echo '<td>' . htmlspecialchars($asignacion['FECHA_ASIGNACIÓN']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>No se encontraron resultados.</p>';
    }
    ?>

</body>
</html>
