<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Material</title>
    <link rel="stylesheet" href="../css/estilos_material.css">
</head>
<body>

    <h2>Buscar Material</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codMaterial">Código de Material:</label>
        <input type="text" name="codMaterial" id="codMaterial">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec">

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <?php
    // Conexión a la base de datos
    function conexion() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            die();
        }
    }

    // Función para buscar material por código de material y cédula del técnico
    function buscar_material($codMaterial = null, $cedulaTec = null) {
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM `material` WHERE 1=1";
        
        if (!empty($codMaterial)) {
            $sql .= " AND `COD_MATERIAL` = :codMaterial";
        }
        
        if (!empty($cedulaTec)) {
            $sql .= " AND `CEDULA_TEC` = :cedulaTec";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($codMaterial)) {
            $stmt->bindParam(':codMaterial', $codMaterial);
        }
        
        if (!empty($cedulaTec)) {
            $stmt->bindParam(':cedulaTec', $cedulaTec);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener valores del formulario
    $codMaterial = isset($_GET['codMaterial']) ? $_GET['codMaterial'] : null;
    $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;

    // Buscar y mostrar los resultados
    $materiales = buscar_material($codMaterial, $cedulaTec);
    ?>

    <?php if (!empty($materiales)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código de Material</th>
                        <th>Nombre del Material</th>
                        <th>Consecutivo Inicial</th>
                        <th>Consecutivo Final</th>
                        <th>Cédula Técnico</th>
                        <th>Nombre Completo</th>
                        <th>Total</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiales as $material): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($material['COD_MATERIAL']); ?></td>
                            <td><?php echo htmlspecialchars($material['NOMBRE_MATERIAL']); ?></td>
                            <td><?php echo htmlspecialchars($material['CONSECUTIVO_INICIAL']); ?></td>
                            <td><?php echo htmlspecialchars($material['CONSECUTIVO_FINAL']); ?></td>
                            <td><?php echo htmlspecialchars($material['CEDULA_TEC']); ?></td>
                            <td><?php echo htmlspecialchars($material['NOMBRE_COMPLETO']); ?></td>
                            <td><?php echo htmlspecialchars($material['TOTAL']); ?></td>
                            <td><?php echo htmlspecialchars($material['OBSERVACION']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

</body>
</html>
