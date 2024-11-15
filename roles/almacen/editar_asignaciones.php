<?php
include 'conexion.php'; // Incluye el archivo de conexión

// Obtener el ID de la asignación que se quiere editar
$id_asignacion = isset($_GET['id']) ? $_GET['id'] : '';

if ($id_asignacion) {
    $pdo = conexion_administrador();
    $sql = "SELECT * FROM asignaciones WHERE ID_ASIGNACION = :id_asignacion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_asignacion' => $id_asignacion]);
    $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asignación</title>
</head>
<body>
    <h1>Editar Asignación</h1>
    <form action="update_asignaciones.php" method="post">
        <input type="hidden" name="ID_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['ID_ASIGNACION']); ?>">
        <label for="CEDULA_TEC">Cédula Técnico:</label>
        <input type="text" id="CEDULA_TEC" name="CEDULA_TEC" value="<?php echo htmlspecialchars($asignacion['CEDULA_TEC']); ?>"><br>
        <label for="ID_USUARIO">ID Usuario:</label>
        <input type="text" id="ID_USUARIO" name="ID_USUARIO" value="<?php echo htmlspecialchars($asignacion['ID_USUARIO']); ?>"><br>
        <label for="FECHA_ASIGNACION">Fecha Asignación:</label>
        <input type="date" id="FECHA_ASIGNACION" name="FECHA_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['FECHA_ASIGNACION']); ?>"><br>
        <label for="TIPO_ASIGNACION">Tipo Asignación:</label>
        <input type="text" id="TIPO_ASIGNACION" name="TIPO_ASIGNACION" value="<?php echo htmlspecialchars($asignacion['TIPO_ASIGNACION']); ?>"><br>
        <label for="ID_ITEM_ASIGNADO">ID Item Asignado:</label>
        <input type="text" id="ID_ITEM_ASIGNADO" name="ID_ITEM_ASIGNADO" value="<?php echo htmlspecialchars($asignacion['ID_ITEM_ASIGNADO']); ?>"><br>
        <label for="CANTIDAD_ASIGNADA">Cantidad Asignada:</label>
        <input type="text" id="CANTIDAD_ASIGNADA" name="CANTIDAD_ASIGNADA" value="<?php echo htmlspecialchars($asignacion['CANTIDAD_ASIGNADA']); ?>"><br>
        <label for="ESTADO_HERRAMIENTA">Estado Herramienta:</label>
        <input type="text" id="ESTADO_HERRAMIENTA" name="ESTADO_HERRAMIENTA" value="<?php echo htmlspecialchars($asignacion['ESTADO_HERRAMIENTA']); ?>"><br>
        <label for="OBSERVACION">Observación:</label>
        <textarea id="OBSERVACION" name="OBSERVACION"><?php echo htmlspecialchars($asignacion['OBSERVACION']); ?></textarea><br>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
