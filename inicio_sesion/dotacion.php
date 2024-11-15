<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es analista
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'dotacion') {
    header("Location: login.php");
    exit();
}

// Verificar si el usuario ha solicitado cerrar sesión
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de dotacion</title>
    <link rel="stylesheet" href="../css/bulma.mss">
    <link rel="stylesheet" href="../css/estilos_admin.css">
    <link rel="stylesheet" href="../css/estilos_admin.css">

</head>
<body>
    <div class="container">
        <h1 class="title"></h1>
        <br><br><br>
        <h1 class="title">Bienvenido</h1>
        <p>Seleccione una sección:</p>
        <div class="buttons">
            <a href="../roles/dotacion/DATOS_PROTECCION.php" class="button is-link">Matriz <br> EPP</a>
            <a href="../roles/dotacion/Camisa.php" class="button is-link">Control <br>Camisas<br>Actuales</a>
            <a href="../roles/dotacion/pantalon.php" class="button is-link">Control<br> Pantalones<br>Actuales</a>
            <a href="../roles/dotacion/chaqueta.php" class="button is-link">Control <br>Chaquetas<br>Actuales</a>
            <a href="../roles/dotacion/overol.php" class="button is-link">Control <br>Overoles<br>Actuales</a>
            <a href="../roles/dotacion/personal.php" class="button is-link">Listado <BR> Tecnicos</a>
            <a href="../roles/dotacion/carnet.php" class="button is-link">Control <BR> EPP 1</a>
            <a href="../roles/dotacion/epp2.php" class="button is-link">Control <BR> EPP 2</a>
            <a href="../roles/dotacion/historial.php" class="button is-link">Historial Dotacion</a>
            <!--<a href="roles/analista/materiales_asignados.php" class="button is-link">Materiales Asignados</a>
-->
        </div>

        <form method="post" action="analista.php" style="margin-top: 20px;">
            <button class="button is-danger" type="submit" name="logout">Cerrar sesión</button>
        </form>
        
    </div>
</body>
</html>
