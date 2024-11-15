<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Técnicos</title>
    <link rel="stylesheet" href="../css/estilos_tecnicos.css">
</head>
<body>

    <h2>Buscar Técnicos</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="estadoEmpresa">Estado en la Empresa:</label>
        <select name="estadoEmpresa" id="estadoEmpresa">
            <option value="">--Seleccione un Estado--</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
            <option value="suspendido">Suspendido</option>
            <option value="retirado">Retirado</option>
        </select><br><br>

        <label for="cargo">Cargo:</label>
        <input type="text" name="cargo" id="cargo"><br><br>

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <?php
    // Conexión a la base de datos
    function conexion(){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            die();
        }
    }

    // Función para buscar técnicos por cédula, estado en la empresa y cargo
    function buscar_tecnicos($cedulaTec = null, $estadoEmpresa = null, $cargo = null){
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM `tecnicos` WHERE 1=1";
        
        if (!empty($cedulaTec)) {
            $sql .= " AND `CEDULA_TEC` = :cedulaTec";
        }
        
        if (!empty($estadoEmpresa)) {
            $sql .= " AND `ESTADO_EN_LA_EMPRESA` = :estadoEmpresa";
        }
        
        if (!empty($cargo)) {
            $sql .= " AND `CARGO` = :cargo";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($cedulaTec)) {
            $stmt->bindParam(':cedulaTec', $cedulaTec);
        }
        
        if (!empty($estadoEmpresa)) {
            $stmt->bindParam(':estadoEmpresa', $estadoEmpresa);
        }
        
        if (!empty($cargo)) {
            $stmt->bindParam(':cargo', $cargo);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener valores del formulario
    $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
    $estadoEmpresa = isset($_GET['estadoEmpresa']) ? $_GET['estadoEmpresa'] : null;
    $cargo = isset($_GET['cargo']) ? $_GET['cargo'] : null;

    // Buscar y mostrar los resultados
    $tecnicos = buscar_tecnicos($cedulaTec, $estadoEmpresa, $cargo);
    ?>

    <?php if (!empty($tecnicos)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Cédula Técnico</th>
                        <th>Nombre Completo</th>
                        <th>ID SAP</th>
                        <th>Cargo</th>
                        <th>Fecha Ingreso</th>
                        <th>Estado en la Empresa</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tecnicos as $tecnico): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tecnico['CEDULA_TEC']); ?></td>
                            <td><?php echo htmlspecialchars($tecnico['NOMBRE_COMPLETO']); ?></td>
                            <td><?php echo htmlspecialchars($tecnico['ID_SAP']); ?></td>
                            <td><?php echo htmlspecialchars($tecnico['CARGO']); ?></td>
                            <td><?php echo htmlspecialchars($tecnico['FECHA_INGRESO']); ?></td>
                            <td><?php echo htmlspecialchars($tecnico['ESTADO_EN_LA_EMPRESA']); ?></td>
                            <td>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($tecnico['FOTO']); ?>" alt="Foto Técnico" height="100">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

</body>
</html>
