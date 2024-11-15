<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Detectores</title>
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        .slide-container {
            display: none;
            margin-top: 20px;
        }
        .slide-container.active {
            display: block;
        }
    </style>
    <script>
        function toggleSlide() {
            var x = document.getElementById("addForm");
            if (x.style.display === "none" || x.style.display === "") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function editDetector(detectorJson) {
            var detector = JSON.parse(detectorJson);
            var form = document.querySelector('#addForm form');
            form.action = ''; // Ensures form action is correct

            // Llenar los campos del formulario con los valores del detector
            form.querySelector('[name="action"]').value = 'edit';
            form.querySelector('[name="serial"]').value = detector['SERIAL'];
            form.querySelector('[name="cedulaTec"]').value = detector['CEDULA_TEC'];
            form.querySelector('[name="fechaLlegada"]').value = detector['FECHA_LLEGADA'];
            form.querySelector('[name="procedencia"]').value = detector['PROCEDENCIA'];
            form.querySelector('[name="valor"]').value = detector['VALOR'];
            form.querySelector('[name="marca"]').value = detector['MARCA'];
            form.querySelector('[name="modelo"]').value = detector['MODELO'];
            form.querySelector('[name="estuche"]').value = detector['ESTUCHE'];
            form.querySelector('[name="fechaCalibracion"]').value = detector['FECHA_CALIBRACION'];
            form.querySelector('[name="fechaProxCalibracion"]').value = detector['FECHA_PROX_CALIBRACION'];
            form.querySelector('[name="quienLoTenia"]').value = detector['QUIEN_LO_TENIA'];
            form.querySelector('[name="ubiActEqui"]').value = detector['UBI_ACT_EQUI'];
            form.querySelector('[name="tecAsig"]').value = detector['TEC_ASIG'];
            form.querySelector('[name="estadoEquipo"]').value = detector['ESTADO_EQUIPO'];
            form.querySelector('[name="fechaEstado"]').value = detector['FECHA_ESTADO'];
            form.querySelector('[name="observacion"]').value = detector['OBSERVACION'];
            form.querySelector('[name="observacionProsoind"]').value = detector['OBSERVACION_PROSOIND'];
            form.querySelector('[name="observacionPerdidos"]').value = detector['OBSERVACION_PERDIDOS'];
            form.querySelector('[name="fechaUltValid"]').value = detector['FECHA_ULT_VALID'];

            toggleSlide(); // Mostrar el formulario
        }

        function confirmarEliminacion(serial) {
            if (confirm("¿Está seguro que desea eliminar el detector con Serial " + serial + "?")) {
                window.location.href = "?delete=1&serial=" + serial;
            }
        }
    </script>
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #007acc; color: white; border-radius: 15px;">
<div style="flex: 1; text-align: left;">
        <img src="img/logo.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>    
    <div style="flex: 1.60; text-align: left; margin-right: auto; margin-left: auto;">
        <h3 style="margin: 0; font-size: 30px;">DETECTORES</h3>
    </div>
    <div style="flex:   0; text-align: right; margin-right: auto; margin-left: auto;">
        
    <img src="img/detector.png" alt="Logo" style="border-radius: 10px;; width: 100px; height: auto;">
    </div>
</header>
    
    <br>
<button class="toggle-button" onclick="window.location.href='../../inicio_sesion/analista.php';">Página Principal</button> <br>
<a>Para ver la informacion primero presiona en buscar</a>

<h2>Buscar Detector</h2>

<!-- Formulario de Búsqueda -->
<form method="GET" action="">
    <label for="serial">Serial:</label>
    <input type="text" name="serial" id="serial"><br><br>

    <label for="cedulaTec">Cédula Técnico:</label>
    <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

    <label for="estadoEquipo">Estado del Equipo:</label>
    <select name="estadoEquipo" id="estadoEquipo">
        <option value="">Seleccione un estado</option>
        <option value="Asignado">Asignado</option>
        <option value="Dar de baja">Dar de baja</option>
        <option value="Disponible">Disponible</option>
    </select><br><br>

    <label for="tecnicoAsignado">Técnico Asignado:</label>
    <input type="text" name="tecnicoAsignado" id="tecnicoAsignado"><br><br>

    <input type="submit" value="Buscar">
</form>

<!-- Botón para añadir un nuevo detector -->
<div class="button"> <br>
    <button  class="toggle-button" onclick="toggleSlide()">Añadir Detector</button>

</div>

<!-- Formulario para agregar o editar detector -->
<div id="addForm" class="slide-container">
    <h2>Agregar/Editar Detector</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="add">
        <label for="serial">Serial:</label>
        <input type="text" name="serial" id="serial" required><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="fechaLlegada">Fecha de Llegada:</label>
        <input type="date" name="fechaLlegada" id="fechaLlegada"><br><br>

        <label for="procedencia">Procedencia:</label>
        <input type="text" name="procedencia" id="procedencia"><br><br>

        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor"><br><br>

        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca"><br><br>

        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo" id="modelo"><br><br>

        <label for="estuche">Estuche:</label>
        <input type="text" name="estuche" id="estuche"><br><br>

        <label for="fechaCalibracion">Fecha de Calibración:</label>
        <input type="date" name="fechaCalibracion" id="fechaCalibracion"><br><br>

        <label for="fechaProxCalibracion">Fecha Próx. Calibración:</label>
        <input type="date" name="fechaProxCalibracion" id="fechaProxCalibracion"><br><br>

        <label for="quienLoTenia">Quién lo Tenía:</label>
        <input type="text" name="quienLoTenia" id="quienLoTenia"><br><br>

        <label for="ubiActEqui">Ubicación Actual:</label>
        <input type="text" name="ubiActEqui" id="ubiActEqui"><br><br>

        <label for="tecAsig">Técnico Asignado:</label>
        <input type="text" name="tecAsig" id="tecAsig"><br><br>

        <label for="estadoEquipo">Estado del Equipo:</label>
        <input type="text" name="estadoEquipo" id="estadoEquipo"><br><br>

        <label for="fechaEstado">Fecha Estado:</label>
        <input type="date" name="fechaEstado" id="fechaEstado"><br><br>

        <label for="observacion">Observación:</label>
        <textarea name="observacion" id="observacion"></textarea><br><br>

        <label for="observacionProsoind">Observación Prosoind:</label>
        <textarea name="observacionProsoind" id="observacionProsoind"></textarea><br><br>

        <label for="observacionPerdidos">Observación Perdidos:</label>
        <textarea name="observacionPerdidos" id="observacionPerdidos"></textarea><br><br>

        <label for="fechaUltValid">Fecha Última Validación:</label>
        <input type="date" name="fechaUltValid" id="fechaUltValid"><br><br>

        <input type="submit" value="Guardar Detector">
    </form>
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

// Función para insertar o actualizar un detector
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = conexion();
    
    if ($_POST['action'] == 'add') {
        $sql = "INSERT INTO detectores (SERIAL, CEDULA_TEC, FECHA_LLEGADA, PROCEDENCIA, VALOR, MARCA, MODELO, ESTUCHE, FECHA_CALIBRACION, FECHA_PROX_CALIBRACION, QUIEN_LO_TENIA, UBI_ACT_EQUI, TEC_ASIG, ESTADO_EQUIPO, FECHA_ESTADO, OBSERVACION, OBSERVACION_PROSOIND, OBSERVACION_PERDIDOS, FECHA_ULT_VALID) 
                VALUES (:serial, :cedulaTec, :fechaLlegada, :procedencia, :valor, :marca, :modelo, :estuche, :fechaCalibracion, :fechaProxCalibracion, :quienLoTenia, :ubiActEqui, :tecAsig, :estadoEquipo, :fechaEstado, :observacion, :observacionProsoind, :observacionPerdidos, :fechaUltValid)";
    } else if ($_POST['action'] == 'edit') {
        $sql = "UPDATE detectores SET CEDULA_TEC = :cedulaTec, FECHA_LLEGADA = :fechaLlegada, PROCEDENCIA = :procedencia, VALOR = :valor, MARCA = :marca, MODELO = :modelo, ESTUCHE = :estuche, FECHA_CALIBRACION = :fechaCalibracion, FECHA_PROX_CALIBRACION = :fechaProxCalibracion, QUIEN_LO_TENIA = :quienLoTenia, UBI_ACT_EQUI = :ubiActEqui, TEC_ASIG = :tecAsig, ESTADO_EQUIPO = :estadoEquipo, FECHA_ESTADO = :fechaEstado, OBSERVACION = :observacion, OBSERVACION_PROSOIND = :observacionProsoind, OBSERVACION_PERDIDOS = :observacionPerdidos, FECHA_ULT_VALID = :fechaUltValid 
                WHERE SERIAL = :serial";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'serial' => $_POST['serial'],
        'cedulaTec' => $_POST['cedulaTec'],
        'fechaLlegada' => $_POST['fechaLlegada'],
        'procedencia' => $_POST['procedencia'],
        'valor' => $_POST['valor'],
        'marca' => $_POST['marca'],
        'modelo' => $_POST['modelo'],
        'estuche' => $_POST['estuche'],
        'fechaCalibracion' => $_POST['fechaCalibracion'],
        'fechaProxCalibracion' => $_POST['fechaProxCalibracion'],
        'quienLoTenia' => $_POST['quienLoTenia'],
        'ubiActEqui' => $_POST['ubiActEqui'],
        'tecAsig' => $_POST['tecAsig'],
        'estadoEquipo' => $_POST['estadoEquipo'],
        'fechaEstado' => $_POST['fechaEstado'],
        'observacion' => $_POST['observacion'],
        'observacionProsoind' => $_POST['observacionProsoind'],
        'observacionPerdidos' => $_POST['observacionPerdidos'],
        'fechaUltValid' => $_POST['fechaUltValid']
    ]);

    echo "Detector guardado correctamente.";
}

// Función para eliminar un detector
if (isset($_GET['delete'])) {
    $pdo = conexion();
    $serial = $_GET['serial'];
    $sql = "DELETE FROM detectores WHERE SERIAL = :serial";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['serial' => $serial]);

    echo "Detector eliminado correctamente.";
}

// Mostrar resultados de búsqueda
if ($_GET) {
    $pdo = conexion();
    $serial = $_GET['serial'] ?? '';
    $cedulaTec = $_GET['cedulaTec'] ?? '';
    $estadoEquipo = $_GET['estadoEquipo'] ?? '';
    $tecnicoAsignado = $_GET['tecnicoAsignado'] ?? '';

    $sql = "SELECT * FROM detectores WHERE SERIAL LIKE :serial AND CEDULA_TEC LIKE :cedulaTec AND ESTADO_EQUIPO LIKE :estadoEquipo AND TEC_ASIG LIKE :tecnicoAsignado";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'serial' => "%$serial%",
        'cedulaTec' => "%$cedulaTec%",
        'estadoEquipo' => "%$estadoEquipo%",
        'tecnicoAsignado' => "%$tecnicoAsignado%"
    ]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        echo "<table border='1'>
                <tr>
                    <th>Serial</th>
                    <th>Cédula Técnico</th>
                    <th>Fecha Llegada</th>
                    <th>Procedencia</th>
                    <th>Valor</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Estuche</th>
                    <th>Fecha Calibración</th>
                    <th>Fecha Próx. Calibración</th>
                    <th>Quien lo Tenía</th>
                    <th>Ubicación Actual</th>
                    <th>Técnico Asignado</th>
                    <th>Estado del Equipo</th>
                    <th>Fecha Estado</th>
                    <th>Observación</th>
                    <th>Observación Prosoind</th>
                    <th>Observación Perdidos</th>
                    <th>Fecha Última Validación</th>
                </tr>";
        
        foreach ($resultados as $fila) {
            $detectorJson = json_encode($fila);
            echo "<tr>
                    <td>{$fila['SERIAL']}</td>
                    <td>{$fila['CEDULA_TEC']}</td>
                    <td>{$fila['FECHA_LLEGADA']}</td>
                    <td>{$fila['PROCEDENCIA']}</td>
                    <td>{$fila['VALOR']}</td>
                    <td>{$fila['MARCA']}</td>
                    <td>{$fila['MODELO']}</td>
                    <td>{$fila['ESTUCHE']}</td>
                    <td>{$fila['FECHA_CALIBRACION']}</td>
                    <td>{$fila['FECHA_PROX_CALIBRACION']}</td>
                    <td>{$fila['QUIEN_LO_TENIA']}</td>
                    <td>{$fila['UBI_ACT_EQUI']}</td>
                    <td>{$fila['TEC_ASIG']}</td>
                    <td>{$fila['ESTADO_EQUIPO']}</td>
                    <td>{$fila['FECHA_ESTADO']}</td>
                    <td>{$fila['OBSERVACION']}</td>
                    <td>{$fila['OBSERVACION_PROSOIND']}</td>
                    <td>{$fila['OBSERVACION_PERDIDOS']}</td>
                    <td>{$fila['FECHA_ULT_VALID']}</td>

                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
}
?>

</body>
</html>
