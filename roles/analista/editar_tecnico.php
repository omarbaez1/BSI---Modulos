<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Técnico</title>
    <link rel="stylesheet" href="../css/estilos_material.css">
</head>
<body>

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

    // Obtener la cédula del técnico a editar
    $cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';

    // Función para obtener los datos del técnico
    function obtener_tecnico($cedula) {
        $pdo = conexion();
        $sql = "SELECT * FROM `tecnicos` WHERE `CEDULA_TEC` = :cedula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener datos del técnico
    $tecnico = obtener_tecnico($cedula);

    // Si no se encuentra el técnico, redirigir o mostrar mensaje
    if (!$tecnico) {
        echo '<p>Técnico no encontrado.</p>';
        exit();
    }

    // Procesar el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombreCompleto = $_POST['nombreCompleto'];
        $idSap = $_POST['idSap'];
        $cargo = $_POST['cargo'];
        $fechaIngreso = $_POST['fechaIngreso'];
        $estadoEmpresa = $_POST['estadoEmpresa'];
        $foto = $_POST['foto'];

        $pdo = conexion();
        $sql = "UPDATE `tecnicos` SET 
                `NOMBRE_COMPLETO` = :nombreCompleto,
                `ID_SAP` = :idSap,
                `CARGO` = :cargo,
                `FECHA_INGRESO` = :fechaIngreso,
                `ESTADO_EN_LA_EMPRESA` = :estadoEmpresa,
                `FOTO` = :foto
                WHERE `CEDULA_TEC` = :cedula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombreCompleto', $nombreCompleto, PDO::PARAM_STR);
        $stmt->bindParam(':idSap', $idSap, PDO::PARAM_STR);
        $stmt->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $stmt->bindParam(':fechaIngreso', $fechaIngreso, PDO::PARAM_STR);
        $stmt->bindParam(':estadoEmpresa', $estadoEmpresa, PDO::PARAM_STR);
        $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo '<p>Técnico actualizado exitosamente.</p>';
            echo '<a href="tecnicos.php">Volver a la búsqueda</a>';
            exit();
        } else {
            echo '<p>Error al actualizar el técnico.</p>';
        }
    }
    ?>

    <h2>Editar Técnico</h2>

    <!-- Formulario de Edición -->
    <form method="POST" action="">
        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo htmlspecialchars($tecnico['CEDULA_TEC']); ?>" readonly><br><br>

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo htmlspecialchars($tecnico['NOMBRE_COMPLETO']); ?>" required><br><br>

        <label for="idSap">ID SAP:</label>
        <input type="text" name="idSap" id="idSap" value="<?php echo htmlspecialchars($tecnico['ID_SAP']); ?>" required><br><br>

        <label for="cargo">Cargo:</label>
        <input type="text" name="cargo" id="cargo" value="<?php echo htmlspecialchars($tecnico['CARGO']); ?>" required><br><br>

        <label for="fechaIngreso">Fecha Ingreso:</label>
        <input type="date" name="fechaIngreso" id="fechaIngreso" value="<?php echo htmlspecialchars($tecnico['FECHA_INGRESO']); ?>" required><br><br>

        <label for="estadoEmpresa">Estado en la Empresa:</label>
        <select name="estadoEmpresa" id="estadoEmpresa" required>
            <option value="activo" <?php echo ($tecnico['ESTADO_EN_LA_EMPRESA'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
            <option value="inactivo" <?php echo ($tecnico['ESTADO_EN_LA_EMPRESA'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            <option value="suspendido" <?php echo ($tecnico['ESTADO_EN_LA_EMPRESA'] == 'suspendido') ? 'selected' : ''; ?>>Suspendido</option>
            <option value="retirado" <?php echo ($tecnico['ESTADO_EN_LA_EMPRESA'] == 'retirado') ? 'selected' : ''; ?>>Retirado</option>
        </select><br><br>

        <label for="foto">Foto (URL):</label>
        <input type="text" name="foto" id="foto" value="<?php echo htmlspecialchars($tecnico['FOTO']); ?>"><br><br>

        <input type="submit" value="Actualizar Técnico">
    </form>

    <a href="tecnicos.php">Volver a la búsqueda</a>

</body>
</html>
