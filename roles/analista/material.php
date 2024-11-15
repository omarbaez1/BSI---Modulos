<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Materiales</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #333;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        
        input[type="submit"], .button {
            background-color: #007acc;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
        }
        input[type="submit"]:hover, .button:hover {
            background-color: #007acc;
            border-radius: 8px;
        }
        
      
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">MATERIALES</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/precinto.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/analista.php';">Página Principal</button>

    <h2>Buscar Material</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codMaterial">Código de Material:</label>
        <input type="text" name="codMaterial" id="codMaterial">

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br>
        <br>
        <br><input type="submit" value="Buscar"> <br>
        

    </form>

     <!-- Botón para abrir el modal de añadir nuevo material -->
     <br><button id="abrirModal" class="button">Añadir Material</button>

<!-- Modal para añadir y editar material -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Añadir Material</h2>
        <form method="POST" action="">
            <label for="codMaterial">Código de Material:</label>
            <input type="text" name="codMaterial" id="codMaterialInput" required>

            <label for="nombreMaterial">Nombre del Material:</label>
            <input type="text" name="nombreMaterial" id="nombreMaterialInput" required>

            <label for="consecInicial">Consecutivo Inicial:</label>
            <input type="text" name="consecInicial" id="consecInicialInput" required>

            <label for="consecFinal">Consecutivo Final:</label>
            <input type="text" name="consecFinal" id="consecFinalInput" required>

            <label for="cedulaTec">Cédula Técnico:</label>
            <input type="text" name="cedulaTec" id="cedulaTecInput" required>

            <label for="nombreCompleto">Nombre Completo:</label>
            <input type="text" name="nombreCompleto" id="nombreCompletoInput" required>

            <label for="total">Total:</label>
            <input type="text" name="total" id="totalInput" required>

            <label for="observacion">Observación:</label>
            <input type="text" name="observacion" id="observacionInput">

            <input type="submit" id="modal-submit" name="agregar" value="Añadir Material">
        </form>
    </div>
</div>


    <h2>Resultados de la Búsqueda</h2>

    <?php
    // Conexión a la base de datos
    function conexion() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            die();
        }
    }

    // Función para buscar material por código de material y cédula del técnico
    function buscar_material($codMaterial = null, $cedulaTec = null) {
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM material WHERE 1=1";
        
        if (!empty($codMaterial)) {
            $sql .= " AND COD_MATERIAL = :codMaterial";
        }
        
        if (!empty($cedulaTec)) {
            $sql .= " AND CEDULA_TEC = :cedulaTec";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($codMaterial)) {
            $stmt->bindParam(':codMaterial', $codMaterial);
        }
        
        if (!empty($cedulaTec)) {
            $stmt->bindParam(':cedulaTec', $cedulaTec);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para eliminar material
    function eliminar_material($codMaterial) {
        $pdo = conexion();
        $sql = "DELETE FROM material WHERE COD_MATERIAL = :codMaterial";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codMaterial', $codMaterial);
        $stmt->execute();
    }

    // Función para editar material
    function editar_material($codMaterial, $nombreMaterial, $consecInicial, $consecFinal, $cedulaTec, $nombreCompleto, $total, $observacion) {
        $pdo = conexion();
        $sql = "UPDATE material SET NOMBRE_MATERIAL = :nombreMaterial, CONSECUTIVO_INICIAL = :consecInicial, 
                CONSECUTIVO_FINAL = :consecFinal, CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, 
                TOTAL = :total, OBSERVACION = :observacion WHERE COD_MATERIAL = :codMaterial";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codMaterial', $codMaterial);
        $stmt->bindParam(':nombreMaterial', $nombreMaterial);
        $stmt->bindParam(':consecInicial', $consecInicial);
        $stmt->bindParam(':consecFinal', $consecFinal);
        $stmt->bindParam(':cedulaTec', $cedulaTec);
        $stmt->bindParam(':nombreCompleto', $nombreCompleto);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->execute();
    }

    // Función para añadir nuevo material
    function agregar_material($codMaterial, $nombreMaterial, $consecInicial, $consecFinal, $cedulaTec, $nombreCompleto, $total, $observacion) {
        $pdo = conexion();
        $sql = "INSERT INTO material (COD_MATERIAL, NOMBRE_MATERIAL, CONSECUTIVO_INICIAL, CONSECUTIVO_FINAL, 
                CEDULA_TEC, NOMBRE_COMPLETO, TOTAL, OBSERVACION) 
                VALUES (:codMaterial, :nombreMaterial, :consecInicial, :consecFinal, :cedulaTec, :nombreCompleto, :total, :observacion)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codMaterial', $codMaterial);
        $stmt->bindParam(':nombreMaterial', $nombreMaterial);
        $stmt->bindParam(':consecInicial', $consecInicial);
        $stmt->bindParam(':consecFinal', $consecFinal);
        $stmt->bindParam(':cedulaTec', $cedulaTec);
        $stmt->bindParam(':nombreCompleto', $nombreCompleto);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->execute();
    }

    // Procesar eliminación
    if (isset($_POST['eliminar'])) {
        eliminar_material($_POST['codMaterial']);
    }

    // Procesar edición
    if (isset($_POST['editar'])) {
        editar_material(
            $_POST['codMaterial'],
            $_POST['nombreMaterial'],
            $_POST['consecInicial'],
            $_POST['consecFinal'],
            $_POST['cedulaTec'],
            $_POST['nombreCompleto'],
            $_POST['total'],
            $_POST['observacion']
        );
    }

    // Procesar nuevo material
    if (isset($_POST['agregar'])) {
        agregar_material(
            $_POST['codMaterial'],
            $_POST['nombreMaterial'],
            $_POST['consecInicial'],
            $_POST['consecFinal'],
            $_POST['cedulaTec'],
            $_POST['nombreCompleto'],
            $_POST['total'],
            $_POST['observacion']
        );
    }

    // Obtener valores del formulario de búsqueda
    $codMaterial = isset($_GET['codMaterial']) ? $_GET['codMaterial'] : null;
    $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;

    // Buscar y mostrar los resultados
    $materiales = buscar_material($codMaterial, $cedulaTec);
    ?>

    <?php if (!empty($materiales)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código de Material</th>
                        <th>Nombre del Material</th>
                        <th>Consecutivo Inicial</th>
                        <th>Consecutivo Final</th>
                        <th>Cédula Técnico</th>
                        <th>Nombre Completo</th>
                        <th>Total</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiales as $material): ?>
                        <tr>
                            <td><?php echo $material['COD_MATERIAL']; ?></td>
                            <td><?php echo $material['NOMBRE_MATERIAL']; ?></td>
                            <td><?php echo $material['CONSECUTIVO_INICIAL']; ?></td>
                            <td><?php echo $material['CONSECUTIVO_FINAL']; ?></td>
                            <td><?php echo $material['CEDULA_TEC']; ?></td>
                            <td><?php echo $material['NOMBRE_COMPLETO']; ?></td>
                            <td><?php echo $material['TOTAL']; ?></td>
                            <td><?php echo $material['OBSERVACION']; ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

   

    <script>
        // Abrir modal para añadir material
        var modal = document.getElementById("modal");
        var abrirModal = document.getElementById("abrirModal");
        var cerrarModal = document.getElementsByClassName("close")[0];

        abrirModal.onclick = function() {
            document.getElementById("modal-title").innerText = "Añadir Material";
            document.getElementById("modal-submit").name = "agregar";
            modal.style.display = "block";
        }

        cerrarModal.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Función para editar material
        function editarMaterial(codMaterial, nombreMaterial, consecInicial, consecFinal, cedulaTec, nombreCompleto, total, observacion) {
            document.getElementById("modal-title").innerText = "Editar Material";
            document.getElementById("modal-submit").name = "editar";
            document.getElementById("codMaterialInput").value = codMaterial;
            document.getElementById("nombreMaterialInput").value = nombreMaterial;
            document.getElementById("consecInicialInput").value = consecInicial;
            document.getElementById("consecFinalInput").value = consecFinal;
            document.getElementById("cedulaTecInput").value = cedulaTec;
            document.getElementById("nombreCompletoInput").value = nombreCompleto;
            document.getElementById("totalInput").value = total;
            document.getElementById("observacionInput").value = observacion;

            modal.style.display = "block";
        }
    </script>

</body>
</html>