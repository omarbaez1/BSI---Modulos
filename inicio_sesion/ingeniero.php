<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es ingeniero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'ingeniero') {
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
    <title>Panel del ingeniero</title>
    <link rel="stylesheet" href="css/bulma.mss">
    <link rel="stylesheet" href="../css/estilos_admin.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Bienvenido, ingeniero</h1>
        <p>Seleccione una sección:</p>
        <div class="buttons">
            <a href="../roles/ingeniero/cambios_herramientas.php" class="button is-link">Cambios Herramientas</a>
            <a href="../roles/ingeniero/asignaciones.php" class="button is-link">Asignaciones</a>
            <a href="../roles/ingeniero/celulares.php" class="button is-link">Celulares</a>
            <a href="../roles/ingeniero/descuento_herramientas.php" class="button is-link">Descuento <br>Herramientas</a>
            <a href="../roles/ingeniero/detectores.php" class="button is-link">Detectores</a>
            <a href="../roles/ingeniero/herramienta.php" class="button is-link">Herramienta</a>
            <a href="../roles/ingeniero/llaves_de_cepo.php" class="button is-link">Llaves de Cepo</a>
            <a href="../roles/ingeniero/material.php" class="button is-link">Material</a>
            <a href="../roles/ingeniero/materiales_asignados.php" class="button is-link">Materiales Asignados</a>
            <a href="../roles/ingeniero/tecnicos.php" class="button is-link">Técnicos</a>
        </div>
   
        <form method="post" action="ingeniero.php" style="margin-top: 20px;">
            <button class="button is-danger" type="submit" name="logout">Cerrar sesión</button>
        </form>
        
    </div>
</body>
</html>
