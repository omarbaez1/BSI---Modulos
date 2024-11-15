<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Roles</title>
</head>
<body>

    <h2>Buscar Roles</h2>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="">
        <label for="nombreRol">Nombre del Rol:</label>
        <input type="text" name="nombreRol" id="nombreRol"><br><br>

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

    // Función para buscar roles por nombre del rol
    function buscar_rol($nombreRol = null){
        $pdo = conexion();

        // Construir la consulta SQL con filtros opcionales
        $sql = "SELECT * FROM `roles` WHERE 1=1";
        
        if (!empty($nombreRol)) {
            $sql .= " AND `NOMBRE_ROL` = :nombreRol";
        }

        $stmt = $pdo->prepare($sql);
        
        // Vincular los parámetros si existen
        if (!empty($nombreRol)) {
            $stmt->bindParam(':nombreRol', $nombreRol);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener valores del formulario
    $nombreRol = isset($_GET['nombreRol']) ? $_GET['nombreRol'] : null;

    // Buscar y mostrar los resultados
    $roles = buscar_rol($nombreRol);

    if (!empty($roles)) {
        foreach($roles as $rol) {
            echo 'ID Rol: ' . $rol['ID_ROL'] . '<br>';
            echo 'Nombre Rol: ' . $rol['NOMBRE_ROL'] . '<br>';
            echo 'Descripción: ' . $rol['DESCRIPCION'] . '<br>';
            echo '<hr>';
        }
    } else {
        echo 'No se encontraron resultados.';
    }
    ?>

</body>
</html>
