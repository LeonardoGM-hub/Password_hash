//se uso como referencia el codigo de registros en Base de datos SQL,
// de una manera local.
<?php
$archivo = "usuarios.json";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $login  = trim($_POST["login"] ?? "");
    $pass   = $_POST["pass"] ?? "";
    $conf   = $_POST["conf"] ?? "";

    if (strpos($login, ' ') !== false)       $msg = "El login no debe llevar espacios.";
    if (empty($nombre) || empty($login))    $msg = "Todos los campos son obligatorios.";
    if ($pass !== $conf)                    $msg = "Las contraseñas no coinciden.";

    if (empty($msg)) {
        $usuarios = file_exists($archivo) ? (json_decode(file_get_contents($archivo), true) ?? []) : [];
        $existe = false;
        foreach ($usuarios as $u) {
            if (strtolower($u['login']) === strtolower($login)) { $existe = true; break; }
        }

        if ($existe) {
            $msg = "El nombre de usuario ya existe.";
        } else {

            $usuarios[] = [
                "nombre" => $nombre,
                "login"  => $login,
                "pass"   => password_hash($pass, PASSWORD_DEFAULT)
            ];
            
            file_put_contents($archivo, json_encode($usuarios, JSON_PRETTY_PRINT));
            $msg = "¡Usuario guardado con exito!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Registro de Usuario</h2>
    
    <?php if (!empty($msg)): ?>
        <p style="font-weight: bold; color: green;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST">

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