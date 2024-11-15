<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Llaves de Cepo</title>
    <link rel="stylesheet" href="../css/estilos_materiales.css">
    <style>
        /* Estilo para el slider */
        #nuevoRegistro {
            display: none;
            margin-top: 20px;
        }
        #toggleForm {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Buscar Llaves de Cepo</h2>

        <!-- Formulario de Búsqueda -->
        <form method="GET" action="">
            <div class="form-group">
                <label for="codLlave">Código de la Llave:</label>
                <input type="text" name="codLlave" id="codLlave" value="<?php echo isset($_GET['codLlave']) ? htmlspecialchars($_GET['codLlave']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="cedulaTec">Cédula Técnico:</label>
                <input type="text" name="cedulaTec" id="cedulaTec" value="<?php echo isset($_GET['cedulaTec']) ? htmlspecialchars($_GET['cedulaTec']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="nombreCompleto">Nombre Completo:</label>
                <input type="text" name="nombreCompleto" id="nombreCompleto" value="<?php echo isset($_GET['nombreCompleto']) ? htmlspecialchars($_GET['nombreCompleto']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option value="">Todos</option>
                    <option value="Asignada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'Asignada') ? 'selected' : ''; ?>>Asignada</option>
                    <option value="En Mal Estado" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'En Mal Estado') ? 'selected' : ''; ?>>En Mal Estado</option>
                    <option value="Extraviada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'Extraviada') ? 'selected' : ''; ?>>Extraviada</option>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" value="Buscar">
            </div>
        </form>

        <h2>Resultados de la Búsqueda</h2>

        <div class="table-container">
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

            // Función para buscar llaves de cepo
            function buscar_llaves_de_cepo($codLlave = null, $cedulaTec = null, $nombreCompleto = null, $estado = null){
                $pdo = conexion();
                $sql = "SELECT * FROM llaves_de_cepo WHERE 1=1";
                
                if (!empty($codLlave)) {
                    $sql .= " AND COD_LLAVE = :codLlave";
                }
                
                if (!empty($cedulaTec)) {
                    $sql .= " AND CEDULA_TEC = :cedulaTec";
                }

                if (!empty($nombreCompleto)) {
                    $sql .= " AND NOMBRE_COMPLETO LIKE :nombreCompleto";
                }

                if (!empty($estado)) {
                    $sql .= " AND ESTADO_LLAVE = :estado";
                }

                $stmt = $pdo->prepare($sql);
                
                if (!empty($codLlave)) {
                    $stmt->bindParam(':codLlave', $codLlave);
                }
                
                if (!empty($cedulaTec)) {
                    $stmt->bindParam(':cedulaTec', $cedulaTec);
                }

                if (!empty($nombreCompleto)) {
                    $nombreCompleto = "%$nombreCompleto%";
                    $stmt->bindParam(':nombreCompleto', $nombreCompleto);
                }

                if (!empty($estado)) {
                    $stmt->bindParam(':estado', $estado);
                }

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Obtener valores del formulario
            $codLlave = isset($_GET['codLlave']) ? $_GET['codLlave'] : null;
            $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
            $nombreCompleto = isset($_GET['nombreCompleto']) ? $_GET['nombreCompleto'] : null;
            $estado = isset($_GET['estado']) ? $_GET['estado'] : null;

            // Buscar y mostrar los resultados
            $llaves = buscar_llaves_de_cepo($codLlave, $cedulaTec, $nombreCompleto, $estado);

            if (!empty($llaves)) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código de Llave</th>';
                echo '<th>Cédula Técnico</th>';
                echo '<th>Nombre Completo</th>';
                echo '<th>ID SAP</th>';
                echo '<th>Estado</th>';
                echo '<th>Fecha Estado</th>';
                echo '<th>Observación</th>';
                echo '<th>Ubicación Actual</th>';
                echo '<th>Fecha Asignación</th>';
                echo '<th>2ª Observación</th>';                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($llaves as $llave) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($llave['COD_LLAVE']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['CEDULA_TEC']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['NOMBRE_COMPLETO']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['ID_SAP']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['ESTADO_LLAVE']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['FECHA_ESTADO']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['OBSERVACION']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['UBI_ACT_LLAVE']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['FECHA_ASIGNACION']) . '</td>';
                    echo '<td>' . htmlspecialchars($llave['2_OBSERVACION']) . '</td>';
                    echo '<td>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo 'No se encontraron resultados.';
            }
            ?>
        </div>

        

    </div>

    <script>
        document.getElementById('toggleForm').addEventListener('click', function() {
            var form = document.getElementById('nuevoRegistro');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                this.textContent = 'Ocultar Formulario';
            } else {
                form.style.display = 'none';
                this.textContent = 'Añadir Nueva Llave de Cepo';
            }
        });
    </script>

</body>
</html>
