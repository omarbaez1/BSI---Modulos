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

// Eliminar un descuento
if (isset($_GET['delete_id'])) {
    $sql = "DELETE FROM descuentos_herramientas WHERE ID_DESCUENTO = :ID_DESCUENTO";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ID_DESCUENTO' => $_GET['delete_id']]);
    header('Location: descuento_herramientas.php'); // Redirigir para evitar reenvío de formularios
    exit();
}

// Lógica de búsqueda
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM descuentos_herramientas WHERE COD_HERRAMIENTA LIKE :search OR CEDULA_TEC LIKE :search OR NOMBRE_COMPLETO LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => '%' . $search . '%']);
    $descuentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Obtener todos los descuentos si no hay búsqueda
    $sql = "SELECT * FROM descuentos_herramientas";
    $stmt = $pdo->query($sql);
    $descuentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Descuentos de Herramientas</title>
    <link rel="stylesheet" href="css/estilo_u.css">

    
    
</head>
<body>
    
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">DESCUENTO HERRAMIENTA</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/dhh.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
    <button class="toggle-button" onclick="window.location.href='../../inicio_sesion/almacen.php';">Página Principal</button>

    <h1>Lista de Descuentos de Herramientas</h1>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="descuento_herramientas.php">
        <input type="text" name="search" placeholder="Buscar por Código de Herramienta, Cédula Técnico o Nombre Completo" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Buscar"><br>

    </form>

    <a href="editar_descuento.php">Añadir Nuevo Descuento</a>

    <table border="1">
        <thead>
            <tr>
                <th>Código de Herramienta</th>
                <th>Herramienta</th>
                <th>Cédula Técnico</th>
                <th>Nombre Completo</th>
                <th>Tipo Evento</th>
                <th>Fecha Evento</th>
                <th>Valor Descuento</th>
                <th>Observación</th>
                <th>ID Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($descuentos)): ?>
                <?php foreach ($descuentos as $descuento): ?>
                <tr>
                    <td><?php echo htmlspecialchars($descuento['COD_HERRAMIENTA']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['HERRAMIENTA']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['CEDULA_TEC']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['NOMBRE_COMPLETO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['TIPO_EVENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['FECHA_EVENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['VALOR_DESCUENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['OBSERVACION']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['ID_USUARIO']); ?></td>
                    <td>
                        <a href="editar_descuento.php?id=<?php echo htmlspecialchars($descuento['ID_DESCUENTO']); ?>">Editar</a><br>
                        <a href="descuento_herramientas.php?delete_id=<?php echo htmlspecialchars($descuento['ID_DESCUENTO']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este descuento?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">No se encontraron descuentos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
