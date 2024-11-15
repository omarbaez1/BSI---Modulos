<?php  
// Conexión a la base de datos
function conexion_administrador() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=epp', username: 'root', password: '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . htmlspecialchars($e->getMessage());
        die();
    }
}

$pdo = conexion_administrador();

// Procesar la adición de un nuevo técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    // Obtener datos del formulario
    $id_epp = $_POST['id_epp'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $periodo = $_POST['periodo'];
    $cal_can = $_POST['cal_can'];
    $cal_talla= $_POST['cal_talla'];
    $mal_mas = $_POST['mal_mas'];
    $mal_moto = $_POST['mal_moto'];
    $gorra = $_POST['gorra'];
    $bolsa = $_POST['bolsa'];
    $bsi = $_POST['bsi'];
    $vanti = $_POST['vanti'];
    $sura = $_POST['sura'];
    $portacarnet = $_POST['portacarnet'];
    $entrega = $_POST['entrega'];
    
    

    // Insertar nuevo registro
    $sql = "INSERT INTO dotacion (ID_EPP,CEDULA, NOMBRE, 
    FECHA_ENTREGA,
    PERIODO, CAL_CAN, CAL_TALLA, 
    MAL_MAS, MAL_MOTO, GORRA, BOLSA, BSI,
    VANTI, SURA, PORTACARNET, ENTREGA) 
           
    VALUES (:id_epp, :cedula, :nombre, :fecha_entrega, :periodo, 
    :cal_can, :cal_talla, 
    :mal_mas, :mal_moto, :gorra, :bolsa, 
    :bsi, :vanti, :sura, :portacarnet, :entrega)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_epp' => $id_epp,
        ':cedula' => $cedula,
        ':nombre' => $nombre,
        ':fecha_entrega' => $fecha_entrega,
        ':periodo' => $periodo,
        ':cal_can' => $cal_can,
        ':cal_talla' => $cal_talla,
        ':mal_mas' => $mal_mas,
        ':mal_moto' => $mal_moto,
        ':gorra' => $gorra,
        ':bolsa' => $bolsa,
        ':bsi' => $bsi,
        ':vanti' => $vanti,
        ':sura' => $sura,
        ':portacarnet' => $portacarnet,
        ':entrega' => $entrega,


    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la edición de un técnico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    // Obtener datos del formulario
        $id_epp = $_POST['id_epp'];
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $periodo = $_POST['periodo'];
        $cal_can = $_POST['cal_can'];
        $cal_talla= $_POST['cal_talla'];
        $mal_mas = $_POST['mal_mas'];
        $mal_moto = $_POST['mal_moto'];
        $gorra = $_POST['gorra'];
        $bolsa = $_POST['bolsa'];
        $bsi = $_POST['bsi'];
        $vanti = $_POST['vanti'];
        $sura = $_POST['sura'];
        $portacarnet = $_POST['portacarnet'];
        $entrega = $_POST['entrega'];
    
    // Actualizar el registro
    $sql = "UPDATE dotacion SET 
    CEDULA =:cedula,
    NOMBRE = :nombre,
    FECHA_ENTREGA = :fecha_entrega, 
    PERIODO = :periodo,
    CAL_CAN= :cal_can,
    CAL_TALLA= :cal_talla,
    MAL_MAS= :mal_mas,
    MAL_MOTO= :mal_moto,
    GORRA= :gorra,
    BOLSA= :bolsa,
    BSI= :bsi,
    VANTI =:vanti,
    SURA =:sura,
    PORTACARNET =:portacarnet,
    ENTREGA =:entrega
    WHERE ID_EPP = :id_epp";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_epp' => $id_epp,
        ':cedula' => $cedula,
        ':nombre' => $nombre,
        ':fecha_entrega' => $fecha_entrega,
        ':periodo' => $periodo,
        ':cal_can' => $cal_can,
        ':cal_talla' => $cal_talla,
        ':mal_mas' => $mal_mas,
        ':mal_moto' => $mal_moto,
        ':gorra' => $gorra,
        ':bolsa' => $bolsa,
        ':bsi' => $bsi,
        ':vanti' => $vanti,
        ':sura' => $sura,
        ':portacarnet' => $portacarnet,
        ':entrega' => $entrega
    ]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Procesar la eliminación de un técnico
if (isset($_GET['delete'])) {
    $id_epp = $_GET['delete'];
    
    $sql = "DELETE FROM dotacion WHERE ID_EPP = :id_epp";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_epp' => $id_epp]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirigir a la misma página para ver los cambios
    exit();
}

// Obtener los datos del técnico para edición, si se solicita
$editMode = false;
$editData = [];
if (isset($_GET['edit'])) {
    $id_epp = $_GET['edit'];
    
    $sql = "SELECT * FROM dotacion WHERE ID_EPP = :id_epp";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_epp' => $id_epp]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($editData) {
        $editMode = true;
    }
}

// Inicializar variables para búsqueda
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';

// Búsqueda dinámica
$sql = "SELECT * FROM dotacion WHERE 1=1";
$params = [];

// Agregar condiciones dinámicas para la búsqueda
if (!empty($cedula)) {
    $sql .= " AND LOWER(CEDULA) LIKE LOWER(:cedula)";
    $params[':cedula'] = '%' . $cedula . '%';
}
// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con los parámetros si se proporcionan
$stmt->execute($params);

$epp2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de epp1</title>
    <link rel="stylesheet" href="estilo_u.css">
    <style>

</style>

    <script>
        function toggleForm() {
            var form = document.getElementById('add-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar este registro?');
        }
    </script>
</head>
<body>
<button class="toggle-button"  onclick="window.location.href='../../inicio_sesion/dotacion.php';">Página Principal</button>

<h2>Busqueda EPP1</h2>

<!-- Formulario de Búsqueda -->
<form method="GET" action="">
    <label for="cedula">Cédula Personal:</label>
    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($cedula); ?>">
    <button type="submit" class="btn">Buscar</button>
</form>

<?php if ($editMode): ?>
    <!-- Formulario para Editar Técnico -->
    <h2>Editar Datos</h2>
    <form method="POST" action="">
    <input type="hidden" name="edit" value="true">

        <input type="hidden" name="id_epp" value="<?php echo htmlspecialchars($editData['ID_EPP']); ?>">
        
        <label for="nombre">Cedula:</label>
        <input type="text" name="cedula" value="<?php echo htmlspecialchars($editData['CEDULA']); ?>">

        <label for="nombre">Nombre Completo:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($editData['NOMBRE']); ?>">
        
        <label for="fecha_entrega">Fecha Entrega:</label>
        <input type="text" name="fecha_entrega" id="fecha_entrega" value="<?php echo htmlspecialchars($editData['FECHA_ENTREGA']); ?>">
        
        <label for="periodo">Período:</label>
        <input type="text" name="periodo" id="periodo" value="<?php echo htmlspecialchars($editData['PERIODO']); ?>">
  
        <label for="cal_talla">Talla Calzado:</label>
        <input type="text" name="cal_talla" id="cal_talla" value="<?php echo htmlspecialchars($editData['CAL_TALLA']); ?>">
        
        <label for="mal_mas">Mal Masivo:</label>
        <input type="text" name="mal_mas" id="mal_mas" value="<?php echo htmlspecialchars($editData['MAL_MAS']); ?>">
        
        <label for="mal_moto">Mal Moto:</label>
        <input type="text" name="mal_moto" id="mal_moto" value="<?php echo htmlspecialchars($editData['MAL_MOTO']); ?>">
        
        <label for="gorra">Gorra:</label>
        <input type="text" name="gorra" id="gorra" value="<?php echo htmlspecialchars($editData['GORRA']); ?>">
        
        <label for="bolsa">Bolsa:</label>
        <input type="text" name="bolsa" id="bolsa" value="<?php echo htmlspecialchars($editData['BOLSA']); ?>">
        
        <label for="bsi">BSI:</label>
        <input type="text" name="bsi" id="bsi" value="<?php echo htmlspecialchars($editData['BSI']); ?>">
        
        <label for="vanti">Vanti:</label>
        <input type="text" name="vanti" id="vanti" value="<?php echo htmlspecialchars($editData['VANTI']); ?>">
        
        <label for="sura">Sura:</label>
        <input type="text" name="sura" id="sura" value="<?php echo htmlspecialchars($editData['SURA']); ?>">
        
        <label for="portacarnet">Porta Carnet:</label>
        <input type="text" name="portacarnet" id="portacarnet" value="<?php echo htmlspecialchars($editData['PORTACARNET']); ?>">
        
        <label for="entrega">Entregado Por:</label>
        <input type="text" name="entrega" id="entrega" value="<?php echo htmlspecialchars($editData['ENTREGA']); ?>">
        
        <button type="submit" class="btn">Guardar Cambios</button>
    </form>
<?php endif; ?>

<!-- Formulario para Añadir Técnico -->
<h2>Añadir Nuevo EPP1</h2>
<button class="btn" onclick="toggleEPPForm()">Mostrar/Ocultar Formulario</button>
<div id="add-epp-form" style="display: none;"> <!-- Ocultar el formulario inicialmente -->
    <form method="POST" action="">
        <input type="hidden" name="add" value="true">

        <label for="cedula">Cédula:</label>
        <input type="text" name="cedula" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" name="fecha_entrega" required>

        <label for="motivo">Periodo:</label>
        <input type="text" name="periodo">

        <label for="cal_can">Calzado Cantidad:</label>
        <input type="text" name="cal_can">

        <label for="cal_talla">Calzado Talla:</label>
        <input type="text" name="cal_talla">

        <label for="mal_mas">Maleta Masivo:</label>
        <input type="text" name="mal_mas">

        <label for="mal_moto">Maletín Motorizado:</label>
        <input type="text" name="mal_moto">

        <label for="gorra">Gorra:</label>
        <input type="text" name="gorra">

        <label for="bolsa">Bolsa:</label>
        <input type="text" name="bolsa">

        <label for="bsi">BSI:</label>
        <input type="text" name="bsi">

        <label for="vanti">Vanti:</label>
        <input type="text" name="vanti">

        <label for="sura">Sura:</label>
        <input type="text" name="sura">

        <label for="portacarnet">Portacarnet:</label>
        <input type="text" name="portacarnet">

        <label for="entrega">Entregado Por:</label>
        <input type="text" name="entrega">

        <button type="submit" class="btn">Añadir Técnico</button>
    </form>
</div>

<script>
function toggleEPPForm() {
    const form = document.getElementById('add-epp-form');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block'; // Mostrar el formulario
    } else {
        form.style.display = 'none'; // Ocultar el formulario
    }
}
</script>



<!-- Mostrar los Técnicos -->
<h2>Lista de EPP1</h2>
<div class="table-container">
    <table>
        <thead>
            <tr>  
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Fecha de Entrega</th>
            <th>Calzado de Can</th>
            <th>Calzado Talla</th>
            <th>Maletín Masivo</th>
            <th>Maletín Motorizado</th>
            <th>Gorra</th>
            <th>Bolsa</th>
            <th>BSI</th>
            <th>Vanti</th>
            <th>Sura</th>
            <th>Portacarnet</th>
            <th>Entregado Por</th>
            <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            <?php if ($epp2): ?>
                <?php foreach ($epp2 as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['CEDULA']); ?></td>
                        <td><?php echo htmlspecialchars($item['NOMBRE']); ?></td>
                        <td><?php echo htmlspecialchars($item['FECHA_ENTREGA']); ?></td>
                        <td><?php echo htmlspecialchars($item['CAL_CAN']); ?></td>
                        <td><?php echo htmlspecialchars($item['CAL_TALLA']); ?></td>
                        <td><?php echo htmlspecialchars($item['MAL_MAS']); ?></td>
                        <td><?php echo htmlspecialchars($item['MAL_MOTO']); ?></td>
                        <td><?php echo htmlspecialchars($item['GORRA']); ?></td>
                        <td><?php echo htmlspecialchars($item['BOLSA']); ?></td>
                        <td><?php echo htmlspecialchars($item['BSI']); ?></td>
                        <td><?php echo htmlspecialchars($item['VANTI']); ?></td>
                        <td><?php echo htmlspecialchars($item['SURA']); ?></td>
                        <td><?php echo htmlspecialchars($item['PORTACARNET']); ?></td>
                        <td><?php echo htmlspecialchars($item['ENTREGA']); ?></td>

                        <td>
                            <a href="?edit=<?php echo urlencode($item['ID_EPP']); ?>" class="btn1">Editar</a>
                            <a href="?delete=<?php echo urlencode($item['ID_EPP']); ?>" class="btn1" onclick="return confirmDelete();">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No se encontró personal.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


</body>
</html>
