include 'analista/conexion.php';
$pdo = conexion_analista();

include 'administrador/conexion.php';
$pdo = conexion_administrador();

include 'almacenista/conexion.php';
$pdo = conexion_almacenista();

include 'usuario1/conexion.php';
$pdo = conexion_usuario1();

