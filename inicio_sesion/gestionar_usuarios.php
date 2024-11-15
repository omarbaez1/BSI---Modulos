<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

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

// Mensaje de feedback
$message = "";

// Procesar formulario de agregar usuario
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash de contraseña
    $rol = $_POST['rol'];

    // Consulta para insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (USERNAME, PASSWORD, ID_ROL) VALUES (?, ?, (SELECT ID_ROL FROM roles WHERE NOMBRE_ROL = ?))");
    $stmt->bind_param('sss', $username, $password, $rol);
    if ($stmt->execute()) {
        $message = "Usuario agregado correctamente.";
    } else {
        $message = "Error al agregar usuario.";
    }
}

// Procesar formulario de eliminar usuario
if (isset($_POST['delete_user'])) {
    $username = $_POST['delete_username'];

    // Consulta para eliminar usuario
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE USERNAME = ?");
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $message = "Usuario eliminado correctamente.";
    } else {
        $message = "Error al eliminar usuario.";
    }
}

// Procesar formulario de cambiar contraseña
if (isset($_POST['change_password'])) {
    $username = $_POST['change_username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT); // Hash de nueva contraseña

    // Consulta para actualizar contraseña
    $stmt = $conn->prepare("UPDATE usuarios SET PASSWORD = ? WHERE USERNAME = ?");
    $stmt->bind_param('ss', $new_password, $username);
    if ($stmt->execute()) {
        $message = "Contraseña cambiada correctamente.";
    } else {
        $message = "Error al cambiar contraseña.";
    }
}

// Obtener lista de usuarios y roles
$roles_result = $conn->query("SELECT NOMBRE_ROL FROM roles");
$roles = $roles_result->fetch_all(MYSQLI_ASSOC);

$users_result = $conn->query("SELECT u.USERNAME, r.NOMBRE_ROL FROM usuarios u JOIN roles r ON u.ID_ROL = r.ID_ROL");
$users = $users_result->fetch_all(MYSQLI_ASSOC);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/estilo_u.css">
    <style>
        /* Estilos aquí */
    </style>
</head>
<body>

<div class="header-container">
    <h1 class="title">Gestión de Usuarios</h1>
    <button class="toggle-button" onclick="window.location.href='../../almacen_bsi/inicio_sesion/administrador.php';">Página Principal</button>
</div>

<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<!-- Formulario para agregar usuario -->
<h2 class="subtitle">Agregar Usuario</h2>
<form method="post" action="gestionar_usuarios.php">
    <div class="field">
        <label class="label">Nombre de Usuario</label>
        <div class="control">
            <input class="input" type="text" name="username" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Contraseña</label>
        <div class="control">
            <input class="input" type="password" name="password" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Rol</label>
        <div class="control">
            <div class="select">
                <select name="rol" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo htmlspecialchars($role['NOMBRE_ROL']); ?>">
                            <?php echo htmlspecialchars($role['NOMBRE_ROL']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-link" type="submit" name="add_user">Agregar Usuario</button>
        </div>
    </div>
</form>

<!-- Formulario para eliminar usuario -->
<h2 class="subtitle">Eliminar Usuario</h2>
<form method="post" action="gestionar_usuarios.php">
    <div class="field">
        <label class="label">Nombre de Usuario</label>
        <div class="control">
            <input class="input" type="text" name="delete_username" required>
        </div>
    </div>
    <div class="field">
        <div class="control">
           <button class="button is-danger" type="submit" name="delete_user">Eliminar Usuario</button>
        </div>
    </div>
</form>

<!-- Formulario para cambiar contraseña -->
<h2 class="subtitle">Cambiar Contraseña</h2>
<form method="post" action="gestionar_usuarios.php">
    <div class="field">
        <label class="label">Nombre de Usuario</label>
        <div class="control">
            <input class="input" type="text" name="change_username" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Nueva Contraseña</label>
        <div class="control">
            <input class="input" type="password" name="new_password" required>
        </div>
    </div>
    <div class="field">
        <div class="control"><br>
            <button class="button is-link" type="submit" name="change_password">Cambiar Contraseña</button>
        </div>
    </div>
</form>

<!-- Mostrar usuarios existentes -->
<h2 class="subtitle">Usuarios Existentes</h2>
<table>
    <thead>
        <tr>
            <th>Nombre de Usuario</th>
            <th>Rol</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['USERNAME']); ?></td>
                <td><?php echo htmlspecialchars($user['NOMBRE_ROL']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
