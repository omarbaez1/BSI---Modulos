<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es analista
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'analista') {
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
    <title>Panel del analista</title>
    <link rel="stylesheet" href="css/bulma.mss">
    <link rel="stylesheet" href="../css/estilos_admin.css">
</head>
<body>
    <div class="container">
        <h1 class="title"></h1>
        <br><br><br>
        <h1 class="title">Bienvenido, analista</h1>
        <p>Seleccione una sección:</p>
        <div class="buttons">
            <a href="../roles/analista/cambios_herramientas.php" class="button is-link">Cambios Herramientas</a>
            <a href="../roles/analista/asignaciones.php" class="button is-link">Asignaciones</a>
            <a href="../roles/analista/celulares.php" class="button is-link">Celulares</a>
            <a href="../roles/analista/descuento_herramientas.php" class="button is-link">Descuento <br>Herramientas</a>
            <a href="../roles/analista/detectores.php" class="button is-link">Detectores</a>
            <a href="../roles/analista/herramienta.php" class="button is-link">Herramienta</a>
            <a href="../roles/analista/llaves_de_cepo.php" class="button is-link">Llaves de Cepo</a>
            <a href="../roles/analista/material.php" class="button is-link">Material</a>
            <a href="../roles/analista/materiales_asignados.php" class="button is-link">Materiales Asignados</a>
            <a href="../roles/analista/tecnicos.php" class="button is-link">Técnicos</a>
        </div>

        <form method="post" action="analista.php" style="margin-top: 20px;">
            <button class="button is-danger" type="submit" name="logout">Cerrar sesión</button>
        </form>
        
    </div>
</body>
</html>
