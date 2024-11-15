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
    if (isset($_POST['ove_con'], $_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'],
     $_POST['periodo'], $_POST['motivo'], $_POST['ove_can'], $_POST['ove_talla'],$_POST['entrega'], $_POST['observacion'])) {
        $ove_con = $_POST['ove_con'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['feove_entrega'];
        $periodo = $_POST['periodo'];
        $motivo = $_POST['motivo']; 
        $ove_can = $_POST['ove_can'];
        $ove_talla = $_POST['ove_talla'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "UPDATE overol SET CEDULA=?, NOMBRE=?, FECHA_ENTREGA=?, PERIODO=?, MOTIVO=?,
         ove_CAN=?, ove_TALLA=?,ENTREGA=?, OBSERVACION=? WHERE ove_CON=?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issssisssi", $cedula, $nombre, $fecha_entrega, 
            $periodo, $motivo, $ove_can, $ove_talla,$entrega, $observacion, $ove_con);
            if ($stmt->execute()) {
                header('Location: overol.php'); // Redirige de vuelta a overol.php
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

// Obtener datos de la overol a editar
if (isset($_GET['ove_con'])) {
    $ove_con = $_GET['ove_con'];
    
    $stmt = $conn->prepare("SELECT * FROM overol WHERE ove_CON = ?");
    $stmt->bind_param("i", $ove_con);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $overol = $result->fetch_assoc();
    } else {
        echo "<p>No se encontró el overol.</p>";
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
    <title>Editar overol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .cancel-link {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
            transition: color 0.3s;
        }
        .cancel-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Editar overol</h1>
    <form method="POST" action="">
        <input type="hidden" name="ove_con" value="<?php echo htmlspecialchars($overol['OVE_CON']); ?>">
        
        <label for="cedula">Cédula:</label>
        <input type="number" id="cedula" name="cedula" value="<?php echo htmlspecialchars($overol['CEDULA']); ?>" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($overol['NOMBRE']); ?>" required>
        
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo htmlspecialchars($overol['FECHA_ENTREGA']); ?>" required>
        
        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo" value="<?php echo htmlspecialchars($overol['PERIODO']); ?>">
        
        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($overol['MOTIVO']); ?>">
        
        <label for="ove_can">Cantidad:</label>
        <input type="number" id="ove_can" name="ove_can" value="<?php echo htmlspecialchars($overol['OVE_CAN']); ?>">
        
        <label for="ove_talla">Talla:</label>
        <input type="text" id="ove_talla" name="ove_talla" value="<?php echo htmlspecialchars($overol['OVE_TALLA']); ?>">
        
        <label for="entrega">Entregado Por:</label>
        <input type="text" id="entrega" name="entrega" value="<?php echo htmlspecialchars($overol['ENTREGA']); ?>">

        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"><?php echo htmlspecialchars($overol['OBSERVACION']); ?></textarea>
        
        <div>
            <input type="submit" value="Actualizar overol">
            <a href="overol.php" class="cancel-link">Cancelar</a>
        </div>
    </form>
</body>
</html>
