<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
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
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="css/bulma.mss">
    <link rel="stylesheet" href="../css/estilos_admin.css">

    <style>
                /* Fondo */
                .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('logo2.png'); /* Cambia a tu imagen */
            background-size: 95%; /* Ajusta el tamaño de la imagen */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            opacity: 0.2; /* Opacidad del fondo */
            z-index: 1; /* Asegura que esté detrás del contenedor */
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Bienvenido, Administrador</h1>
        <p>Seleccione una sección:</p>
        <div class="buttons">
            <a href="../roles/administrador/cambios_herramientas.php" class="button is-link">Cambios Herramientas</a>
            <a href="../roles/administrador/asignaciones.php" class="button is-link">Asignaciones</a>
            <a href="../roles/administrador/celulares.php" class="button is-link">Celulares</a>
            <a href="../roles/administrador/descuento_herramientas.php" class="button is-link">Descuento <br>Herramientas</a>
            <a href="../roles/administrador/detectores.php" class="button is-link">Detectores</a>
            <a href="../roles/administrador/herramienta.php" class="button is-link">Herramienta</a>
            <a href="../roles/administrador/llaves_de_cepo.php" class="button is-link">Llaves de Cepo</a>
            <a href="../roles/administrador/material.php" class="button is-link">Material</a>
            <a href="../roles/administrador/materiales_asignados.php" class="button is-link">Materiales Asignados</a>
            <a href="../roles/administrador/tecnicos.php" class="button is-link">Técnicos</a>
        </div>
        <!-- Botón de Gestión de Usuarios -->
        <a href="gestionar_usuarios.php" class="button is-info" style="margin-top: 20px;">Gestionar Usuarios</a>
         <br>   
        <form method="post" action="administrador.php" style="margin-top: 20px;">
            <button class="button is-danger" type="submit" name="logout">Cerrar sesión</button>
        </form>

    </div>
</body>
</html>
