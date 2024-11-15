<?php
// Almacenar errores
$error_message = '';


// Conexión a la base de datos
function conexion(&$error_message)
{
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        $error_message = 'Error de conexión a la base de datos.';
        return null;
    }
}

// Añadir nuevo cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['idCambioEditar'])) {
    $idCambioNuevo = $_POST['idCambioNuevo'];
    $cedulaTecNuevo = $_POST['cedulaTecNuevo'];
    $nombreCompletoNuevo = $_POST['nombreCompletoNuevo'];
    $codHerramientaNuevo = $_POST['codHerramientaNuevo'];
    $tipoCambioNuevo = $_POST['tipoCambioNuevo'];
    $fechaCambioNuevo = $_POST['fechaCambioNuevo'];
    $observacionNuevo = $_POST['observacionNuevo'];

    $pdo = conexion($error_message);
    if ($pdo) {
        try {
            // Verificar si ya existe un cambio con el mismo código
            $sqlCheck = "SELECT COUNT(*) FROM cambios_herramientas WHERE COD_HERRAMIENTA = :codHerramienta";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([':codHerramienta' => $codHerramientaNuevo]);
            $count = $stmtCheck->fetchColumn();

            if ($count > 0) {
                $error_intro = "Este cambio de herramienta no se puede agregar, hay dos posibles razones:";
                $error_detail = "1. Debido a que el código redactado ya existe, no se puede duplicar un código de cambio de herramienta.";
                $error_detail2 = "2. Debido a que la cedula registrada no existe en nuestra tabla de Tecnicos.";

                // Juntarlos para mostrar
                $error_message = $error_intro . "\n\n" . $error_detail. "\n\n" . $error_detail2;
                echo '<div style="color: red; background-color: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin: 20px 0;">' . nl2br($error_message) . '</div>';




            } else {
                $sql = "INSERT INTO cambios_herramientas (ID_CAMBIO, CEDULA_TEC, NOMBRE_COMPLETO, COD_HERRAMIENTA, TIPO_CAMBIO, FECHA_CAMBIO, OBSERVACION) VALUES (:idCambio, :cedulaTec, :nombreCompleto, :codHerramienta, :tipoCambio, :fechaCambio, :observacion)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':idCambio' => $idCambioNuevo,
                    ':cedulaTec' => $cedulaTecNuevo,
                    ':nombreCompleto' => $nombreCompletoNuevo,
                    ':codHerramienta' => $codHerramientaNuevo,
                    ':tipoCambio' => $tipoCambioNuevo,
                    ':fechaCambio' => $fechaCambioNuevo,
                    ':observacion' => $observacionNuevo
                ]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } catch (Exception $e) {
            $error_message = 'Error al agregar el cambio.';
        }
    }
}

// Editar cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCambioEditar'])) {
    $idCambioEditar = $_POST['idCambioEditar'];
    $cedulaTecEditar = $_POST['cedulaTecEditar'];
    $nombreCompletoEditar = $_POST['nombreCompletoEditar'];
    $codHerramientaEditar = $_POST['codHerramientaEditar'];
    $tipoCambioEditar = $_POST['tipoCambioEditar'];
    $fechaCambioEditar = $_POST['fechaCambioEditar'];
    $observacionEditar = $_POST['observacionEditar'];

    $pdo = conexion($error_message);
    if ($pdo) {
        try {
            $sql = "UPDATE cambios_herramientas SET CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, COD_HERRAMIENTA = :codHerramienta, TIPO_CAMBIO = :tipoCambio, FECHA_CAMBIO = :fechaCambio, OBSERVACION = :observacion WHERE ID_CAMBIO = :idCambio";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':cedulaTec' => $cedulaTecEditar,
                ':nombreCompleto' => $nombreCompletoEditar,
                ':codHerramienta' => $codHerramientaEditar,
                ':tipoCambio' => $tipoCambioEditar,
                ':fechaCambio' => $fechaCambioEditar,
                ':observacion' => $observacionEditar,
                ':idCambio' => $idCambioEditar
            ]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            $error_message = 'Error al editar el cambio, la cedula no es modificable o el codigo de herramienta no se puede duplicar.';
        }
    }
}

// Eliminar cambio
if (isset($_GET['eliminar'])) {
    $idCambioEliminar = $_GET['eliminar'];
    $pdo = conexion($error_message);
    if ($pdo) {
        try {
            $sql = "DELETE FROM cambios_herramientas WHERE ID_CAMBIO = :idCambio";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':idCambio' => $idCambioEliminar]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            $error_message = 'Error al eliminar el cambio.';
        }
    }
}

// Función para buscar cambios de herramientas
function buscar_cambios($idCambio = null, $codHerramienta = null, $cedulaTec = null, $nombreCompleto = null)
{
    global $error_message;
    $pdo = conexion($error_message);
    if (!$pdo) {
        return [];
    }

    $sql = "SELECT * FROM cambios_herramientas WHERE 1=1";

    if (!empty($idCambio)) {
        $sql .= " AND LOWER(ID_CAMBIO) LIKE LOWER(:idCambio)";
    }
    if (!empty($codHerramienta)) {
        $sql .= " AND LOWER(COD_HERRAMIENTA) LIKE LOWER(:codHerramienta)";
    }
    if (!empty($cedulaTec)) {
        $sql .= " AND LOWER(CEDULA_TEC) LIKE LOWER(:cedulaTec)";
    }
    if (!empty($nombreCompleto)) {
        $sql .= " AND LOWER(NOMBRE_COMPLETO) LIKE LOWER(:nombreCompleto)";
    }

    $stmt = $pdo->prepare($sql);

    if (!empty($idCambio)) {
        $stmt->bindValue(':idCambio', '%' . $idCambio . '%');
    }
    if (!empty($codHerramienta)) {
        $stmt->bindValue(':codHerramienta', '%' . $codHerramienta . '%');
    }
    if (!empty($cedulaTec)) {
        $stmt->bindValue(':cedulaTec', '%' . $cedulaTec . '%');
    }
    if (!empty($nombreCompleto)) {
        $stmt->bindValue(':nombreCompleto', '%' . $nombreCompleto . '%');
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener valores del formulario
$idCambio = isset($_GET['idCambio']) ? $_GET['idCambio'] : null;
$codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
$cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
$nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;

// Mostrar los resultados
$cambios = buscar_cambios($idCambio, $codHerramienta, $cedulaTec, $nombreCompleto);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cambios de Herramientas</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        /* Estilos para la tabla y el contenedor */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
        .slide-container { display: none; margin-top: 20px; }
        .slide-btngc {
            background-color: #007ba7; /* Color del botón */
            border: none;
            color: white;
            padding: 10px 20px; /* Tamaño del botón */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px; /* Tamaño de la fuente */
            margin: 6px 2px;
            cursor: pointer;
            border-radius: 5px; /* Esquinas redondeadas */
            transition: background-color 0.3s; /* Transición suave */   
            position: absolute; /* Posicionamiento absoluto */
            top: 1260px; /* Espaciado desde la parte superior */
            left: 695px; /* Espaciado desde la izquierda */
        }
    </style>
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.33; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0;">CAMBIOS DE HERRAMIENTAS</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/dhh.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>

    
    <br>
    <button class="toggle-button" onclick="window.location.href='../../inicio_sesion/almacen.php';">Página Principal</button>
    <?php if ($error_message): ?>

    <?php endif; ?>

    <h2>CAMBIOS DE HERRAMIENTA</h2>
    <form method="GET" action="">
        <label for="idCambio">ID Cambio:</label>
        <input type="text" name="idCambio" id="idCambio" value="<?= htmlspecialchars($idCambio) ?>">
        <label for="codHerramienta">Código de Herramienta:</label>
        <input type="text" name="codHerramienta" id="codHerramienta" value="<?= htmlspecialchars($codHerramienta) ?>">
        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec" value="<?= htmlspecialchars($cedulaTec) ?>">
        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?= htmlspecialchars($nombreCompleto) ?>">
        <input type="submit" value="Buscar">
    </form>

    <button class="toggle-button" onclick="toggleSlide()">Añadir Nuevo Cambio</button>
    <div id="slide-container" class="slide-container">
        <h2>Añadir Nuevo Cambio</h2>
        <form method="POST" action="">
            <label for="idCambioNuevo">ID Cambio:</label>
            <input type="text" name="idCambioNuevo" id="idCambioNuevo" required>
            <label for="cedulaTecNuevo">Cédula Técnico:</label>
            <input type="text" name="cedulaTecNuevo" id="cedulaTecNuevo" required>
            <label for="nombreCompletoNuevo">Nombre Completo:</label>
            <input type="text" name="nombreCompletoNuevo" id="nombreCompletoNuevo" required>
            <label for="codHerramientaNuevo">Código Herramienta:</label>
            <input type="text" name="codHerramientaNuevo" id="codHerramientaNuevo" required>
            <label for="tipoCambioNuevo">Tipo Cambio:</label>
            <input type="text" name="tipoCambioNuevo" id="tipoCambioNuevo" required>
            <label for="fechaCambioNuevo">Fecha Cambio:</label>
            <input type="date" name="fechaCambioNuevo" id="fechaCambioNuevo" required>
            <label for="observacionNuevo">Observación:</label>
            <textarea name="observacionNuevo" id="observacionNuevo"></textarea>
            <input type="submit" value="Guardar Cambio" class="slide-btngc">
        </form>
    </div>

    <h2>Resultados de la Búsqueda</h2>
    <div class="results">
        <?php if (!empty($cambios)) : ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Cédula Técnico</th>
                            <th>Nombre Completo</th>
                            <th>Código Herramienta</th>
                            <th>Tipo Cambio</th>
                            <th>Fecha Cambio</th>
                            <th>Observación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cambios as $cambio) : ?>
                            <tr>
                                <td><?= htmlspecialchars($cambio['CEDULA_TEC']) ?></td>
                                <td><?= htmlspecialchars($cambio['NOMBRE_COMPLETO']) ?></td>
                                <td><?= htmlspecialchars($cambio['COD_HERRAMIENTA']) ?></td>
                                <td><?= htmlspecialchars($cambio['TIPO_CAMBIO']) ?></td>
                                <td><?= htmlspecialchars($cambio['FECHA_CAMBIO']) ?></td>
                                <td><?= htmlspecialchars($cambio['OBSERVACION']) ?></td>
                                <td>
                                    <a onclick="toggleEdit('<?= $cambio['ID_CAMBIO'] ?>')">Editar</a> | 
                                    <a href="?eliminar=<?= $cambio['ID_CAMBIO'] ?>" onclick="return confirm('¿Estás seguro de eliminar este cambio?')">Eliminar</a>
                                </td>
                            </tr>
                            <tr class="edit-form" data-id-cambio="<?= $cambio['ID_CAMBIO'] ?>" style="display: none;">
                                <td colspan="8">
                                    <form method="POST" action="">
                                        <input type="text" name="cedulaTecEditar" id="cedulaTecEditar" value="<?= htmlspecialchars($cambio['CEDULA_TEC']) ?>" required>
                                        <label for="nombreCompletoEditar">Nombre Completo:</label>
                                        <input type="text" name="nombreCompletoEditar" id="nombreCompletoEditar" value="<?= htmlspecialchars($cambio['NOMBRE_COMPLETO']) ?>" required>
                                        <label for="codHerramientaEditar">Código Herramienta:</label>
                                        <input type="text" name="codHerramientaEditar" id="codHerramientaEditar" value="<?= htmlspecialchars($cambio['COD_HERRAMIENTA']) ?>" required>
                                        <label for="tipoCambioEditar">Tipo Cambio:</label>
                                        <input type="text" name="tipoCambioEditar" id="tipoCambioEditar" value="<?= htmlspecialchars($cambio['TIPO_CAMBIO']) ?>" required>
                                        <label for="fechaCambioEditar">Fecha Cambio:</label>
                                        <input type="date" name="fechaCambioEditar" id="fechaCambioEditar" value="<?= htmlspecialchars($cambio['FECHA_CAMBIO']) ?>" required>
                                        <label for="observacionEditar">Observación:</label>
                                        <textarea name="observacionEditar" id="observacionEditar"><?= htmlspecialchars($cambio['OBSERVACION']) ?></textarea>
                                        <input type="submit" name="guardarCambio" value="Guardar Cambios">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p>No se encontraron cambios.</p>
        <?php endif; ?>
    </div>
    <script>
        function toggleSlide() {
            var slide = document.getElementById("slide-container");
            slide.style.display = (slide.style.display === "none") ? "block" : "none";
        }

        function toggleEdit(idCambio) {
            var forms = document.getElementsByClassName("edit-form");
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = (forms[i].dataset.idCambio === idCambio) ? "block" : "none";
            }
        }
    </script>
</body>
</html>
