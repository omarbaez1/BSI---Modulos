<?php
// herramienta.php

// Iniciar la sesión para manejar mensajes de éxito o error
session_start();

// Función para conectar a la base de datos
function conexion() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Manejo de errores de conexión
        die('Error de conexión: ' . $e->getMessage());
    }
}

// Manejar la eliminación de una herramienta
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codHerramienta'])) {
    $codHerramienta = $_GET['codHerramienta'];
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("DELETE FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->execute();
        $_SESSION['message'] = "Herramienta eliminada exitosamente.";
        $_SESSION['msg_type'] = "success";
        header("Location: herramienta.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al eliminar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("Location: herramienta.php");
        exit();
    }
}

// Manejar la adición de una nueva herramienta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Obtener y sanitizar los datos del formulario
    $codHerramientaAdd = trim($_POST['codHerramientaAdd']);
    $herramientaAdd = trim($_POST['herramientaAdd']);
    $buenoEstadoAdd = intval($_POST['buenoEstadoAdd']);
    $estadoRegularAdd = intval($_POST['estadoRegularAdd']);
    $malEstadoAdd = intval($_POST['malEstadoAdd']);
    $totalAlmacenAdd = intval($_POST['totalAlmacenAdd']);
    $cantidadAsignadaAdd = intval($_POST['cantidadAsignadaAdd']);
    $existenciaBsiAdd = intval($_POST['existenciaBsiAdd']);

    try {
        $pdo = conexion();
        // Verificar si el código de herramienta ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramientaAdd);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['message'] = "El código de herramienta ya existe.";
            $_SESSION['msg_type'] = "warning";
        } else {
            // Insertar la nueva herramienta
            $stmt = $pdo->prepare("INSERT INTO `herramienta` (`COD_HERRAMIENTA`, `HERRAMIENTA`, `BUEN_ESTADO`, `ESTADO_REGULAR`, `MAL_ESTADO`, `TOTAL_ALMACEN`, `CANTIDAD_ASIGNADA`, `EXISTENCIA_BSI`) VALUES (:codHerramienta, :herramienta, :buenoEstado, :estadoRegular, :malEstado, :totalAlmacen, :cantidadAsignada, :existenciaBsi)");
            $stmt->bindParam(':codHerramienta', $codHerramientaAdd);
            $stmt->bindParam(':herramienta', $herramientaAdd);
            $stmt->bindParam(':buenoEstado', $buenoEstadoAdd);
            $stmt->bindParam(':estadoRegular', $estadoRegularAdd);
            $stmt->bindParam(':malEstado', $malEstadoAdd);
            $stmt->bindParam(':totalAlmacen', $totalAlmacenAdd);
            $stmt->bindParam(':cantidadAsignada', $cantidadAsignadaAdd);
            $stmt->bindParam(':existenciaBsi', $existenciaBsiAdd);
            $stmt->execute();
            $_SESSION['message'] = "Herramienta agregada exitosamente.";
            $_SESSION['msg_type'] = "success";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al agregar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: herramienta.php");
    exit();
}

// Manejar la edición de una herramienta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    // Obtener y sanitizar los datos del formulario de edición
    $codHerramienta = trim($_POST['codHerramienta']);
    $herramienta = trim($_POST['herramienta']);
    $buenoEstado = intval($_POST['buenoEstado']);
    $estadoRegular = intval($_POST['estadoRegular']);
    $malEstado = intval($_POST['malEstado']);
    $totalAlmacen = intval($_POST['totalAlmacen']);
    $cantidadAsignada = intval($_POST['cantidadAsignada']);
    $existenciaBsi = intval($_POST['existenciaBsi']);

    try {
        $pdo = conexion();
        // Actualizar la herramienta
        $stmt = $pdo->prepare("UPDATE `herramienta` SET `HERRAMIENTA` = :herramienta, `BUEN_ESTADO` = :buenoEstado, `ESTADO_REGULAR` = :estadoRegular, `MAL_ESTADO` = :malEstado, `TOTAL_ALMACEN` = :totalAlmacen, `CANTIDAD_ASIGNADA` = :cantidadAsignada, `EXISTENCIA_BSI` = :existenciaBsi WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->bindParam(':herramienta', $herramienta);
        $stmt->bindParam(':buenoEstado', $buenoEstado);
        $stmt->bindParam(':estadoRegular', $estadoRegular);
        $stmt->bindParam(':malEstado', $malEstado);
        $stmt->bindParam(':totalAlmacen', $totalAlmacen);
        $stmt->bindParam(':cantidadAsignada', $cantidadAsignada);
        $stmt->bindParam(':existenciaBsi', $existenciaBsi);
        $stmt->execute();
        $_SESSION['message'] = "Herramienta actualizada exitosamente.";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al actualizar la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: herramienta.php");
    exit();
}

// Obtener el formulario de edición si se solicita
$editHerramienta = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codHerramienta'])) {
    $codHerramienta = $_GET['codHerramienta'];
    try {
        $pdo = conexion();
        $stmt = $pdo->prepare("SELECT * FROM `herramienta` WHERE `COD_HERRAMIENTA` = :codHerramienta");
        $stmt->bindParam(':codHerramienta', $codHerramienta);
        $stmt->execute();
        $editHerramienta = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$editHerramienta) {
            $_SESSION['message'] = "Herramienta no encontrada.";
            $_SESSION['msg_type'] = "warning";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al obtener la herramienta: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Herramientas</title>
    <link rel="stylesheet" href="../css/estilos_materiales.css">
    <style>
        /* Estilos para el slide del formulario de añadir */
        .form-slide, .form-edit {
            display: none;
            margin: 20px 0;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .form-slide.active, .form-edit.active {
            display: block;
        }
        .btn-slide, .btn-close-edit {
            cursor: pointer;
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        .btn-slide:hover, .btn-close-edit:hover {
            background-color: #0056b3;
        }
        .btn {
            padding: 10px 15px;
            background-color: #28A745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
        .message.warning {
            background-color: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEEBA;
        }
        .message.danger {
            background-color: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Gestión de Herramientas</h2>

        <!-- Mostrar mensajes de sesión -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['msg_type']; ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['msg_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de Búsqueda -->
        <form method="GET" action="herramienta.php">
            <div class="form-group">
                <label for="codHerramienta">Código de Herramienta:</label>
                <input type="text" name="codHerramienta" id="codHerramienta" class="input-field" value="<?php echo isset($_GET['codHerramienta']) ? htmlspecialchars($_GET['codHerramienta']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="herramienta">Nombre de la Herramienta:</label>
                <input type="text" name="herramienta" id="herramienta" class="input-field" value="<?php echo isset($_GET['herramienta']) ? htmlspecialchars($_GET['herramienta']) : ''; ?>">
            </div>

            <input type="submit" value="Buscar" class="btn">
            <a href="herramienta.php" class="btn" style="background-color: #6C757D; margin-left: 10px;">Limpiar</a>
        </form>

        

        <!-- Formulario de Edición (solo se muestra si se está editando) -->
        <?php if ($editHerramienta): ?>
            <div id="formEdit" class="form-edit active">
                <h3>Editar Herramienta</h3>
                <form method="POST" action="herramienta.php">
                    <input type="hidden" name="action" value="edit">

                    <label for="codHerramienta">Código de Herramienta:</label>
                    <input type="text" name="codHerramienta" id="codHerramienta" value="<?php echo htmlspecialchars($editHerramienta['COD_HERRAMIENTA']); ?>" readonly><br><br>

                    <label for="herramienta">Nombre de la Herramienta:</label>
                    <input type="text" name="herramienta" id="herramienta" value="<?php echo htmlspecialchars($editHerramienta['HERRAMIENTA']); ?>" required><br><br>

                    <label for="buenoEstado">Buen Estado:</label>
                    <input type="number" name="buenoEstado" id="buenoEstado" min="0" value="<?php echo htmlspecialchars($editHerramienta['BUEN_ESTADO']); ?>" required><br><br>

                    <label for="estadoRegular">Estado Regular:</label>
                    <input type="number" name="estadoRegular" id="estadoRegular" min="0" value="<?php echo htmlspecialchars($editHerramienta['ESTADO_REGULAR']); ?>" required><br><br>

                    <label for="malEstado">Mal Estado:</label>
                    <input type="number" name="malEstado" id="malEstado" min="0" value="<?php echo htmlspecialchars($editHerramienta['MAL_ESTADO']); ?>" required><br><br>

                    <label for="totalAlmacen">Total Almacén:</label>
                    <input type="number" name="totalAlmacen" id="totalAlmacen" min="0" value="<?php echo htmlspecialchars($editHerramienta['TOTAL_ALMACEN']); ?>" required><br><br>

                    <label for="cantidadAsignada">Cantidad Asignada:</label>
                    <input type="number" name="cantidadAsignada" id="cantidadAsignada" min="0" value="<?php echo htmlspecialchars($editHerramienta['CANTIDAD_ASIGNADA']); ?>" required><br><br>

                    <label for="existenciaBsi">Existencia BSI:</label>
                    <input type="number" name="existenciaBsi" id="existenciaBsi" min="0" value="<?php echo htmlspecialchars($editHerramienta['EXISTENCIA_BSI']); ?>" required><br><br>

                    <input type="submit" value="Actualizar Herramienta" class="btn">
                    <button type="button" class="btn-close-edit" onclick="hideEditForm()">Cancelar</button>
                </form>
            </div>
        <?php endif; ?>

        <h2>Resultados de la Búsqueda</h2>

        <div class="table-container">
            <?php
            // Función para buscar herramientas por código y nombre
            function buscar_herramienta($codHerramienta = null, $herramienta = null) {
                $pdo = conexion();

                // Construir la consulta SQL con filtros opcionales
                $sql = "SELECT * FROM `herramienta` WHERE 1=1";
                
                if (!empty($codHerramienta)) {
                    $sql .= " AND `COD_HERRAMIENTA` = :codHerramienta";
                }
                
                if (!empty($herramienta)) {
                    $sql .= " AND `HERRAMIENTA` LIKE :herramienta";
                }

                $stmt = $pdo->prepare($sql);
                
                // Vincular los parámetros si existen
                if (!empty($codHerramienta)) {
                    $stmt->bindParam(':codHerramienta', $codHerramienta);
                }
                
                if (!empty($herramienta)) {
                    // Usar % para la búsqueda parcial en el nombre de la herramienta
                    $herramienta = "%$herramienta%";
                    $stmt->bindParam(':herramienta', $herramienta);
                }

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Obtener valores del formulario de búsqueda
            $codHerramienta = isset($_GET['codHerramienta']) ? $_GET['codHerramienta'] : null;
            $herramienta = isset($_GET['herramienta']) ? $_GET['herramienta'] : null;

            // Buscar y obtener los resultados
            $herramientas = buscar_herramienta($codHerramienta, $herramienta);

            if (!empty($herramientas)) {
                echo '<table border="1" cellpadding="10">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código de Herramienta</th>';
                echo '<th>Nombre de la Herramienta</th>';
                echo '<th>Buen Estado</th>';
                echo '<th>Estado Regular</th>';
                echo '<th>Mal Estado</th>';
                echo '<th>Total Almacén</th>';
                echo '<th>Cantidad Asignada</th>';
                echo '<th>Existencia BSI</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($herramientas as $herr) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($herr['COD_HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['HERRAMIENTA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['BUEN_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['ESTADO_REGULAR']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['MAL_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['TOTAL_ALMACEN']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['CANTIDAD_ASIGNADA']) . '</td>';
                    echo '<td>' . htmlspecialchars($herr['EXISTENCIA_BSI']) . '</td>';
                    echo '<td>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No se encontraron resultados.</p>';
            }
            ?>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar el formulario de añadir
        function toggleSlide() {
            var formSlide = document.getElementById('formSlide');
            formSlide.classList.toggle('active');
        }

        // Función para ocultar el formulario de edición
        function hideEditForm() {
            var formEdit = document.getElementById('formEdit');
            if (formEdit) {
                formEdit.classList.remove('active');
                // Redireccionar sin los parámetros de edición
                window.location.href = 'herramienta.php';
            }
        }
    </script>

</body>
</html>
