<?php 
session_start();

// Configuración de la base de datos
$host = '127.0.0.1';
$db = 'almacen_bsi';
$user = 'root'; // Cambia esto según tu configuración
$pass = ''; // Cambia esto según tu configuración

// Crear una conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Consulta SQL para validar las credenciales
    $stmt = $conn->prepare("
        SELECT u.USERNAME, u.PASSWORD, r.NOMBRE_ROL 
        FROM usuarios u 
        JOIN roles r ON u.ID_ROL = r.ID_ROL 
        WHERE u.USERNAME = ? AND u.PASSWORD = ? AND r.NOMBRE_ROL = ?
    ");
    $stmt->bind_param('sss', $usuario, $password, $rol);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si las credenciales son correctas
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $rol;

        // Redirigir a la página correspondiente según el rol
        switch ($rol) {
            case 'administrador':
                header("Location: administrador.php");
                break;
            case 'ingeniero':
                header("Location: ingeniero.php");
                break;
            case 'analista':
                header("Location: analista.php");
                break;
            case 'almacen':
                header("Location: almacen.php");
                break;
            case 'dotacion':
                header("Location: dotacion.php");
                break;
            default:
                $error = "Rol no válido.";
        }
        exit();
    } else {
        $error = "Credenciales incorrectas. Inténtelo nuevamente.";
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../css/estilos_incio.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Evita scroll en la página */
            position: relative; /* Para posicionar el fondo correctamente */
        }
        .container {
            display: flex; /* Usar flexbox para alinear la imagen y el formulario */
            width: 80%; /* Ancho del contenedor */
            max-width: 1000px; /* Ancho máximo del contenedor */
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .image-container {
            flex: 1; /* Imagen ocupa el 50% del contenedor */
            display: flex; /* Para centrar la imagen */
            justify-content: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente */
            padding: 20px; /* Espacio alrededor de la imagen */
        }
        .form-container {
            flex: 1; /* Formulario ocupa el 50% del contenedor */
            padding: 30px; /* Espacio alrededor del formulario */
            text-align: center;
        }
        .icon1 {
            width: 200px; /* Ajusta el tamaño de la imagen */
            height: auto;
            margin-bottom: 15px; /* Espacio entre la imagen y el título */
        }
        .icon {
            width: 500px; /* Ajusta el tamaño de la imagen */
            height: auto;
            margin-bottom: 15px; /* Espacio entre la imagen y el título */
            border-radius: 15px;
        }
        h2.title {
            margin-bottom: 15px; /* Espacio entre el título y el formulario */
            color: #333; /* Color del texto del título */
        }
        .field {
            margin-bottom: 15px; /* Espacio entre los campos del formulario */
        }
        .label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .input, .select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Para incluir el padding y border en el ancho total */
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3; /* Color al pasar el ratón */
        }
        p.error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="img/almacen.png" alt="Icono" class="icon"> <!-- Muestra la imagen dentro del contenedor -->
        </div>
        <div class="form-container">
            <img src="img/logo.png" alt="Icono" class="icon1">
            <h2 class="title">Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <div class="field">
                    <label class="label">Usuario</label>
                    <input class="input" type="text" name="usuario" required>
                </div>
                <div class="field">
                    <label class="label">Contraseña</label>
                    <input class="input" type="password" name="password" required>
                </div>
                <div class="field">
                    <label class="label">Rol</label>
                    <select class="select" name="rol" required>
                        <option value="administrador">Administrador</option>
                        <option value="ingeniero">Ingeniero</option>
                        <option value="analista">Analista</option>
                        <option value="almacen">Almacenista</option>
                        <option value="dotacion">Dotación</option>
                    </select>
                </div>
                <div class="field">
                    <br>
                    <button class="button" type="submit">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
