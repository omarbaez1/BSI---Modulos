<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Material</title>
    <link rel="stylesheet" href="../css/estilos_materiales.css">
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

    // Manejar la actualización del material
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $codMaterialOriginal = isset($_POST['codMaterialOriginal']) ? $_POST['codMaterialOriginal'] : null;
        $codMaterial = isset($_POST['codMaterial']) ? $_POST['codMaterial'] : null;
        $nombreMaterial = isset($_POST['nombreMaterial']) ? $_POST['nombreMaterial'] : null;
        $consecutivoInicial = isset($_POST['consecutivoInicial']) ? $_POST['consecutivoInicial'] : null;
        $consecutivoFinal = isset($_POST['consecutivoFinal']) ? $_POST['consecutivoFinal'] : null;
        $cedulaTec = isset($_POST['cedulaTec']) ? $_POST['cedulaTec'] : null;
        $nombreCompleto = isset($_POST['nombreCompleto']) ? $_POST['nombreCompleto'] : null;
        $total = isset($_POST['total']) ? $_POST['total'] : null;
        $observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

        if ($codMaterial && $codMaterialOriginal) {
            $pdo = conexion();

            // Actualizar el material en la base de datos
            $stmt = $pdo->prepare("UPDATE material SET 
                COD_MATERIAL = :codMaterial,
                NOMBRE_MATERIAL = :nombreMaterial,
                CONSECUTIVO_INICIAL = :consecutivoInicial,
                CONSECUTIVO_FINAL = :consecutivoFinal,
                CEDULA_TEC = :cedulaTec,
                NOMBRE_COMPLETO = :nombreCompleto,
                TOTAL = :total,
                OBSERVACION = :observacion
                WHERE COD_MATERIAL = :codMaterialOriginal");

            $stmt->bindParam(':codMaterial', $codMaterial);
            $stmt->bindParam(':nombreMaterial', $nombreMaterial);
            $stmt->bindParam(':consecutivoInicial', $consecutivoInicial);
            $stmt->bindParam(':consecutivoFinal', $consecutivoFinal);
            $stmt->bindParam(':cedulaTec', $cedulaTec);
            $stmt->bindParam(':nombreCompleto', $nombreCompleto);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':observacion', $observacion);
            $stmt->bindParam(':codMaterialOriginal', $codMaterialOriginal);

            try {
                $stmt->execute();
                echo '<p>Material actualizado correctamente.</p>';
            } catch (PDOException $e) {
                echo 'Error al actualizar el material: ' . $e->getMessage();
            }
        } else {
            echo '<p>El código de material no está disponible para la actualización.</p>';
        }
    } else {
        // Obtener el código del material desde la URL
        $codMaterial = isset($_GET['codMaterial']) ? $_GET['codMaterial'] : null;

        if ($codMaterial) {
            $pdo = conexion();
            $stmt = $pdo->prepare("SELECT * FROM material WHERE COD_MATERIAL = :codMaterial");
            $stmt->bindParam(':codMaterial', $codMaterial);
            $stmt->execute();
            $material = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$material) {
                echo '<p>Material no encontrado.</p>';
                exit;
            }
        } else {
            echo '<p>Código de material no proporcionado.</p>';
            exit;
        }
    }
    ?>

    <h2>Editar Material</h2>
    <form method="POST" action="">
        <input type="hidden" name="codMaterialOriginal" value="<?php echo htmlspecialchars($material['COD_MATERIAL']); ?>">

        <label for="codMaterial">Código de Material:</label>
        <input type="text" name="codMaterial" id="codMaterial" value="<?php echo htmlspecialchars($material['COD_MATERIAL']); ?>" required><br><br>

        <label for="nombreMaterial">Nombre del Material:</label>
        <input type="text" name="nombreMaterial" id="nombreMaterial" value="<?php echo htmlspecialchars($material['NOMBRE_MATERIAL']); ?>" required><br><br>

        <label for="consecutivoInicial">Consecutivo Inicial:</label>
        <input type="text" name="consecutivoInicial" id="consecutivoInicial" value="<?php echo htmlspecialchars($material['CONSECUTIVO_INICIAL']); ?>" required><br><br>

        <label for="consecutivoFinal">Consecutivo Final:</label>
        <input type="text" name="consecutivoFinal" id="consecutivoFinal" value="<?php echo htmlspecialchars($material['CONSECUTIVO_FINAL']); ?>" required><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($material['CEDULA_TEC']); ?>" required><br><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($material['NOMBRE_COMPLETO']); ?>" required><br><br>

        <label for="total">Total:</label>
        <input type="number" name="total" id="total" value="<?php echo htmlspecialchars($material['TOTAL']); ?>" required><br><br>

        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion" rows="4"><?php echo htmlspecialchars($material['OBSERVACION']); ?></textarea><br><br>

        <input type="submit" value="Actualizar Material">
    </form>

</body>
</html>
