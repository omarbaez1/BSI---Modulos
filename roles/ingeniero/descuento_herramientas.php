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
    <link rel="stylesheet" href="../css/estilos_materiales.css">
</head>
<body>
    <h1>Lista de Descuentos de Herramientas</h1>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="descuento_herramientas.php">
        <input type="text" name="search" placeholder="Buscar por Código de Herramienta, Cédula Técnico o Nombre Completo" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Buscar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID Descuento</th>
                <th>Código de Herramienta</th>
                <th>Cédula Técnico</th>
                <th>Nombre Completo</th>
                <th>Tipo Evento</th>
                <th>Fecha Evento</th>
                <th>Valor Descuento</th>
                <th>Observación</th>
                <th>Herramienta</th>
                <th>ID Usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($descuentos)): ?>
                <?php foreach ($descuentos as $descuento): ?>
                <tr>
                    <td><?php echo htmlspecialchars($descuento['ID_DESCUENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['COD_HERRAMIENTA']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['CEDULA_TEC']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['NOMBRE_COMPLETO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['TIPO_EVENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['FECHA_EVENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['VALOR_DESCUENTO']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['OBSERVACION']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['HERRAMIENTA']); ?></td>
                    <td><?php echo htmlspecialchars($descuento['ID_USUARIO']); ?></td>
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
