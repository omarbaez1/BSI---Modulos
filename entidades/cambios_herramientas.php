<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Cambios de Herramientas</title>
    <link rel="stylesheet" href="estilos_cambiasherramientas.css"> <!-- Enlace a la hoja de estilos externa -->
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

    <h2>Buscar Cambios de Herramientas</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codHerramienta">Código de Herramienta:</label>
        <input type="text" name="codHerramienta" id="codHerramienta">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec">

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto">

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
        <?php
        // Conexión a la base de datos
        function conexion()
        {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error de conexión: ' . $e->getMessage();
                die();
            }
        }

        // Función para buscar cambios de herramienta por código, cédula y nombre
        function buscar_cambio($codHerramienta = null, $cedulaTec = null, $nombreCompleto = null)
        {
            $pdo = conexion();

            // Construir la consulta SQL con filtros opcionales
            $sql = "SELECT * FROM cambios_herramientas WHERE 1=1";

            if (!empty($codHerramienta)) {
                $sql .= " AND LOWER(COD_HERRAMIENTA) LIKE LOWER(:codHerramienta)";
            }

            if (!empty($cedulaTec)) {
                $sql .= " AND LOWER(CEDULA_TEC) LIKE LOWER(:cedulaTec)";
            }

            if (!empty($nombreCompleto)) {
                $sql .= " AND LOWER(NOMBRE_COMPLETO) LIKE LOWER(:nombreCompleto)";
            }

            $stmt = $pdo->prepare($sql);

            // Vincular los parámetros si existen
            if (!empty($codHerramienta)) {
                $stmt->bindValue(':codHerramienta', '%' . $codHerramienta . '%');
            }

            if (!empty($cedulaTec)) {
                $stmt->bindValue(':cedulaTec', '%' . $cedulaTec . '%');
            }

            if (!empty($nombreCompleto)) {
                $stmt->bindValue(':nombreCompleto', '%' . $nombreCompleto . '%');
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Obtener valores del formulario
        $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
        $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
        $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;

        // Buscar y mostrar los resultados
        $cambios = buscar_cambio($codHerramienta, $cedulaTec, $nombreCompleto);

        if (!empty($cambios)) {
            echo '<div class="table-container">';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID Cambio</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>Código de Herramienta</th>';
            echo '<th>ID Usuario</th>';
            echo '<th>Tipo de Cambio</th>';
            echo '<th>Fecha de Cambio</th>';
            echo '<th>Observación</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($cambios as $cambio) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($cambio['ID_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['COD_HERRAMIENTA']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['ID_USUARIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['TIPO_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['FECHA_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['OBSERVACION']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo 'No se encontraron resultados.';
        }
        ?>
    </div>

</body>

</html>
