<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Detectores</title>
    <link rel="stylesheet" href="../css/estilos_detectores.css">
</head>
<body>

    <h2>Buscar Detector</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="serial">Serial:</label>
        <input type="text" name="serial" id="serial"><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="estadoEquipo">Estado del Equipo:</label>
        <select name="estadoEquipo" id="estadoEquipo">
            <option value="">Seleccione un estado</option>
            <option value="Asignado">Asignado</option>
            <option value="Dar de baja">Dar de baja</option>
            <option value="Disponible">Disponible</option>
            <option value="Equipo de baja">Equipo de baja</option>
            <option value="Extraviado">Extraviado</option>
            <option value="No se sabe">No se sabe</option>
            <option value="Para mantenimiento">Para mantenimiento</option>
            <option value="Perdido">Perdido</option>
            <option value="Prospecto">Prospecto</option>
        </select><br><br>

        <label for="tecnicoAsignado">Técnico Asignado:</label>
        <input type="text" name="tecnicoAsignado" id="tecnicoAsignado"><br><br>

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

    // Función para buscar detectores por serial, cédula, estado y técnico asignado
    function buscar_detector($serial = null, $cedulaTec = null, $estadoEquipo = null, $tecnicoAsignado = null){
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM `detectores` WHERE 1=1";
        
        if (!empty($serial)) {
            $sql .= " AND `SERIAL` = :serial";
        }
        
        if (!empty($cedulaTec)) {
            $sql .= " AND `CEDULA_TEC` = :cedulaTec";
        }
        
        if (!empty($estadoEquipo)) {
            $sql .= " AND `ESTADO_EQUIPO` = :estadoEquipo";
        }

        if (!empty($tecnicoAsignado)) {
            // Usar % para la búsqueda parcial en el nombre del técnico
            $sql .= " AND `TEC_ASIG` LIKE :tecnicoAsignado";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($serial)) {
            $stmt->bindParam(':serial', $serial);
        }
        
        if (!empty($cedulaTec)) {
            $stmt->bindParam(':cedulaTec', $cedulaTec);
        }
        
        if (!empty($estadoEquipo)) {
            $stmt->bindParam(':estadoEquipo', $estadoEquipo);
        }
        
        if (!empty($tecnicoAsignado)) {
            // Usar % para la búsqueda parcial en el nombre del técnico
            $tecnicoAsignado = "%$tecnicoAsignado%";
            $stmt->bindParam(':tecnicoAsignado', $tecnicoAsignado);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener valores del formulario
    $serial = isset($_GET['serial']) ? $_GET['serial'] : null;
    $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
    $estadoEquipo = isset($_GET['estadoEquipo']) ? $_GET['estadoEquipo'] : null;
    $tecnicoAsignado = isset($_GET['tecnicoAsignado']) ? $_GET['tecnicoAsignado'] : null;

    // Buscar y mostrar los resultados
    $detectores = buscar_detector($serial, $cedulaTec, $estadoEquipo, $tecnicoAsignado);

    if (!empty($detectores)) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Serial</th>';
        echo '<th>Cédula Técnico</th>';
        echo '<th>Fecha de Llegada</th>';
        echo '<th>Procedencia</th>';
        echo '<th>Valor</th>';
        echo '<th>Marca</th>';
        echo '<th>Modelo</th>';
        echo '<th>Estuche</th>';
        echo '<th>Fecha de Calibración</th>';
        echo '<th>Fecha Próxima Calibración</th>';
        echo '<th>Días para Calibración</th>';
        echo '<th>Estado de Calibración</th>';
        echo '<th>Quién lo Tenía</th>';
        echo '<th>Ubicación Actual</th>';
        echo '<th>Técnico Asignado</th>';
        echo '<th>Estado del Equipo</th>';
        echo '<th>Fecha de Estado</th>';
        echo '<th>Observación</th>';
        echo '<th>Observación Prosind</th>';
        echo '<th>Observación Perdidos</th>';
        echo '<th>Fecha Última Validación</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach($detectores as $det) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($det['SERIAL']) . '</td>';
            echo '<td>' . htmlspecialchars($det['CEDULA_TEC']) . '</td>';
            echo '<td>' . htmlspecialchars($det['FECHA_LLEGADA']) . '</td>';
            echo '<td>' . htmlspecialchars($det['PROCEDENCIA']) . '</td>';
            echo '<td>' . htmlspecialchars($det['VALOR']) . '</td>';
            echo '<td>' . htmlspecialchars($det['MARCA']) . '</td>';
            echo '<td>' . htmlspecialchars($det['MODELO']) . '</td>';
            echo '<td>' . htmlspecialchars($det['ESTUCHE']) . '</td>';
            echo '<td>' . htmlspecialchars($det['FECHA_CALIBRACION']) . '</td>';
            echo '<td>' . htmlspecialchars($det['FECHA_PROX_CALIBRACION']) . '</td>';
            echo '<td>' . htmlspecialchars($det['DIAS_VEN_CALIB']) . '</td>';
            echo '<td>' . htmlspecialchars($det['ESTADO_CALIBRACION']) . '</td>';
            echo '<td>' . htmlspecialchars($det['QUIEN_LO_TENIA']) . '</td>';
            echo '<td>' . htmlspecialchars($det['UBI_ACT_EQUI']) . '</td>';
            echo '<td>' . htmlspecialchars($det['TEC_ASIG']) . '</td>';
            echo '<td>' . htmlspecialchars($det['ESTADO_EQUIPO']) . '</td>';
            echo '<td>' . htmlspecialchars($det['FECHA_ESTADO']) . '</td>';
            echo '<td>' . htmlspecialchars($det['OBSERVACION']) . '</td>';
            echo '<td>' . htmlspecialchars($det['OBSERVACION_PROSOIND']) . '</td>';
            echo '<td>' . htmlspecialchars($det['OBSERVACION_PERDIDOS']) . '</td>';
            echo '<td>' . htmlspecialchars($det['FECHA_ULT_VALID']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No se encontraron resultados.';
    }
    ?>

</body>
</html>
