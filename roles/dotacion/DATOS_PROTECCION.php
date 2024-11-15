<?php  
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epp";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los datos de la tabla 'equipo_proteccion'
$sql = "SELECT * FROM equipo_proteccion";
$result = $conn->query($sql);

// Verifica si la consulta ha tenido éxito
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Equipo de Protección</title>
    <link rel="stylesheet" href="estilo_u.css">
    <link rel="stylesheet" href="css/estilo_u.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            color: #007ba7;
            text-align: center;
            font-size: 2.5rem;
            margin: 20px 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #007ba7;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        tr:hover {
            background-color: #e0f7fa;
        }

        .separator {
            height: 2px;
            background-color: #007ba7; /* Color de la línea de separación */
        }

        .no-results {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin: 20px 0;
        }

        .toggle-button {
            position: relative; /* Asegura que se pueda desplazar */
            top: 10px; /* Desplazar hacia abajo */
            left: 15px; /* Desplazar a la derecha */

            display: inline-block; /* Permitir ajustes de posición */
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }
            th, td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <button class="toggle-button" onclick="window.location.href='../../inicio_sesion/dotacion.php';">Página Principal</button>

    <h1>Datos de Equipo de Protección</h1>

    <?php
    // Verifica si la consulta devuelve resultados
    if ($result->num_rows > 0) {
        // Muestra los datos en una tabla HTML
        echo "<table>";
        
        // Muestra los encabezados
        echo "<tr>
                <th>Campo</th>
                <th>Valor</th>
              </tr>";

        // Muestra los datos de cada fila
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            // Oculta la fila ID
            foreach ($row as $key => $value) {
                if ($key == 'id') {
                    continue; // Salta el campo ID
                }
                echo "<tr";
                if ($key == 'elemento_equipo_proteccion') {
                    echo " class='element-row'"; // Aplica el color a la fila de elemento_equipo_proteccion
                }
                echo ">";
                echo "<td>" . htmlspecialchars($key) . "</td>";
                echo "<td>" . htmlspecialchars($value) . "</td>";
                echo "</tr>";
            }
            // Añadir fila de ID oculta
            echo "<tr class='highlight-row'><td>ID</td><td>" . $id . "</td></tr>";
            // Añadir línea de separación entre registros
            echo "<tr class='separator'><td colspan='2'></td></tr>";
        }
        
        echo "</table>";
    } else {
        echo "<div class='no-results'>0 resultados</div>";
    }
    
    // Cierra la conexión
    $conn->close();
    ?>
    
</body>
</html>
