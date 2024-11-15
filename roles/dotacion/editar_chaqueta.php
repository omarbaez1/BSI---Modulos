<?php
// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'epp';
$username = 'root';
$password = '';

// Incluye el archivo de conexión a la base de datos
include 'CONEXIONES.php';

// Verificar si se ha enviado una solicitud de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cha_con'], $_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['periodo'], $_POST['motivo'], $_POST['cha_can'], $_POST['cha_talla'],$_POST['entrega'], $_POST['observacion'])) {
        $cha_con = $_POST['cha_con'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo']; 
        $cha_can = $_POST['cha_can'];
        $cha_talla = $_POST['cha_talla'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "UPDATE chaqueta SET CEDULA=?, NOMBRE=?, FECHA_ENTREGA=?, PERIODO=?, MOTIVO=?, CHA_CAN=?, CHA_TALLA=?,ENTREGA=?, OBSERVACION=? WHERE CHA_CON=?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssisssi", $cedula, $nombre, $fecha_entrega, $periodo, $motivo, $cha_can, $cha_talla, $entrega,$observacion, $cha_con);
            if ($stmt->execute()) {
                header('Location: chaqueta.php'); // Redirige de vuelta a chaqueta.php
                exit;
            } else {
                echo "<p>Error al actualizar los datos: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
        }

        $conn->close();
    } else {
        echo "<p>Datos del formulario incompletos.</p>";
    }
}

// Obtener datos de la chaqueta a editar
if (isset($_GET['cha_con'])) {
    $cha_con = $_GET['cha_con'];
    
    $stmt = $conn->prepare("SELECT * FROM chaqueta WHERE CHA_CON = ?");
    $stmt->bind_param("i", $cha_con);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $chaqueta = $result->fetch_assoc();
    } else {
        echo "<p>No se encontró la chaqueta.</p>";
        exit;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Chaqueta</title>
    <link rel="stylesheet" href="estilo_u.css">
    <style>
    </style>
</head>
<body>
    <h1>Editar Chaqueta</h1>
    <form method="POST" action="">
        <input type="hidden" name="cha_con" value="<?php echo htmlspecialchars($chaqueta['CHA_CON']); ?>">
        
        <label for="cedula">Cédula:</label>
        <input type="number" id="cedula" name="cedula" value="<?php echo htmlspecialchars($chaqueta['CEDULA']); ?>" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($chaqueta['NOMBRE']); ?>" required>
        
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo htmlspecialchars($chaqueta['FECHA_ENTREGA']); ?>" required>
        
        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo" value="<?php echo htmlspecialchars($chaqueta['PERIODO']); ?>">
        
        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($chaqueta['MOTIVO']); ?>">
        
        <label for="cha_can">Cantidad:</label>
        <input type="number" id="cha_can" name="cha_can" value="<?php echo htmlspecialchars($chaqueta['CHA_CAN']); ?>">
        
        <label for="cha_talla">Talla:</label>
        <input type="text" id="cha_talla" name="cha_talla" value="<?php echo htmlspecialchars($chaqueta['CHA_TALLA']); ?>">
        
        <label for="entrega">Entregado Por:</label>
        <textarea id="entrega" name="entrega"><?php echo htmlspecialchars($chaqueta['ENTREGA']); ?></textarea>
        
        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"><?php echo htmlspecialchars($chaqueta['OBSERVACION']); ?></textarea>
        
        <div>
            <input type="submit" value="Actualizar Chaqueta">
            <a href="chaqueta.php" class="cancel-link">Cancelar</a>
        </div>
    </form>
</body>
</html>
