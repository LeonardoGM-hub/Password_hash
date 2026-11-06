<?php
$archivo = "usuarios.json";
$msg = "";

// Leemos y asignamos los valores ingresados a variables 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $pass  = $_POST["pass"] ?? "";

    // Verificamos que los datos no esten en blanco
    if ($login === "" || $pass === "") {
        $msg = "Todos los campos son obligatorios.";
    } else {
        // Se verifica el archivo JSON para busca rel usuario
        $usuarios = [];
        if (file_exists($archivo)) {
            $contenido = file_get_contents($archivo);
            $usuarios = json_decode($contenido, true);
        }
        if ($usuarios == "") {
            $usuarios = [];
        }

        // Buscamos al usuario
        $usuarioEncontrado = null;
        foreach ($usuarios as $u) {
            // Comparamos el login
            if (strtolower($u['login']) === strtolower($login)) {
                $usuarioEncontrado = $u;
                break;
            }
        }

        // checamos que el usuario exista y verificamos su contraseña
        if ($usuarioEncontrado === null) {
            $msg = "El usuario no está registrado.";
        } else {
            // Recuperar el hash de su contraseña con password_verify
            $hash = $usuarioEncontrado['pass'];

            if (password_verify($pass, $hash)) {
                // mostramos el mensaje de acceso autorizado
                $msg = "¡Bienvenido al sistema! Acceso autorizado.";
            } else {
                // si no coincide damos un mensaje de acceso rechazado
                $msg = "Contraseña incorrecta. Acceso rechazado.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <h2>Iniciar Sesion</h2>
    
    <!-- Mostramos el mensaje del login -->
    <?php if ($msg !== ""): ?>
        <p style="font-weight: bold; color: <?= (strpos($msg, 'Bienvenido') !== false) ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($msg) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de usuario (login):</label>
        <input type="text" name="login" value="<?= htmlspecialchars($login ?? '') ?>" required>

        <label>Contraseña:</label>
        <input type="password" name="pass" required>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>