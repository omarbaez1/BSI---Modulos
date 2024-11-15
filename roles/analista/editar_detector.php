<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Detector</title>
    <link rel="stylesheet" href="../css/estilos_material.css">
    <link rel="stylesheet" href="../css/estilos_detectores.css">
</head>
<body>

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

    // Obtener el serial del detector a editar
    $serial = isset($_GET['SERIAL']) ? $_GET['SERIAL'] : '';

    // Verificar si el serial está definido
    if ($serial) {
        $pdo = conexion();
        
        // Consultar el detector por serial
        $stmt = $pdo->prepare("SELECT * FROM detectores WHERE SERIAL = :serial");
        $stmt->bindParam(':serial', $serial);
        $stmt->execute();
        $detector = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar si se encontró el detector
        if (!$detector) {
            echo '<p>Detector no encontrado.</p>';
            exit;
        }
    }

    // Actualizar el detector
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $serial = $_POST['serial'];
        $cedulaTec = $_POST['cedulaTec'];
        $fechaLlegada = $_POST['fechaLlegada'];
        $procedencia = $_POST['procedencia'];
        $valor = $_POST['valor'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $estuche = $_POST['estuche'];
        $fechaCalibracion = $_POST['fechaCalibracion'];
        $fechaProxCalibracion = $_POST['fechaProxCalibracion'];
        $diasVenCalib = $_POST['diasVenCalib'];
        $estadoCalibracion = $_POST['estadoCalibracion'];
        $quienLoTenia = $_POST['quienLoTenia'];
        $ubiActEqui = $_POST['ubiActEqui'];
        $tecAsig = $_POST['tecAsig'];
        $estadoEquipo = $_POST['estadoEquipo'];
        $fechaEstado = $_POST['fechaEstado'];
        $observacion = $_POST['observacion'];
        $observacionProsind = $_POST['observacionProsind'];
        $observacionPerdidos = $_POST['observacionPerdidos'];
        $fechaUltValid = $_POST['fechaUltValid'];
        
        // Actualizar el detector en la base de datos
        $pdo = conexion();
        $sql = "UPDATE detectores SET
                    CEDULA_TEC = :cedulaTec,
                    FECHA_LLEGADA = :fechaLlegada,
                    PROCEDENCIA = :procedencia,
                    VALOR = :valor,
                    MARCA = :marca,
                    MODELO = :modelo,
                    ESTUCHE = :estuche,
                    FECHA_CALIBRACION = :fechaCalibracion,
                    FECHA_PROX_CALIBRACION = :fechaProxCalibracion,
                    DIAS_VEN_CALIB = :diasVenCalib,
                    ESTADO_CALIBRACION = :estadoCalibracion,
                    QUIEN_LO_TENIA = :quienLoTenia,
                    UBI_ACT_EQUI = :ubiActEqui,
                    TEC_ASIG = :tecAsig,
                    ESTADO_EQUIPO = :estadoEquipo,
                    FECHA_ESTADO = :fechaEstado,
                    OBSERVACION = :observacion,
                    OBSERVACION_PROSOIND = :observacionProsind,
                    OBSERVACION_PERDIDOS = :observacionPerdidos,
                    FECHA_ULT_VALID = :fechaUltValid
                WHERE SERIAL = :serial";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':serial', $serial);
        $stmt->bindParam(':cedulaTec', $cedulaTec);
        $stmt->bindParam(':fechaLlegada', $fechaLlegada);
        $stmt->bindParam(':procedencia', $procedencia);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':estuche', $estuche);
        $stmt->bindParam(':fechaCalibracion', $fechaCalibracion);
        $stmt->bindParam(':fechaProxCalibracion', $fechaProxCalibracion);
        $stmt->bindParam(':diasVenCalib', $diasVenCalib);
        $stmt->bindParam(':estadoCalibracion', $estadoCalibracion);
        $stmt->bindParam(':quienLoTenia', $quienLoTenia);
        $stmt->bindParam(':ubiActEqui', $ubiActEqui);
        $stmt->bindParam(':tecAsig', $tecAsig);
        $stmt->bindParam(':estadoEquipo', $estadoEquipo);
        $stmt->bindParam(':fechaEstado', $fechaEstado);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->bindParam(':observacionProsind', $observacionProsind);
        $stmt->bindParam(':observacionPerdidos', $observacionPerdidos);
        $stmt->bindParam(':fechaUltValid', $fechaUltValid);

        if ($stmt->execute()) {
            echo '<p>Detector actualizado con éxito.</p>';
            // Redirigir después de la actualización
            header("Location: detectores.php");
            exit;
        } else {
            echo '<p>Error al actualizar el detector.</p>';
        }
    }
    ?>

    <h2>Editar Detector</h2>
    <form method="post" action="">
        <input type="hidden" name="serial" value="<?php echo htmlspecialchars($detector['SERIAL']); ?>">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($detector['CEDULA_TEC']); ?>" required><br><br>

        <label for="fechaLlegada">Fecha de Llegada:</label>
        <input type="date" name="fechaLlegada" id="fechaLlegada" value="<?php echo htmlspecialchars($detector['FECHA_LLEGADA']); ?>" required><br><br>

        <label for="procedencia">Procedencia:</label>
        <input type="text" name="procedencia" id="procedencia" value="<?php echo htmlspecialchars($detector['PROCEDENCIA']); ?>" required><br><br>

        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" value="<?php echo htmlspecialchars($detector['VALOR']); ?>" required><br><br>

        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" value="<?php echo htmlspecialchars($detector['MARCA']); ?>" required><br><br>

        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo" id="modelo" value="<?php echo htmlspecialchars($detector['MODELO']); ?>" required><br><br>

        <label for="estuche">Estuche:</label>
        <input type="text" name="estuche" id="estuche" value="<?php echo htmlspecialchars($detector['ESTUCHE']); ?>"><br><br>

        <label for="fechaCalibracion">Fecha de Calibración:</label>
        <input type="date" name="fechaCalibracion" id="fechaCalibracion" value="<?php echo htmlspecialchars($detector['FECHA_CALIBRACION']); ?>"><br><br>

        <label for="fechaProxCalibracion">Fecha Próxima Calibración:</label>
        <input type="date" name="fechaProxCalibracion" id="fechaProxCalibracion" value="<?php echo htmlspecialchars($detector['FECHA_PROX_CALIBRACION']); ?>"><br><br>

        <label for="diasVenCalib">Días para Calibración:</label>
        <input type="number" name="diasVenCalib" id="diasVenCalib" value="<?php echo htmlspecialchars($detector['DIAS_VEN_CALIB']); ?>" readonly><br><br>

        <label for="estadoCalibracion">Estado de Calibración:</label>
        <input type="text" name="estadoCalibracion" id="estadoCalibracion" value="<?php echo htmlspecialchars($detector['ESTADO_CALIBRACION']); ?>" readonly><br><br>

        <label for="quienLoTenia">Quién lo Tenía:</label>
        <input type="text" name="quienLoTenia" id="quienLoTenia" value="<?php echo htmlspecialchars($detector['QUIEN_LO_TENIA']); ?>"><br><br>

        <label for="ubiActEqui">Ubicación Actual:</label>
        <input type="text" name="ubiActEqui" id="ubiActEqui" value="<?php echo htmlspecialchars($detector['UBI_ACT_EQUI']); ?>"><br><br>

        <label for="tecAsig">Técnico Asignado:</label>
        <input type="text" name="tecAsig" id="tecAsig" value="<?php echo htmlspecialchars($detector['TEC_ASIG']); ?>"><br><br>

        <label for="estadoEquipo">Estado del Equipo:</label>
        <select name="estadoEquipo" id="estadoEquipo" required>
            <option value="Asignado" <?php if ($detector['ESTADO_EQUIPO'] === 'Asignado') echo 'selected'; ?>>Asignado</option>
            <option value="Dar de baja" <?php if ($detector['ESTADO_EQUIPO'] === 'Dar de baja') echo 'selected'; ?>>Dar de baja</option>
            <option value="Disponible" <?php if ($detector['ESTADO_EQUIPO'] === 'Disponible') echo 'selected'; ?>>Disponible</option>
            <option value="Equipo de baja" <?php if ($detector['ESTADO_EQUIPO'] === 'Equipo de baja') echo 'selected'; ?>>Equipo de baja</option>
            <option value="Extraviado" <?php if ($detector['ESTADO_EQUIPO'] === 'Extraviado') echo 'selected'; ?>>Extraviado</option>
            <option value="No se sabe" <?php if ($detector['ESTADO_EQUIPO'] === 'No se sabe') echo 'selected'; ?>>No se sabe</option>
            <option value="Para mantenimiento" <?php if ($detector['ESTADO_EQUIPO'] === 'Para mantenimiento') echo 'selected'; ?>>Para mantenimiento</option>
            <option value="Perdido" <?php if ($detector['ESTADO_EQUIPO'] === 'Perdido') echo 'selected'; ?>>Perdido</option>
            <option value="Prospecto" <?php if ($detector['ESTADO_EQUIPO'] === 'Prospecto') echo 'selected'; ?>>Prospecto</option>
        </select><br><br>

        <label for="fechaEstado">Fecha de Estado:</label>
        <input type="date" name="fechaEstado" id="fechaEstado" value="<?php echo htmlspecialchars($detector['FECHA_ESTADO']); ?>" required><br><br>

        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion"><?php echo htmlspecialchars($detector['OBSERVACION']); ?></textarea><br><br>

        <label for="observacionProsind">Observación Prosind:</label>
        <textarea name="observacionProsind" id="observacionProsind"><?php echo htmlspecialchars($detector['OBSERVACION_PROSOIND']); ?></textarea><br><br>

        <label for="observacionPerdidos">Observación Perdidos:</label>
        <textarea name="observacionPerdidos" id="observacionPerdidos"><?php echo htmlspecialchars($detector['OBSERVACION_PERDIDOS']); ?></textarea><br><br>

        <label for="fechaUltValid">Fecha Última Validación:</label>
        <input type="date" name="fechaUltValid" id="fechaUltValid" value="<?php echo htmlspecialchars($detector['FECHA_ULT_VALID']); ?>"><br><br>

        <input type="submit" value="Actualizar Detector">
    </form>

</body>
</html>
