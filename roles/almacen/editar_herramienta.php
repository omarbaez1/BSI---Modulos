<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Herramienta</title>
    <link rel="stylesheet" href="../css/estilos_herramienta.css">
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

    // Obtener el código de la herramienta desde la URL
    $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;

    if ($codHerramienta) {
        $pdo = conexion();
        $stmt = $pdo->prepare("SELECT * FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->execute();
        $herramienta = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Actualizar herramienta
        $codHerramienta = $_POST['codHerramienta'];
        $herramientaNombre = $_POST['herramienta'];
        $buenoEstado = $_POST['buenoEstado'];
        $estadoRegular = $_POST['estadoRegular'];
        $malEstado = $_POST['malEstado'];
        $totalAlmacen = $_POST['totalAlmacen'];
        $cantidadAsignada = $_POST['cantidadAsignada'];
        $existenciaBsi = $_POST['existenciaBsi'];

        $pdo = conexion();
        $stmt = $pdo->prepare("UPDATE `herramienta` SET `HERRAMIENTA` = :herramienta, `BUEN_ESTADO` = :buenoEstado, `ESTADO_REGULAR` = :estadoRegular, `MAL_ESTADO` = :malEstado, `TOTAL_ALMACEN` = :totalAlmacen, `CANTIDAD_ASIGNADA` = :cantidadAsignada, `EXISTENCIA_BSI` = :existenciaBsi WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->bindParam(':herramienta', $herramientaNombre);
        $stmt->bindParam(':buenoEstado', $buenoEstado);
        $stmt->bindParam(':estadoRegular', $estadoRegular);
        $stmt->bindParam(':malEstado', $malEstado);
        $stmt->bindParam(':totalAlmacen', $totalAlmacen);
        $stmt->bindParam(':cantidadAsignada', $cantidadAsignada);
        $stmt->bindParam(':existenciaBsi', $existenciaBsi);
        $stmt->execute();

        header("Location: herramienta.php");
        exit();
    }
    ?>

    <div class="container">
        <h2>Editar Herramienta</h2>

        <?php if ($herramienta): ?>
            <form method="POST" action="">
                <input type="hidden" name="codHerramienta" value="<?php echo htmlspecialchars($herramienta['COD_HERRAMIENTA']); ?>">

                <label for="herramienta">Nombre de la Herramienta:</label>
                <input type="text" name="herramienta" id="herramienta" value="<?php echo htmlspecialchars($herramienta['HERRAMIENTA']); ?>" required><br><br>

                <label for="buenoEstado">Buen Estado:</label>
                <input type="number" name="buenoEstado" id="buenoEstado" value="<?php echo htmlspecialchars($herramienta['BUEN_ESTADO']); ?>" required><br><br>

                <label for="estadoRegular">Estado Regular:</label>
                <input type="number" name="estadoRegular" id="estadoRegular" value="<?php echo htmlspecialchars($herramienta['ESTADO_REGULAR']); ?>" required><br><br>

                <label for="malEstado">Mal Estado:</label>
                <input type="number" name="malEstado" id="malEstado" value="<?php echo htmlspecialchars($herramienta['MAL_ESTADO']); ?>" required><br><br>

                <label for="totalAlmacen">Total Almacén:</label>
                <input type="number" name="totalAlmacen" id="totalAlmacen" value="<?php echo htmlspecialchars($herramienta['TOTAL_ALMACEN']); ?>" required><br><br>

                <label for="cantidadAsignada">Cantidad Asignada:</label>
                <input type="number" name="cantidadAsignada" id="cantidadAsignada" value="<?php echo htmlspecialchars($herramienta['CANTIDAD_ASIGNADA']); ?>" required><br><br>

                <label for="existenciaBsi">Existencia BSI:</label>
                <input type="number" name="existenciaBsi" id="existenciaBsi" value="<?php echo htmlspecialchars($herramienta['EXISTENCIA_BSI']); ?>" required><br><br>

                <input type="submit" value="Actualizar Herramienta">
            </form>
        <?php else: ?>
            <p>No se encontró la herramienta.</p>
        <?php endif; ?>
    </div>

</body>
</html>
