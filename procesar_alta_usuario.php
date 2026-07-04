<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.html");
    exit();
}

require_once "clases/Usuario.php";


if (isset($_POST['Guardar'])) {

    // validacion server-side basica
    if (empty($_POST['ci']) || empty($_POST['primer_nombre']) || empty($_POST['primer_apellido']) ||
        empty($_POST['email']) || empty($_POST['password']) ||
        empty($_POST['tipo_usuario']) || empty($_POST['id_sucursal'])) {
        header("Location: alta_usuario.php?error=Faltan campos obligatorios");
        exit();
    }

    // password minimo 8 caracteres (regla de la letra)
    if (strlen($_POST['password']) < 8) {
        header("Location: alta_usuario.php?error=Password minimo 8 caracteres");
        exit();
    }

    // validar formato de email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        header("Location: alta_usuario.php?error=Email invalido");
        exit();
    }

    // subir foto si vino en el form
    $rutaFoto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['foto']['name']);
        $nombreUnico   = time() . "_" . $nombreArchivo;
        $destino       = "uploads/usuarios/" . $nombreUnico;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $rutaFoto = $destino;
        }
    }

    // crear el usuario
    $usuario = new Usuario();
    $usuario->setCi($_POST['ci']);
    $usuario->setPrimerNombre($_POST['primer_nombre']);
    $usuario->setSegundoNombre($_POST['segundo_nombre']);
    $usuario->setPrimerApellido($_POST['primer_apellido']);
    $usuario->setSegundoApellido($_POST['segundo_apellido']);
    $usuario->setFechaNacimiento($_POST['fecha_nacimiento']);
    $usuario->setEmail($_POST['email']);
    $usuario->setPassword($_POST['password']);   // el setter hashea internamente
    $usuario->setFoto($rutaFoto);
    $usuario->setTipoUsuario($_POST['tipo_usuario']);
    $usuario->setIdSucursal($_POST['id_sucursal']);
    $usuario->setActivo(1);

    $resultado = $usuario->guardar();

    if ($resultado) {
        header("Location: alta_usuario.php?ok=1");
    } else {
        header("Location: alta_usuario.php?error=No se pudo guardar el usuario");
    }
    exit();
}

header("Location: alta_usuario.php");
exit();
?>
