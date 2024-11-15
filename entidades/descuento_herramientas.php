<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Descuentos de Herramientas</title>
    <link rel="stylesheet" href="../css/estilos_descuentoherramientas.css">
</head>
<body>

    <h2>Buscar Descuento de Herramienta</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codHerramienta">Código de Herramienta:</label>
        <input type="text" name="codHerramienta" id="codHerramienta"><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto"><br><br>

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
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

        // Función para buscar descuentos de herramientas por código, cédula, y nombre
        function buscar_descuento($codHerramienta = null, $cedulaTec = null, $nombreCompleto = null){
            $pdo = conexion();

            // Construir la consulta SQL con filtros opcionales
            $sql = "SELECT * FROM `descuentos_herramientas` WHERE 1=1";
            
            if (!empty($codHerramienta)) {
                $sql .= " AND `COD_HERRAMIENTA` = :codHerramienta";
            }
            
            if (!empty($cedulaTec)) {
                $sql .= " AND `CEDULA_TEC` = :cedulaTec";
            }
            
            if (!empty($nombreCompleto)) {
                $sql .= " AND `NOMBRE_COMPLETO` LIKE :nombreCompleto";
            }

            $stmt = $pdo->prepare($sql);
            
            // Vincular los parámetros si existen
            if (!empty($codHerramienta)) {
                $stmt->bindParam(':codHerramienta', $codHerramienta);
            }
            
            if (!empty($cedulaTec)) {
                $stmt->bindParam(':cedulaTec', $cedulaTec);
            }
            
            if (!empty($nombreCompleto)) {
                $nombreCompleto = '%' . $nombreCompleto . '%'; // Agregar comodines para búsqueda parcial
                $stmt->bindParam(':nombreCompleto', $nombreCompleto);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Obtener valores del formulario
        $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
        $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
        $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;

        // Buscar y mostrar los resultados
        $descuentos = buscar_descuento($codHerramienta, $cedulaTec, $nombreCompleto);

        if (!empty($descuentos)) {
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>Código de Herramienta</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>Tipo de Evento</th>';
            echo '<th>Fecha del Evento</th>';
            echo '<th>Valor del Descuento</th>';
            echo '<th>Observación</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            foreach($descuentos as $descuento) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($descuento['COD_HERRAMIENTA']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['TIPO_EVENTO']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['FECHA_EVENTO']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['VALOR_DESCUENTO']) . '</td>';
                echo '<td>' . htmlspecialchars($descuento['OBSERVACION']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No se encontraron resultados.';
        }
        ?>
    </div>

</body>
</html>
