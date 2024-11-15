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

$mensaje = ''; // Variable para almacenar el mensaje de notificación

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de actualización
    $codLlave = $_POST['codLlave'];
    $cedulaTec = $_POST['cedulaTec'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $idSap = $_POST['idSap'];
    $estado = $_POST['estado'];
    $fechaEstado = $_POST['fechaEstado'];
    $observacion = $_POST['observacion'];
    $ubiActLlave = $_POST['ubiActLlave'];
    $fechaAsignacion = $_POST['fechaAsignacion'];
    $segundaObservacion = $_POST['segundaObservacion'];

    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("
            UPDATE llaves_de_cepo
            SET CEDULA_TEC = :cedulaTec,
                NOMBRE_COMPLETO = :nombreCompleto,
                ID_SAP = :idSap,
                ESTADO_LLAVE = :estado,
                FECHA_ESTADO = :fechaEstado,
                OBSERVACION = :observacion,
                UBI_ACT_LLAVE = :ubiActLlave,
                FECHA_ASIGNACION = :fechaAsignacion,
                2_OBSERVACION = :segundaObservacion
            WHERE COD_LLAVE = :codLlave
        ");
        $stmt->bindParam(':cedulaTec', $cedulaTec);
        $stmt->bindParam(':nombreCompleto', $nombreCompleto);
        $stmt->bindParam(':idSap', $idSap);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fechaEstado', $fechaEstado);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->bindParam(':ubiActLlave', $ubiActLlave);
        $stmt->bindParam(':fechaAsignacion', $fechaAsignacion);
        $stmt->bindParam(':segundaObservacion', $segundaObservacion);
        $stmt->bindParam(':codLlave', $codLlave);
        
        $stmt->execute();
        $mensaje = '<p>Llave de Cepo actualizada con éxito.</p>';
    } catch (PDOException $e) {
        $mensaje = '<p>Error al actualizar la llave: ' . $e->getMessage() . '</p>';
    }
}

// Obtener datos de la llave de cepo para editar
if (isset($_GET['codLlave'])) {
    $codLlave = $_GET['codLlave'];

    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("SELECT * FROM llaves_de_cepo WHERE COD_LLAVE = :codLlave");
        $stmt->bindParam(':codLlave', $codLlave);
        $stmt->execute();
        $llave = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($llave) {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Editar Llave de Cepo</title>
                <link rel="stylesheet" href="css/estilos_materiales.css">
            </head>
            <body>

                <div class="container">
                    <h2>Editar Llave de Cepo</h2>

                    <!-- Mostrar mensaje -->
                    <?php if ($mensaje): ?>
                        <div class="mensaje"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <!-- Formulario de edición -->
                    <form method="POST" action="">
                        <input type="hidden" name="codLlave" value="<?php echo htmlspecialchars($llave['COD_LLAVE']); ?>">

                        <div class="form-group">
                            <label for="cedulaTec">Cédula Técnico:</label>
                            <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($llave['CEDULA_TEC']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="nombreCompleto">Nombre Completo:</label>
                            <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($llave['NOMBRE_COMPLETO']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="idSap">ID SAP:</label>
                            <input type="text" name="idSap" id="idSap" value="<?php echo htmlspecialchars($llave['ID_SAP']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select name="estado" id="estado" required>
                                <option value="Asignada" <?php echo ($llave['ESTADO_LLAVE'] === 'Asignada') ? 'selected' : ''; ?>>Asignada</option>
                                <option value="En Mal Estado" <?php echo ($llave['ESTADO_LLAVE'] === 'En Mal Estado') ? 'selected' : ''; ?>>En Mal Estado</option>
                                <option value="Extraviada" <?php echo ($llave['ESTADO_LLAVE'] === 'Extraviada') ? 'selected' : ''; ?>>Extraviada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fechaEstado">Fecha Estado:</label>
                            <input type="date" name="fechaEstado" id="fechaEstado" value="<?php echo htmlspecialchars($llave['FECHA_ESTADO']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="observacion">Observación:</label>
                            <textarea name="observacion" id="observacion"><?php echo htmlspecialchars($llave['OBSERVACION']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="ubiActLlave">Ubicación Actual:</label>
                            <input type="text" name="ubiActLlave" id="ubiActLlave" value="<?php echo htmlspecialchars($llave['UBI_ACT_LLAVE']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="fechaAsignacion">Fecha Asignación:</label>
                            <input type="date" name="fechaAsignacion" id="fechaAsignacion" value="<?php echo htmlspecialchars($llave['FECHA_ASIGNACION']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="segundaObservacion">2ª Observación:</label>
                            <textarea name="segundaObservacion" id="segundaObservacion"><?php echo htmlspecialchars($llave['2_OBSERVACION']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Actualizar Llave de Cepo">
                        </div>
                    </form>

                    <a href="llaves_de_cepo.php">Volver al listado</a>

                </div>

            </body>
            </html>
            <?php
        } else {
            echo '<p>Llave de Cepo no encontrada.</p>';
        }
    } catch (PDOException $e) {
        echo 'Error al obtener la llave: ' . $e->getMessage();
    }
} else {
    echo '<p>Se requiere el código de la llave para editar.</p>';
}
?>
