<?php
// Conexión a la base de datos
function conexion_administrador() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        die();
    }
}

$pdo = conexion_administrador();

// Inicializa la variable
$editDescuento = [];

// Obtener un registro para editar
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM descuentos_herramientas WHERE ID_DESCUENTO = :ID_DESCUENTO";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ID_DESCUENTO' => $_GET['id']]);
    $editDescuento = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Editar un registro
if (isset($_POST['edit'])) {
    // Verifica si el código de herramienta existe en la tabla `herramienta`
    $codHerramienta = $_POST['COD_HERRAMIENTA'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM herramienta WHERE COD_HERRAMIENTA = :COD_HERRAMIENTA");
    $stmt->execute([':COD_HERRAMIENTA' => $codHerramienta]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        die('El código de herramienta no existe.');
    }

    $sql = "UPDATE descuentos_herramientas SET COD_HERRAMIENTA = :COD_HERRAMIENTA, CEDULA_TEC = :CEDULA_TEC, NOMBRE_COMPLETO = :NOMBRE_COMPLETO, TIPO_EVENTO = :TIPO_EVENTO, FECHA_EVENTO = :FECHA_EVENTO, VALOR_DESCUENTO = :VALOR_DESCUENTO, OBSERVACION = :OBSERVACION, HERRAMIENTA = :HERRAMIENTA, ID_USUARIO = :ID_USUARIO WHERE ID_DESCUENTO = :ID_DESCUENTO";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':COD_HERRAMIENTA' => $_POST['COD_HERRAMIENTA'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':NOMBRE_COMPLETO' => $_POST['NOMBRE_COMPLETO'],
        ':TIPO_EVENTO' => $_POST['TIPO_EVENTO'],
        ':FECHA_EVENTO' => $_POST['FECHA_EVENTO'],
        ':VALOR_DESCUENTO' => $_POST['VALOR_DESCUENTO'],
        ':OBSERVACION' => $_POST['OBSERVACION'],
        ':HERRAMIENTA' => $_POST['HERRAMIENTA'],
        ':ID_USUARIO' => $_POST['ID_USUARIO'],
        ':ID_DESCUENTO' => $_POST['ID_DESCUENTO']
    ]);
    header('Location: descuento_herramientas.php'); // Redirige a la lista de descuentos
    exit();
}

// Añadir un nuevo registro
if (isset($_POST['add'])) {
    // Verifica si el código de herramienta existe en la tabla `herramienta`
    $codHerramienta = $_POST['COD_HERRAMIENTA'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM herramienta WHERE COD_HERRAMIENTA = :COD_HERRAMIENTA");
    $stmt->execute([':COD_HERRAMIENTA' => $codHerramienta]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        die('El código de herramienta no existe.');
    }

    $sql = "INSERT INTO descuentos_herramientas (COD_HERRAMIENTA, CEDULA_TEC, NOMBRE_COMPLETO, TIPO_EVENTO, FECHA_EVENTO, VALOR_DESCUENTO, OBSERVACION, HERRAMIENTA, ID_USUARIO) VALUES (:COD_HERRAMIENTA, :CEDULA_TEC, :NOMBRE_COMPLETO, :TIPO_EVENTO, :FECHA_EVENTO, :VALOR_DESCUENTO, :OBSERVACION, :HERRAMIENTA, :ID_USUARIO)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':COD_HERRAMIENTA' => $_POST['COD_HERRAMIENTA'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':NOMBRE_COMPLETO' => $_POST['NOMBRE_COMPLETO'],
        ':TIPO_EVENTO' => $_POST['TIPO_EVENTO'],
        ':FECHA_EVENTO' => $_POST['FECHA_EVENTO'],
        ':VALOR_DESCUENTO' => $_POST['VALOR_DESCUENTO'],
        ':OBSERVACION' => $_POST['OBSERVACION'],
        ':HERRAMIENTA' => $_POST['HERRAMIENTA'],
        ':ID_USUARIO' => $_POST['ID_USUARIO']
    ]);
    header('Location: descuento_herramientas.php'); // Redirige a la lista de descuentos
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($editDescuento['ID_DESCUENTO']) ? 'Editar' : 'Añadir'; ?> Descuento de Herramienta</title>
    <link rel="stylesheet" href="css/estilos_materiales.css">
</head>
<body>
    <h1><?php echo isset($editDescuento['ID_DESCUENTO']) ? 'Editar' : 'Añadir'; ?> Descuento de Herramienta</h1>
    <form method="post" action="">
        <input type="hidden" name="ID_DESCUENTO" value="<?php echo htmlspecialchars($editDescuento['ID_DESCUENTO'] ?? ''); ?>">
        <label>Código de Herramienta:</label><br>
        <input type="text" name="COD_HERRAMIENTA" value="<?php echo htmlspecialchars($editDescuento['COD_HERRAMIENTA'] ?? ''); ?>" required><br>
        <label>Cédula Técnico:</label><br>
        <input type="text" name="CEDULA_TEC" value="<?php echo htmlspecialchars($editDescuento['CEDULA_TEC'] ?? ''); ?>" required><br>
        <label>Nombre Completo:</label><br>
        <input type="text" name="NOMBRE_COMPLETO" value="<?php echo htmlspecialchars($editDescuento['NOMBRE_COMPLETO'] ?? ''); ?>" required><br>
        <label>Tipo Evento:</label><br>
        <input type="text" name="TIPO_EVENTO" value="<?php echo htmlspecialchars($editDescuento['TIPO_EVENTO'] ?? ''); ?>" required><br>
        <label>Fecha Evento:</label><br>
        <input type="date" name="FECHA_EVENTO" value="<?php echo htmlspecialchars($editDescuento['FECHA_EVENTO'] ?? ''); ?>" required><br>
        <label>Valor Descuento:</label><br>
        <input type="number" name="VALOR_DESCUENTO" value="<?php echo htmlspecialchars($editDescuento['VALOR_DESCUENTO'] ?? ''); ?>" step="0.01" required><br>
        <label>Observación:</label><br>
        <textarea name="OBSERVACION" required><?php echo htmlspecialchars($editDescuento['OBSERVACION'] ?? ''); ?></textarea><br>
        <label>Herramienta:</label><br>
        <input type="text" name="HERRAMIENTA" value="<?php echo htmlspecialchars($editDescuento['HERRAMIENTA'] ?? ''); ?>" required><br>
        <label>ID Usuario:</label><br>
        <input type="text" name="ID_USUARIO" value="<?php echo htmlspecialchars($editDescuento['ID_USUARIO'] ?? ''); ?>" required><br>
        <button type="submit" name="<?php echo isset($editDescuento['ID_DESCUENTO']) ? 'edit' : 'add'; ?>"><?php echo isset($editDescuento['ID_DESCUENTO']) ? 'Guardar Cambios' : 'Añadir Descuento'; ?></button>
    </form>
</body>
</html>
