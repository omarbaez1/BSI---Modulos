<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Carnet</title>
</head>
<body>
    <h1>Insertar Carnet</h1>
    <form action="insert_carnet.php" method="post">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required><br><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" required><br><br>

        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo" required><br><br>

        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" required><br><br>

        <label for="bsi">BSI:</label>
        <input type="text" id="bsi" name="bsi" required><br><br>

        <label for="sura">SURA:</label>
        <input type="text" id="sura" name="sura" required><br><br>

        <label for="vanti">VANTI:</label>
        <input type="text" id="vanti" name="vanti" required><br><br>

        <label for="portacarnet">Portacarnet:</label>
        <input type="text" id="portacarnet" name="portacarnet" required><br><br>

        <label for="observacion">Observación:</label>
        <input type="text" id="observacion" name="observacion"><br><br>

        <input type="submit" value="Insertar Carnet">
    </form>
</body>
</html>
