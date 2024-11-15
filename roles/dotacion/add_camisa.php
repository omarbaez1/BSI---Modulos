<!DOCTYPE html>
<html>
<head>
    <title>Agregar Camisa</title>
</head>
<body>
    <h1>Agregar Camisa</h1>
    <form action="insert_camisa.php" method="post">
        <label for="cedula">Cédula:</label>
        <input type="number" id="cedula" name="cedula" required><br><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        
        <label for="fecha_entrega">Fecha de Entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" required><br><br>
        
        <label for="periodo">Período:</label>
        <input type="text" id="periodo" name="periodo"><br><br>
        
        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo"><br><br>
        
        <label for="cam_can">Cantidad:</label>
        <input type="number" id="cam_can" name="cam_can"><br><br>
        
        <label for="cam_talla">Talla:</label>
        <input type="text" id="cam_talla" name="cam_talla"><br><br>
        
        <label for="cam_con">Código Camisa:</label>
        <input type="number" id="cam_con" name="cam_con" required><br><br>
        
        <label for="observacion">Observación:</label>
        <textarea id="observacion" name="observacion"></textarea><br><br>
        
        <input type="submit" value="Agregar Camisa">
    </form>
</body>
</html>
