<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Celulares</title>
    <link rel="stylesheet" href="../css/estilos_celulares.css">

</head>
<body>

    <h2>Buscar Celulares</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="codigoCelular">Código Celular:</label>
        <input type="text" name="codigoCelular" id="codigoCelular"><br><br>

        <label for="cedulaTec">Cédula Técnico:</label>
        <input type="text" name="cedulaTec" id="cedulaTec"><br><br>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="">--Seleccione un Estado--</option>
            <option value="asignado">Asignado</option>
            <option value="asignado gncb">Asignado GNBC</option>
            <option value="dado de baja">Dado de Baja</option>
            <option value="dañado">Dañado</option>
            <option value="disponible">Disponible</option>
            <option value="perdido por tecnico">Perdido por Técnico</option>
            <option value="robado">Robado</option>
        </select><br><br>

        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
        <div class="table-container">
        <?php
        function conexion(){
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error de conexión: ' . htmlspecialchars($e->getMessage());
                die();
            }
        }

        function buscar_celulares($codigoCelular = null, $cedulaTec = null, $estado = null){
            $pdo = conexion();

            $sql = "SELECT * FROM `celulares` WHERE 1=1";
            
            if (!empty($codigoCelular)) {
                $sql .= " AND LOWER(`COD_CELULAR`) LIKE LOWER(:codigoCelular)";
            }
            
            if (!empty($cedulaTec)) {
                $sql .= " AND LOWER(`CEDULA_TEC`) LIKE LOWER(:cedulaTec)";
            }
            
            if (!empty($estado)) {
                $sql .= " AND LOWER(`ESTADO`) LIKE LOWER(:estado)";
            }

            $stmt = $pdo->prepare($sql);
            
            if (!empty($codigoCelular)) {
                $stmt->bindValue(':codigoCelular', '%' . strtolower($codigoCelular) . '%');
            }
            
            if (!empty($cedulaTec)) {
                $stmt->bindValue(':cedulaTec', '%' . strtolower($cedulaTec) . '%');
            }
            
            if (!empty($estado)) {
                $stmt->bindValue(':estado', '%' . strtolower($estado) . '%');
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $codigoCelular = isset($_GET['codigoCelular']) ? $_GET['codigoCelular'] : null;
        $cedulaTec = isset($_GET['cedulaTec']) ? $_GET['cedulaTec'] : null;
        $estado = isset($_GET['estado']) ? $_GET['estado'] : null;

        $celulares = buscar_celulares($codigoCelular, $cedulaTec, $estado);

        if (!empty($celulares)) {
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>Código Celular</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>Marca</th>';
            echo '<th>Modelo</th>';
            echo '<th>Propietario</th>';
            echo '<th>IMEI1</th>';
            echo '<th>IMEI2</th>';
            echo '<th>Número Asignado</th>';
            echo '<th>Fecha Ingreso</th>';
            echo '<th>Estado</th>';
            echo '<th>Fecha Asignación</th>';
            echo '<th>Observación</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            foreach($celulares as $celular) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($celular['COD_CELULAR']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['MARCA']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['MODELO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['PROPIETARIO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['IMEI1']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['IMEI2']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['NUMERO_ASIGNADO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['FECHA_INGRESO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['ESTADO']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['FECHA_ASIGNACION']) . '</td>';
                echo '<td>' . htmlspecialchars($celular['OBSERVACION']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No se encontraron resultados.';
        }
        ?>
        </div>
    </div>

</body>
</html>
