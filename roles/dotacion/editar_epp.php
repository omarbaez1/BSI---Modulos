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
    if (isset($_POST['id_epp'],$_POST['cedula'], $_POST['nombre'], $_POST['fecha_entrega'], $_POST['tapabocas'], $_POST['gua_cau'], $_POST['zap_anti'], $_POST['len_seg'], $_POST['gua_tela'], $_POST['bot_seg'], $_POST['cas_ara'], $_POST['arn_pro'], $_POST['cha_vis'], $_POST['cha_vis_vanti'], $_POST['cha_airbag'],$_POST['entrega'], $_POST['observacion'])) {
        $id_epp = $_POST['id_epp'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $tapabocas = $_POST['tapabocas'];
        $gua_cau = $_POST['gua_cau'];
        $zap_anti = $_POST['zap_anti'];
        $len_seg = $_POST['len_seg'];
        $gua_tela = $_POST['gua_tela'];
        $bot_seg = $_POST['bot_seg'];
        $cas_ara = $_POST['cas_ara'];
        $arn_pro = $_POST['arn_pro'];
        $cha_vis = $_POST['cha_vis'];
        $cha_vis_vanti = $_POST['cha_vis_vanti'];
        $cha_airbag = $_POST['cha_airbag'];
        $entrega = $_POST['entrega'];
        $observacion = $_POST['observacion'];

        if (!$conn) {
            die("Error: La conexión a la base de datos no se estableció.");
        }

        $sql = "UPDATE epp2 SET NOMBRE=?, FECHA_ENTREGA=?, TAPABOCAS=?, GUA_CAU=?, ZAP_ANTI=?, LEN_SEG=?, GUA_TELA=?, BOT_SEG=?, CAS_ARA=?, ARN_PRO=?, CHA_VIS=?, CHA_VIS_VANTI=?, CHA_AIRBAG=?, OBSERVACION=? WHERE CEDULA=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssssssssssi", $nombre, $fecha_entrega, $tapabocas, 
            $gua_cau, $zap_anti, $len_seg, $gua_tela, $bot_seg, $cas_ara, $arn_pro, 
            $cha_vis, $cha_vis_vanti, $cha_airbag,$entrega, $observacion, $cedula);
            if ($stmt->execute()) {
                header('Location: epp2.php'); // Redirige de vuelta a la lista de camisas
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
// Obtener datos de la camisa a editar
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    
    $stmt = $conn->prepare("SELECT * FROM epp2 WHERE CEDULA = ?");
    $stmt->bind_param("i", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $epp2 = $result->fetch_assoc();
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
    <title>Editar epp2</title>
    <link rel="stylesheet" href="estilo_u.css">
    <style>

    </style>
</head>
<body>
    <h1>Editar epp2</h1>
    <form method="POST" action="">
        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($epp2['CEDULA']); ?>">
        <input type="hidden" name="id_epp" value="<?php echo htmlspecialchars($epp2['ID_EPP']); ?>">
        
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($epp2['NOMBRE']); ?>" required>

        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo htmlspecialchars($epp2['FECHA_ENTREGA']); ?>" required>

        <label for="tapabocas">Tapabocas:</label>
        <input type="text" id="tapabocas" name="tapabocas" value="<?php echo htmlspecialchars($epp2['TAPABOCAS']); ?>" required>

        <label for="gua_cau">Guantes de Caucho:</label>
        <input type="text" id="gua_cau" name="gua_cau" value="<?php echo htmlspecialchars($epp2['GUA_CAU']); ?>" required>

        <label for="zap_anti">Zapatos Antideslizantes:</label>
        <input type="text" id="zap_anti" name="zap_anti" value="<?php echo htmlspecialchars($epp2['ZAP_ANTI']); ?>" required>

        <label for="len_seg">Lentes de Seguridad:</label>
        <input type="text" id="len_seg" name="len_seg" value="<?php echo htmlspecialchars($epp2['LEN_SEG']); ?>" required>

        <label for="gua_tela">Guantes de Tela:</label>
        <input type="text" id="gua_tela" name="gua_tela" value="<?php echo htmlspecialchars($epp2['GUA_TELA']); ?>" required>

        <label for="bot_seg">Botas de Seguridad:</label>
        <input type="text" id="bot_seg" name="bot_seg" value="<?php echo htmlspecialchars($epp2['BOT_SEG']); ?>" required>

        <label for="cas_ara">Casco de Ara:</label>
        <input type="text" id="cas_ara" name="cas_ara" value="<?php echo htmlspecialchars($epp2['CAS_ARA']); ?>" required>

        <label for="arn_pro">Arnés de Protección:</label>
        <input type="text" id="arn_pro" name="arn_pro" value="<?php echo htmlspecialchars($epp2['ARN_PRO']); ?>" required>

        <label for="cha_vis">Chaqueta Visibilidad:</label>
        <input type="text" id="cha_vis" name="cha_vis" value="<?php echo htmlspecialchars($epp2['CHA_VIS']); ?>" required>

        <label for="cha_vis_vanti">Chaqueta Visibilidad Vanti:</label>
        <input type="text" id="cha_vis_vanti" name="cha_vis_vanti" value="<?php echo htmlspecialchars($epp2['CHA_VIS_VANTI']); ?>" required>

        <label for="cha_airbag">Chaqueta Airbag:</label>
        <input type="text" id="cha_airbag" name="cha_airbag" value="<?php echo htmlspecialchars($epp2['CHA_AIRBAG']); ?>" required>

        <label for="entrega">Entregado Por:</label>
        <input type="text" id="entrega" name="entrega" value="<?php echo htmlspecialchars($epp2['ENTREGA']); ?>" >

        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion" required><?php echo htmlspecialchars($epp2['OBSERVACION']); ?></textarea>

        <div>
            <input type="submit" value="Actualizar epp2">
            <a href="epp2.php" class="cancel-link">Cancelar</a>
        </div>
    </form>
</body>
</html>
