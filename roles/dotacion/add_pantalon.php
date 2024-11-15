<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Pantalón</title>
</head>
<body>
    <h1>Insertar Pantalón</h1>
    <form action="insert_pantalon.php" method="post">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required><br><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" required><br><br>

        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo"><br><br>

        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" required><br><br>

        <label for="pan_can">Cantidad:</label>
        <input type="number" id="pan_can" name="pan_can" required><br><br>

        <label for="pan_talla">Talla:</label>
        <input type="text" id="pan_talla" name="pan_talla" required><br><br>

        <label for="pan_con">Código de Pantalón:</label>
        <input type="number" id="pan_con" name="pan_con" required><br><br>

        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"></textarea><br><br>

        <input type="submit" value="Insertar Pantalón">
    </form>
</body>
</html>
