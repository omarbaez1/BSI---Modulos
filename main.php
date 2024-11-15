<?php require "inicio_sesion/ingresar.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "./inc/head.php"; ?>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="css/bulma.min.css">
        </head>
        <?php

# Conexión a la base de datos #
function conexion(){
    $pdo = new PDO('mysql:host=localhost;dbname=almacen_bsi', 'root', '');
    return $pdo;
}

# Verificar datos #
function verificar_datos($filtro, $cadena){
    if(preg_match("/^".$filtro."$/", $cadena)){
        return false;
    } else {
        return true;
    }
}

$NOMBRE_COMPLETO = "Galindo Santos Camilo";

# Validar el nombre #
if(verificar_datos("[a-zA-Z\s]{3,40}", $NOMBRE_COMPLETO)){  
    echo "Los datos no coinciden";
} else {
    # Conexión a la base de datos #
    $conexion = conexion();
    
    # Preparar la consulta SQL #
    $sql = "SELECT * FROM tecnicos WHERE NOMBRE_COMPLETO = :nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $NOMBRE_COMPLETO, PDO::PARAM_STR);
    
    # Ejecutar la consulta #
    $stmt->execute();
    
    # Obtener los resultados #
    $tecnico = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($tecnico){
        echo "Información del Técnico:<br>";
        echo "Documento: " . $tecnico['DOCUMENTO'] . "<br>";
        echo "Nombre Completo: " . $tecnico['NOMBRE_COMPLETO'] . "<br>";
        echo "Cargo: " . $tecnico['CARGO'] . "<br>";
        echo "Fecha de Ingreso: " . $tecnico['FECHA_INGRESO'] . "<br>";
        # Muestra más campos según tu tabla 'tecnicos'
    } else {
        echo "No se encontró información para el técnico con nombre: $NOMBRE_COMPLETO";
    }
}

?>
