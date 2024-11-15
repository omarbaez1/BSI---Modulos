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
    if (isset($_POST['cam_con'], $_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['periodo'], $_POST['motivo'], $_POST['cam_can'], $_POST['cam_talla'], $_POST['entrega'], $_POST['observacion'])) {
        $cam_con = $_POST['cam_con'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo']; 
        $cam_can = $_POST['cam_can'];
        $cam_talla = $_POST['cam_talla'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "UPDATE camisa SET CEDULA=?, NOMBRE=?, FECHA_ENTREGA=?, PERIODO=?, MOTIVO=?, CAM_CAN=?, CAM_TALLA=?,ENTREGA=?, OBSERVACION=? WHERE CAM_CON=?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssisssi", $cedula, $nombre, $fecha_entrega,
             $periodo, $motivo, $cam_can, $cam_talla,$entrega, $observacion, $cam_con);
            if ($stmt->execute()) {
                header('Location: camisa.php'); // Redirige de vuelta a la lista de camisas
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

// Obtener datos de la camisa a editar
if (isset($_GET['cam_con'])) {
    $cam_con = $_GET['cam_con'];
    
    $stmt = $conn->prepare("SELECT * FROM camisa WHERE CAM_CON = ?");
    $stmt->bind_param("i", $cam_con);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $camisa = $result->fetch_assoc();
    } else {
        echo "<p>No se encontró la camisa.</p>";
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
    <title>Editar Camisa</title>
    <link rel="stylesheet" href="estilo_u.css">
    <style>
        
    </style>
</head>
<body>
    <h1>Editar Camisa</h1>
    <form method="POST" action="">
        <input type="hidden" name="cam_con" value="<?php echo htmlspecialchars($camisa['CAM_CON']); ?>">
        
        <label for="cedula">Cédula:</label>
        <input type="number" id="cedula" name="cedula" value="<?php echo htmlspecialchars($camisa['CEDULA']); ?>" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($camisa['NOMBRE']); ?>" required>
        
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo htmlspecialchars($camisa['FECHA_ENTREGA']); ?>" required>
        
        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo" value="<?php echo htmlspecialchars($camisa['PERIODO']); ?>">
        
        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($camisa['MOTIVO']); ?>">
        
        <label for="cam_can">Cantidad:</label>
        <input type="number" id="cam_can" name="cam_can" value="<?php echo htmlspecialchars($camisa['CAM_CAN']); ?>">
        
        <label for="cam_talla">Talla:</label>
        <input type="text" id="cam_talla" name="cam_talla" value="<?php echo htmlspecialchars($camisa['CAM_TALLA']); ?>">
        
        <label for="entrega">Entregado por:</label>
        <input type="text" id="entrega" name="entrega" value="<?php echo htmlspecialchars($camisa['ENTREGA']); ?>" >

        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"><?php echo htmlspecialchars($camisa['OBSERVACION']); ?></textarea>
        
        <div>
            <input type="submit" value="Actualizar Camisa">
            <a href="camisa.php" class="cancel-link">Cancelar</a>
        </div>
    </form>
</body>
</html>
