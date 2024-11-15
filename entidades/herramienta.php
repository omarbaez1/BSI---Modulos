<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Herramientas</title>
    <link rel="stylesheet" href="../css/estilos_herramienta.css">
</head>
<body>

    <div class="container">
        <h2>Buscar Herramienta</h2>

        <!-- Formulario de Búsqueda -->
        <form method="GET" action="">
            <div class="form-group">
                <label for="codHerramienta">Código de Herramienta:</label>
                <input type="text" name="codHerramienta" id="codHerramienta" class="input-field">
            </div>

            <div class="form-group">
                <label for="herramienta">Nombre de la Herramienta:</label>
                <input type="text" name="herramienta" id="herramienta" class="input-field">
            </div>

            <input type="submit" value="Buscar" class="btn">
        </form>

        <h2>Resultados de la Búsqueda</h2>

        <div class="table-container">
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

            // Función para buscar herramientas por código y nombre
            function buscar_herramienta($codHerramienta = null, $herramienta = null){
                $pdo = conexion();

                // Construir la consulta SQL con filtros opcionales
                $sql = "SELECT * FROM `herramienta` WHERE 1=1";
                
                if (!empty($codHerramienta)) {
                    $sql .= " AND `COD_HERRAMIENTA` = :codHerramienta";
                }
                
                if (!empty($herramienta)) {
                    $sql .= " AND `HERRAMIENTA` LIKE :herramienta";
                }

                $stmt = $pdo->prepare($sql);
                
                // Vincular los parámetros si existen
                if (!empty($codHerramienta)) {
                    $stmt->bindParam(':codHerramienta', $codHerramienta);
                }
                
                if (!empty($herramienta)) {
                    // Usar % para la búsqueda parcial en el nombre de la herramienta
                    $herramienta = "%$herramienta%";
                    $stmt->bindParam(':herramienta', $herramienta);
                }

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Obtener valores del formulario
            $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
            $herramienta = isset($_GET['herramienta']) ? $_GET['herramienta'] : null;

            // Buscar y mostrar los resultados
            $herramientas = buscar_herramienta($codHerramienta, $herramienta);

            if (!empty($herramientas)) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código de Herramienta</th>';
                echo '<th>Nombre de la Herramienta</th>';
                echo '<th>Buen Estado</th>';
                echo '<th>Estado Regular</th>';
                echo '<th>Mal Estado</th>';
                echo '<th>Cantidad</th>';
                echo '<th>Cantidad Asignada</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($herramientas as $herr) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($herr['COD_HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['BUEN_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['ESTADO_REGULAR']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['MAL_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['CANTIDAD_ASIGNADA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['CANTIDAD_ASIGNADA']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No se encontraron resultados.</p>';
            }
            ?>
        </div>
    </div>

</body>
</html>
