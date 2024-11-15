<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Llaves de Cepo</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        /* Estilo para el slider */
        #nuevoRegistro {
            display: none;
            margin-top: 20px;
        }
        #toggleForm {
            display: inline-block;

        }
    </style>
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">LLAVE DE CEPO</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/cepo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/analista.php';">Página Principal</button>

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
                <!-- Botón para mostrar el formulario de añadir nuevo registro -->
                <div>
                <br>

            <button class="toggle-button" onclick="toggleForm()">Añadir Llave de Cepo</button>

            
        </div>
        
        
        <!-- Formulario para añadir nuevo registro -->
        <div id="nuevoRegistro">
            <h2>Añadir Llave de Cepo</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="codLlave">Código de la Llave:</label>
                    <input type="text" name="codLlave" id="codLlave" required value="<?php echo isset($_GET['codLlave']) ? htmlspecialchars($_GET['codLlave']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="cedulaTec">Cédula Técnico:</label>
                    <input type="text" name="cedulaTec" id="cedulaTec" required value="<?php echo isset($_GET['cedulaTec']) ? htmlspecialchars($_GET['cedulaTec']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="nombreCompleto">Nombre Completo:</label>
                    <input type="text" name="nombreCompleto" id="nombreCompleto" required value="<?php echo isset($_GET['nombreCompleto']) ? htmlspecialchars($_GET['nombreCompleto']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="idSap">ID SAP:</label>
                    <input type="text" name="idSap" id="idSap" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado" required>
                        <option value="Asignada">Asignada</option>
                        <option value="En Mal Estado">En Mal Estado</option>
                        <option value="Extraviada">Extraviada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fechaEstado">Fecha Estado:</label>
                    <input type="date" name="fechaEstado" id="fechaEstado" required>
                </div>
                <div class="form-group">
                    <label for="observacion">Observación:</label>
                    <textarea name="observacion" id="observacion"></textarea>
                </div>
                <div class="form-group">
                    <label for="ubiActLlave">Ubicación Actual:</label>
                    <input type="text" name="ubiActLlave" id="ubiActLlave">
                </div>
                <div class="form-group">
                    <label for="fechaAsignacion">Fecha Asignación:</label>
                    <input type="date" name="fechaAsignacion" id="fechaAsignacion">
                </div>
                <div class="form-group">
                    <label for="segundaObservacion">2ª Observación:</label>
                    <textarea name="segundaObservacion" id="segundaObservacion"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="Añadir Llave de Cepo" name="añadir">
                </div>
            </form>
        </div>

    </div>

    
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

            // Función para añadir nueva llave
            function añadir_llave($datos) {
                $pdo = conexion();
                $sql = "INSERT INTO llaves_de_cepo (COD_LLAVE, CEDULA_TEC, NOMBRE_COMPLETO, ID_SAP, ESTADO_LLAVE, FECHA_ESTADO, OBSERVACION, UBI_ACT_LLAVE, FECHA_ASIGNACION, `2_OBSERVACION`) 
                        VALUES (:codLlave, :cedulaTec, :nombreCompleto, :idSap, :estado, :fechaEstado, :observacion, :ubiActLlave, :fechaAsignacion, :segundaObservacion)";
                $stmt = $pdo->prepare($sql);
                return $stmt->execute($datos);
            }

            // Función para actualizar llave
            function actualizar_llave($datos) {
                $pdo = conexion();
                $sql = "UPDATE llaves_de_cepo SET CEDULA_TEC = :cedulaTec, NOMBRE_COMPLETO = :nombreCompleto, ID_SAP = :idSap, ESTADO_LLAVE = :estado, FECHA_ESTADO = :fechaEstado, 
                        OBSERVACION = :observacion, UBI_ACT_LLAVE = :ubiActLlave, FECHA_ASIGNACION = :fechaAsignacion, `2_OBSERVACION` = :segundaObservacion
                        WHERE COD_LLAVE = :codLlave";
                $stmt = $pdo->prepare($sql);
                return $stmt->execute($datos);
            }

            // Función para eliminar llave
            function eliminar_llave($codLlave) {
                $pdo = conexion();
                $sql = "DELETE FROM llaves_de_cepo WHERE COD_LLAVE = :codLlave";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':codLlave', $codLlave);
                return $stmt->execute();
            }

            // Procesar formulario de añadir o editar
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $datos = [
                    'codLlave' => $_POST['codLlave'],
                    'cedulaTec' => $_POST['cedulaTec'],
                    'nombreCompleto' => $_POST['nombreCompleto'],
                    'idSap' => $_POST['idSap'],
                    'estado' => $_POST['estado'],
                    'fechaEstado' => $_POST['fechaEstado'],
                    'observacion' => $_POST['observacion'],
                    'ubiActLlave' => $_POST['ubiActLlave'],
                    'fechaAsignacion' => $_POST['fechaAsignacion'],
                    'segundaObservacion' => $_POST['segundaObservacion']
                ];

                if (isset($_POST['editar'])) {
                    actualizar_llave($datos);
                } else {
                    añadir_llave($datos);
                }
            }

            // Procesar eliminar
            if (isset($_GET['eliminar'])) {
                eliminar_llave($_GET['codLlave']);
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
                echo '<th>2ª Observación</th>';
                echo '</tr>';
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
                this.textContent = 'Añadir Llave de Cepo';
            } else {
                form.style.display = 'none';
                this.textContent = 'Añadir Nueva Llave de Cepo';
            }
        });
    </script>
</div>
</body>
</html>
