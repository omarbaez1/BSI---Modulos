<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Material Asignado</title>
    <link rel="stylesheet" href="css/estilos_materiales.css">
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

    // Obtener el código del material desde la URL
    $codMaterial = isset($_GET['codMaterial']) ? $_GET['codMaterial'] : null;

    if ($codMaterial) {
        $pdo = conexion();
        $stmt = $pdo->prepare("SELECT * FROM materiales_asignados WHERE COD_MATERIAL = :codMaterial");
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
    ?>

    <h2>Editar Material Asignado</h2>
    <form method="POST" action="actualizar_material_asignado.php">
        <input type="hidden" name="oldCodMaterial" value="<?php echo htmlspecialchars($material['COD_MATERIAL']); ?>">

        <label for="codMaterial">Código de Material:</label>
        <input type="text" name="codMaterial" id="codMaterial" value="<?php echo htmlspecialchars($material['COD_MATERIAL']); ?>" required><br><br>

        <label for="nombreMaterial">Nombre del Material:</label>
        <input type="text" name="nombreMaterial" id="nombreMaterial" value="<?php echo htmlspecialchars($material['NOMBRE_MATERIAL']); ?>" required><br><br>

        <label for="cantidadAsignada">Cantidad Asignada:</label>
        <input type="number" name="cantidadAsignada" id="cantidadAsignada" value="<?php echo htmlspecialchars($material['CANT_ASIG']); ?>" required><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($material['CEDULA_TEC']); ?>" required><br><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($material['NOMBRE_COMPLETO']); ?>" required><br><br>

        <label for="fechaAsignacion">Fecha Asignación:</label>
        <input type="date" name="fechaAsignacion" id="fechaAsignacion" value="<?php echo htmlspecialchars($material['FECHA_ASIGNACIÓN']); ?>" required><br><br>

        <input type="submit" value="Actualizar Material">
    </form>

</body>
</html>
