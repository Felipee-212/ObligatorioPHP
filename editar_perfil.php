<?php
session_start();

// cualquier usuario logueado puede editar su propio perfil
if (!isset($_SESSION['ci'])) {
    header("Location: login.html");
    exit();
}

require_once "clases/Usuario.php";

// cargo los datos actuales del usuario logueado para pre-llenar el form
$usuario = new Usuario();
$usuario->cargar($_SESSION['ci']);

$ok    = isset($_GET['ok']);
$error = isset($_GET['error']) ? $_GET['error'] : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>
    <fieldset>
        <legend align="center">Editar Perfil</legend>

        <?php if ($ok): ?>
            <p align="center"><b>Perfil actualizado correctamente.</b></p>
        <?php endif; ?>

        <?php if ($error != ""): ?>
            <p align="center"><b>Error: <?php echo $error; ?></b></p>
        <?php endif; ?>

        <table align="center">
            <tr><td>
                <form action="procesar_editar_perfil.php" method="POST" enctype="multipart/form-data">

                    <!-- campos NO editables segun la letra: CI y tipo de usuario -->
                    <div>
                        CI (no editable):
                        <p><input type="text" value="<?php echo $usuario->getCi(); ?>" disabled></p>
                    </div>
                    <div>
                        Tipo (no editable):
                        <p><input type="text" value="<?php echo $usuario->getTipoUsuario(); ?>" disabled></p>
                    </div>

                    <!-- campos editables -->
                    <div>
                        Primer nombre:
                        <p><input type="text" name="primer_nombre" value="<?php echo $usuario->getPrimerNombre(); ?>" required></p>
                    </div>
                    <div>
                        Segundo nombre:
                        <p><input type="text" name="segundo_nombre" value="<?php echo $usuario->getSegundoNombre(); ?>"></p>
                    </div>
                    <div>
                        Primer apellido:
                        <p><input type="text" name="primer_apellido" value="<?php echo $usuario->getPrimerApellido(); ?>" required></p>
                    </div>
                    <div>
                        Segundo apellido:
                        <p><input type="text" name="segundo_apellido" value="<?php echo $usuario->getSegundoApellido(); ?>"></p>
                    </div>
                    <div>
                        Fecha de nacimiento:
                        <p><input type="date" name="fecha_nacimiento" value="<?php echo $usuario->getFechaNacimiento(); ?>"></p>
                    </div>
                    <div>
                        Email:
                        <p><input type="email" name="email" value="<?php echo $usuario->getEmail(); ?>" required></p>
                    </div>
                    <div>
                        Nueva password (dejar vacio para no cambiar):
                        <p><input type="password" name="password" minlength="8" placeholder="Minimo 8 caracteres"></p>
                    </div>
                    <div>
                        Foto actual:
                        <p>
                            <?php if ($usuario->getFoto() != ""): ?>
                                <img src="<?php echo $usuario->getFoto(); ?>" width="100">
                            <?php else: ?>
                                <i>Sin foto</i>
                            <?php endif; ?>
                        </p>
                        Cambiar foto (opcional):
                        <p><input type="file" name="foto" accept="image/*"></p>
                    </div>

                    <input type="submit" name="Guardar" value="Guardar cambios">
                </form>
            </td></tr>
        </table>
    </fieldset>
</body>
</html>
