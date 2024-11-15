<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Overol</title>
</head>
<body>
    <h1>Insertar Overol</h1>
    <form action="insert_overol.php" method="post">
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

        <label for="ove_can">Cantidad:</label>
        <input type="number" id="ove_can" name="ove_can" required><br><br>

        <label for="ove_talla">Talla:</label>
        <input type="text" id="ove_talla" name="ove_talla" required><br><br>

        <label for="ove_con">Código de Overol:</label>
        <input type="number" id="ove_con" name="ove_con" required><br><br>

        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"></textarea><br><br>

        <input type="submit" value="Insertar Overol">
    </form>
</body>
</html>
