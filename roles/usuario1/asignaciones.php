<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Asignaciones</title>
    <link rel="stylesheet" href="../css/estilos_asignaciones.css">
    <style>
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

    <h2>Buscar Asignaciones</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec">

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto">

        <label for="estadoHerramienta">Estado de Herramienta:</label>
        <select name="estadoHerramienta" id="estadoHerramienta">
            <option value="">Seleccione un estado</option>
            <option value="Asignado">Asignado</option>
            <option value="No Asignado">No Asignado</option>
        </select>

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
        <div class="table-container">
        <?php
        function conexion() {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error de conexión: ' . htmlspecialchars($e->getMessage());
                die();
            }
        }

        function buscar_asignacion($cedulaTec = null, $nombreCompleto = null, $estadoHerramienta = null) {
            $pdo = conexion();

            $sql = "SELECT * FROM `asignaciones` WHERE 1=1";
            
            if (!empty($cedulaTec)) {
                $sql .= " AND LOWER(`CEDULA_TEC`) LIKE LOWER(:cedulaTec)";
            }

            if (!empty($nombreCompleto)) {
                $sql .= " AND LOWER(`NOMBRE_COMPLETO`) LIKE LOWER(:nombreCompleto)";
            }

            if (!empty($estadoHerramienta)) {
                $sql .= " AND LOWER(`ESTADO_HERRAMIENTA`) LIKE LOWER(:estadoHerramienta)";
            }

            $stmt = $pdo->prepare($sql);
            
            if (!empty($cedulaTec)) {
                $stmt->bindValue(':cedulaTec', '%' . strtolower($cedulaTec) . '%');
            }

            if (!empty($nombreCompleto)) {
                $stmt->bindValue(':nombreCompleto', '%' . strtolower($nombreCompleto) . '%');
            }

            if (!empty($estadoHerramienta)) {
                $stmt->bindValue(':estadoHerramienta', '%' . strtolower($estadoHerramienta) . '%');
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
        $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;
        $estadoHerramienta = isset($_GET['estadoHerramienta']) ? $_GET['estadoHerramienta'] : null;

        $asignaciones = buscar_asignacion($cedulaTec, $nombreCompleto, $estadoHerramienta);

        if (!empty($asignaciones)) {
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>ID Asignación</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>ID Usuario</th>';
            echo '<th>Fecha de Asignación</th>';
            echo '<th>Tipo de Asignación</th>';
            echo '<th>ID Item Asignado</th>';
            echo '<th>Cantidad Asignada</th>';
            echo '<th>Estado Herramienta</th>';
            echo '<th>Observación</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            foreach($asignaciones as $asignacion) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($asignacion['ID_ASIGNACION']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['ID_USUARIO']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['FECHA_ASIGNACION']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['TIPO_ASIGNACION']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['ID_ITEM_ASIGNADO']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['CANTIDAD_ASIGNADA']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['ESTADO_HERRAMIENTA']) . '</td>';
                echo '<td>' . htmlspecialchars($asignacion['OBSERVACION']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No se encontraron resultados.';
        }
        ?>
        </div>
    </div>

</body>
</html>
