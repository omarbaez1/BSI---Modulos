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
    if (isset($_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['calzado_can'], $_POST['calzado_talla'], $_POST['mal_masivo'], $_POST['mal_moto'], $_POST['gorra'], $_POST['bolsa'], $_POST['periodo'], $_POST['motivo'], $_POST['bsi'], $_POST['vanti'], $_POST['sura'], $_POST['portacarnet'],$_POST['entrega'])) {
        
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $calzado_can = $_POST['calzado_can'];
        $calzado_talla = $_POST['calzado_talla'];
        $mal_masivo = $_POST['mal_masivo'];
        $mal_moto = $_POST['mal_moto'];
        $gorra = $_POST['gorra'];
        $bolsa = $_POST['bolsa'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo'];
        $bsi = $_POST['bsi'];
        $vanti = $_POST['vanti'];   
        $sura = $_POST['sura'];
        $portacarnet = $_POST['portacarnet'];
        $entrega = $_POST['entrega'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "UPDATE dotacion SET 
                    NOMBRE=?, 
                    FECHA_ENTREGA=?, 
                    CALZADO_CAN=?, 
                    CALZADO_TALLA=?, 
                    MAL_MASIVO=?, 
                    MAL_MOTO=?, 
                    GORRA=?, 
                    BOLSA=?, 
                    MOTIVO=?, 
                    PERIODO=?, 
                    BSI=?, 
                    VANTI=?, 
                    SURA=?, 
                    PORTACARNET=?, 
                    ENTREGA=?
                    where CEDULA=?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Cambia la cadena de tipos a que coincida con las variables
            $stmt->bind_param("ssisssssssssssis", $nombre, $fecha_entrega, $calzado_can, $calzado_talla, $mal_masivo, $mal_moto, $gorra, $bolsa, $motivo, $periodo, $bsi, $vanti, $sura, $portacarnet, $cedula,$entrega);
            
            if ($stmt->execute()) {
                header('Location: carnet.php'); // Redirige de vuelta a la lista de dotaciones
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

// Obtener datos a editar
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    
    $stmt = $conn->prepare("SELECT * FROM dotacion WHERE cedula = ?");
    $stmt->bind_param("i", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $dotacion = $result->fetch_assoc();
    } else {
        echo "<p>No se encontró la dotación.</p>";
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
    <title>Editar Datos</title>
    <link rel="stylesheet" href="estilo_u.css">
    <style>
        
    </style>
</head>
<body>
    <h1>Editar Dotación</h1>
    <form method="POST" action="">
        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($dotacion['CEDULA']); ?>">
    
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($dotacion['NOMBRE']); ?>" required>
        
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo htmlspecialchars($dotacion['FECHA_ENTREGA']); ?>" required>
        
        <label for="calzado_can">Cantidad Calzado:</label>
        <input type="text" id="calzado_can" name="calzado_can" value="<?php echo htmlspecialchars($dotacion['CALZADO_CAN']); ?>">
        
        <label for="calzado_talla">Talla Calzado:</label>
        <input type="text" id="calzado_talla" name="calzado_talla" value="<?php echo htmlspecialchars($dotacion['CALZADO_TALLA']); ?>">
        
        <label for="mal_masivo">Maleta Reparto Masivo:</label>
        <input type="text" id="mal_masivo" name="mal_masivo" value="<?php echo htmlspecialchars($dotacion['MAL_MASIVO']); ?>">
        
        <label for="mal_moto">Maleta Motorizado:</label>
        <input type="text" id="mal_moto" name="mal_moto" value="<?php echo htmlspecialchars($dotacion['MAL_MOTO']); ?>">
        
        <label for="gorra">Gorra:</label>
        <input type="text" id="gorra" name="gorra" value="<?php echo htmlspecialchars($dotacion['GORRA']); ?>">
        
        <label for="bolsa">Bolsa:</label>
        <input type="text" id="bolsa" name="bolsa" value="<?php echo htmlspecialchars($dotacion['BOLSA']); ?>">
        
        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo" value="<?php echo htmlspecialchars($dotacion['PERIODO']); ?>">
        
        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($dotacion['MOTIVO']); ?>">
        
        <label for="bsi">Carnet BSI:</label>
        <input type="number" id="bsi" name="bsi" value="<?php echo htmlspecialchars($dotacion['BSI']); ?>">
        
        <label for="vanti">Carnet Vanti:</label>
        <input type="text" id="vanti" name="vanti" value="<?php echo htmlspecialchars($dotacion['VANTI']); ?>">
        
        <label for="sura">Carnet Sura:</label>
        <input type="text" id="sura" name="sura" value="<?php echo htmlspecialchars($dotacion['SURA']); ?>">
        
        <label for="portacarnet">Portacarnet:</label>
        <input type="text" id="portacarnet" name="portacarnet" value="<?php echo htmlspecialchars($dotacion['PORTACARNET']); ?>">

        <label for="entrega">Entregado Por:</label>
        <input type="text" id="entrega" name="entrega" value="<?php echo htmlspecialchars($dotacion['ENTREGA']); ?>">
        
        <div>
            <input type="submit" value="Actualizar Dotación">
            <a href="carnet.php" class="cancel-link">Cancelar</a>
        </div>
    </form>
</body>
</html>
