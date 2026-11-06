<!--Se uso como referencia el codigo de registros en Base de datos SQL,
    de una manera local.

    Tambien se investigo como mandar informacion y guardarla de manera 
    local con un archivo .json -->
<?php
$archivo = "usuarios.json";
$msg = "";

// Le asignamos los valores solicitados y leidos en las variables para su uso
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $login  = trim($_POST["login"] ?? "");
    $pass   = $_POST["pass"] ?? "";
    $conf   = $_POST["conf"] ?? "";

        // Nos aseguramos de no tener informacion en blanco y que mande un mensaje de obligacion
        if ($nombre === "" || $login === "" || $pass === "" || $conf === "") {
        $msg = "Todos los campos son obligatorios.";
        } 
        // Checamos que las contraseñas coincidan
        elseif ($pass !== $conf) {
            $msg = "Las contraseñas no coinciden.";
        } 
        else {
            // Verificamos si el usuario existe antes de seguir y sino hace uno en blanco
            $usuarios = [];
            if (file_exists($archivo)) {
                $contenido = file_get_contents($archivo);
                $usuarios = json_decode($contenido, true);
            }

        // Si no fue valido lo deja en blanco
        if ($usuarios == "") {
            $usuarios = [];
        }

        // Verificamos si el usuario (login) ya existe
        $existe = false;
        foreach ($usuarios as $u) {
            if (strtolower($u['login']) === strtolower($login)) {
                $existe = true;
                break;
            }
        }
        if ($existe) {
            $msg = "El nombre de usuario ya existe.";
        } else {
            // Finalmente confirmamos al nuevo usuario y su informacion
            // Ademas se hashea la contraseña con el metodo solicitado
            $usuarios[] = [
                "nombre" => $nombre,
                "login"  => $login,
                "pass"   => password_hash($pass, PASSWORD_DEFAULT)
            ];
            
            // Manda la informacion en el codigo usuarios.json y entrega un mensaje de exito 
            file_put_contents($archivo, json_encode($usuarios, JSON_PRETTY_PRINT));
            $msg = "¡Usuario guardado con exito!";
        }
    }
}
?>

<!-- Lectura y estructura del formulario PHP-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Registro de Usuario</h2>
    
    <!-- Mensaje de operacion exitosa -->
    <?php if ($msg !== ""): ?>
        <p style="font-weight: bold; color: green;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre completo:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($nombre ?? '') ?>" required>

        <label>Nombre de usuario (login):</label>
        <input type="text" name="login" value="<?= htmlspecialchars($login ?? '') ?>" required>

        <label>Contraseña:</label>
        <input type="password" name="pass" required>

        <label>Confirmar contraseña:</label>
        <input type="password" name="conf" required>

        <button type="submit">Guardar Registro</button>
    </form>
</body>
</html>