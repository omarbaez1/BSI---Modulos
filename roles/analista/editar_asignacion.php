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

// Obtener el ID de la asignación desde la URL
if (isset($_GET['ID_ASIGNACION'])) {
    $ID_ASIGNACION = $_GET['ID_ASIGNACION'];

    // Obtener los datos actuales del registro
    $sql = "SELECT * FROM asignaciones WHERE ID_ASIGNACION = :ID_ASIGNACION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ID_ASIGNACION' => $ID_ASIGNACION]);
    $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si el registro no existe, redirigir a la página principal
    if (!$asignacion) {
        header('Location: asignaciones.php');
        exit();
    }
} else {
    header('Location: asignaciones.php');
    exit();
}

// Procesar la actualización del registro
if (isset($_POST['update'])) {
    $sql = "UPDATE asignaciones SET CEDULA_TEC = :CEDULA_TEC, ID_USUARIO = :ID_USUARIO, FECHA_ASIGNACION = :FECHA_ASIGNACION, TIPO_ASIGNACION = :TIPO_ASIGNACION, ID_ITEM_ASIGNADO = :ID_ITEM_ASIGNADO, CANTIDAD_ASIGNADA = :CANTIDAD_ASIGNADA, ESTADO_HERRAMIENTA = :ESTADO_HERRAMIENTA, OBSERVACION = :OBSERVACION WHERE ID_ASIGNACION = :ID_ASIGNACION";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':ID_ASIGNACION' => $_POST['ID_ASIGNACION'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':ID_USUARIO' => $_POST['ID_USUARIO'],
        ':FECHA_ASIGNACION' => $_POST['FECHA_ASIGNACION'],
        ':TIPO_ASIGNACION' => $_POST['TIPO_ASIGNACION'],
        ':ID_ITEM_ASIGNADO' => $_POST['ID_ITEM_ASIGNADO'],
        ':CANTIDAD_ASIGNADA' => $_POST['CANTIDAD_ASIGNADA'],
        ':ESTADO_HERRAMIENTA' => $_POST['ESTADO_HERRAMIENTA'],
        ':OBSERVACION' => $_POST['OBSERVACION']
    ]);
    header('Location: asignaciones.php'); // Redirige a la página principal después de la actualización
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asignación</title>
    <link rel="stylesheet" href="../css/estilos_material.css">
</head>
<body>
    <h1>Editar Asignación</h1>

    <form method="post">
        <input type="hidden" name="ID_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?>">

        <label>ID Asignación:</label><br>
        <input type="text" name="ID_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?>" readonly><br>
        
        <label>Cédula Técnico:</label><br>
        <input type="number" name="CEDULA_TEC" value="<?php echo htmlspecialchars($asignacion['CEDULA_TEC']); ?>"><br>
        
        <label>ID Usuario:</label><br>
        <input type="number" name="ID_USUARIO" value="<?php echo htmlspecialchars($asignacion['ID_USUARIO']); ?>"><br>
        
        <label>Fecha Asignación:</label><br>
        <input type="date" name="FECHA_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['FECHA_ASIGNACION']); ?>"><br>
        
        <label>Tipo Asignación:</label><br>
        <input type="text" name="TIPO_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['TIPO_ASIGNACION']); ?>"><br>
        
        <label>ID Item Asignado:</label><br>
        <input type="text" name="ID_ITEM_ASIGNADO" value="<?php echo htmlspecialchars($asignacion['ID_ITEM_ASIGNADO']); ?>"><br>
        
        <label>Cantidad Asignada:</label><br>
        <input type="number" name="CANTIDAD_ASIGNADA" value="<?php echo htmlspecialchars($asignacion['CANTIDAD_ASIGNADA']); ?>"><br>
        
        <label>Estado Herramienta:</label><br>
        <input type="text" name="ESTADO_HERRAMIENTA" value="<?php echo htmlspecialchars($asignacion['ESTADO_HERRAMIENTA']); ?>"><br>
        
        <label>Observación:</label><br>
        <textarea name="OBSERVACION"><?php echo htmlspecialchars($asignacion['OBSERVACION']); ?></textarea><br>
        
        <input type="submit" name="update" value="Actualizar Asignación">
    </form>
    
    <a href="asignaciones.php">Volver a la lista de asignaciones</a>
</body>
</html>
