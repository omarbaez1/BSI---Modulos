<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cambios de Herramientas</title>
    <link rel="stylesheet" href="../css/estilos_materiales.css">
    <style>
        /* Estilos para la tabla y el contenedor */
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        /* Estilos para el slide de agregar nuevo */
        .slide-container {
            display: none;
            margin-top: 20px;
        }
        .slide-btn {
            cursor: pointer;
            color: white;
            background-color: #007bff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
    <script>
        function toggleSlide() {
            var slide = document.getElementById("slide-container");
            if (slide.style.display === "none") {
                slide.style.display = "block";
            } else {
                slide.style.display = "none";
            }
        }
    </script>
</head>

<body>

    <h2>Buscar Cambios de Herramientas</h2>

    <!-- Formulario de Búsqueda Unificada -->
    <form method="GET" action="">
        <label for="search">Buscar:</label>
        <input type="text" name="search" id="search" placeholder="Buscar por cualquier campo">
        <input type="submit" value="Buscar">
    </form>

    <h2>Resultados de la Búsqueda</h2>

    <div class="results">
        <?php
        // Conexión a la base de datos
        function conexion()
        {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error de conexión: ' . $e->getMessage();
                die();
            }
        }

        // Función para buscar cambios de herramientas
        function buscar_cambios($search = null)
        {
            $pdo = conexion();
            $sql = "SELECT * FROM cambios_herramientas WHERE 1=1";

            if (!empty($search)) {
                $sql .= " AND (
                    LOWER(ID_CAMBIO) LIKE LOWER(:search) OR
                    LOWER(COD_HERRAMIENTA) LIKE LOWER(:search) OR
                    LOWER(CEDULA_TEC) LIKE LOWER(:search) OR
                    LOWER(NOMBRE_COMPLETO) LIKE LOWER(:search)
                )";
            }

            $stmt = $pdo->prepare($sql);

            if (!empty($search)) {
                $stmt->bindValue(':search', '%' . $search . '%');
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Obtener valor de la búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        // Mostrar los resultados
        $cambios = buscar_cambios($search);

        if (!empty($cambios)) {
            echo '<div class="table-container">';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID Cambio</th>';
            echo '<th>Cédula Técnico</th>';
            echo '<th>Nombre Completo</th>';
            echo '<th>Código Herramienta</th>';
            echo '<th>ID Usuario</th>';
            echo '<th>Tipo Cambio</th>';
            echo '<th>Fecha Cambio</th>';
            echo '<th>Observación</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($cambios as $cambio) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($cambio['ID_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['CEDULA_TEC']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['NOMBRE_COMPLETO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['COD_HERRAMIENTA']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['ID_USUARIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['TIPO_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['FECHA_CAMBIO']) . '</td>';
                echo '<td>' . htmlspecialchars($cambio['OBSERVACION']) . '</td>';
                // Botones de Editar y Eliminar
                echo '<td>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo 'No se encontraron resultados.';
        }
        ?>
    </div>


</body>

</html>
