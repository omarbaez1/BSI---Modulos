<?php
include 'conexion.php';
$pdo = conexion_administrador();

// Obtener datos del celular a editar
if (isset($_GET['COD_CELULAR'])) {
    $sql = "SELECT * FROM celulares WHERE COD_CELULAR = :COD_CELULAR";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':COD_CELULAR' => $_GET['COD_CELULAR']]);
    $celular = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Editar el celular
if (isset($_POST['edit'])) {
    $sql = "UPDATE celulares SET CEDULA_TEC = :CEDULA_TEC, NOMBRE_COMPLETO = :NOMBRE_COMPLETO, MARCA = :MARCA, MODELO = :MODELO, PROPIETARIO = :PROPIETARIO, IMEI1 = :IMEI1, IMEI2 = :IMEI2, NUMERO_ASIGNADO = :NUMERO_ASIGNADO, FECHA_INGRESO = :FECHA_INGRESO, ESTADO = :ESTADO, FECHA_ASIGNACION = :FECHA_ASIGNACION, OBSERVACION = :OBSERVACION WHERE COD_CELULAR = :COD_CELULAR";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':COD_CELULAR' => $_POST['COD_CELULAR'],
        ':CEDULA_TEC' => $_POST['CEDULA_TEC'],
        ':NOMBRE_COMPLETO' => $_POST['NOMBRE_COMPLETO'],
        ':MARCA' => $_POST['MARCA'],
        ':MODELO' => $_POST['MODELO'],
        ':PROPIETARIO' => $_POST['PROPIETARIO'],
        ':IMEI1' => $_POST['IMEI1'],
        ':IMEI2' => $_POST['IMEI2'],
        ':NUMERO_ASIGNADO' => $_POST['NUMERO_ASIGNADO'],
        ':FECHA_INGRESO' => $_POST['FECHA_INGRESO'],
        ':ESTADO' => $_POST['ESTADO'],
        ':FECHA_ASIGNACION' => $_POST['FECHA_ASIGNACION'],
        ':OBSERVACION' => $_POST['OBSERVACION']
    ]);
    header('Location: celulares.php'); // Redirige de vuelta a la lista de celulares
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Celular</title>
    <link rel="stylesheet" href="css/estilos_materiales.css">

</head>
<body>
    <h1>Editar Celular</h1>
    <form method="post">
        <input type="hidden" name="COD_CELULAR" value="<?php echo htmlspecialchars($celular['COD_CELULAR']); ?>">
        <label>Cédula Técnico:</label><br>
        <input type="number" name="CEDULA_TEC" value="<?php echo htmlspecialchars($celular['CEDULA_TEC']); ?>"><br>
        <label>Nombre Completo:</label><br>
        <input type="text" name="NOMBRE_COMPLETO" value="<?php echo htmlspecialchars($celular['NOMBRE_COMPLETO']); ?>"><br>
        <label>Marca:</label><br>
        <input type="text" name="MARCA" value="<?php echo htmlspecialchars($celular['MARCA']); ?>"><br>
        <label>Modelo:</label><br>
        <input type="text" name="MODELO" value="<?php echo htmlspecialchars($celular['MODELO']); ?>"><br>
        <label>Propietario:</label><br>
        <input type="text" name="PROPIETARIO" value="<?php echo htmlspecialchars($celular['PROPIETARIO']); ?>"><br>
        <label>IMEI1:</label><br>
        <input type="text" name="IMEI1" value="<?php echo htmlspecialchars($celular['IMEI1']); ?>"><br>
        <label>IMEI2:</label><br>
        <input type="text" name="IMEI2" value="<?php echo htmlspecialchars($celular['IMEI2']); ?>"><br>
        <label>Número Asignado:</label><br>
        <input type="number" name="NUMERO_ASIGNADO" value="<?php echo htmlspecialchars($celular['NUMERO_ASIGNADO']); ?>"><br>
        <label>Fecha Ingreso:</label><br>
        <input type="date" name="FECHA_INGRESO" value="<?php echo htmlspecialchars($celular['FECHA_INGRESO']); ?>"><br>
        <label>Estado:</label><br>
        <input type="text" name="ESTADO" value="<?php echo htmlspecialchars($celular['ESTADO']); ?>"><br>
        <label>Fecha Asignación:</label><br>
        <input type="date" name="FECHA_ASIGNACION" value="<?php echo htmlspecialchars($celular['FECHA_ASIGNACION']); ?>"><br>
        <label>Observación:</label><br>
        <textarea name="OBSERVACION"><?php echo htmlspecialchars($celular['OBSERVACION']); ?></textarea><br>
        <input type="submit" name="edit" value="Actualizar Celular">
    </form>
</body>
</html>
