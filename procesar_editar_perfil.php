<?php
session_start();

if (!isset($_SESSION['ci'])) {
    header("Location: login.html");
    exit();
}

require_once "clases/Usuario.php";


if (isset($_POST['Guardar'])) {

    // validacion server-side basica
    if (empty($_POST['primer_nombre']) || empty($_POST['primer_apellido']) || empty($_POST['email'])) {
        header("Location: editar_perfil.php?error=Faltan campos obligatorios");
        exit();
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        header("Location: editar_perfil.php?error=Email invalido");
        exit();
    }

    // password nueva es opcional (solo cambia si el usuario la escribio)
    if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
        header("Location: editar_perfil.php?error=Password minimo 8 caracteres");
        exit();
    }

    // cargo el usuario actual desde la base para preservar los campos que no se editan
    $usuario = new Usuario();
    if (!$usuario->cargar($_SESSION['ci'])) {
        header("Location: editar_perfil.php?error=No se encontro el usuario");
        exit();
    }

    // actualizo solo los campos editables (letra pag 5)
    $usuario->setPrimerNombre($_POST['primer_nombre']);
    $usuario->setSegundoNombre($_POST['segundo_nombre']);
    $usuario->setPrimerApellido($_POST['primer_apellido']);
    $usuario->setSegundoApellido($_POST['segundo_apellido']);
    $usuario->setFechaNacimiento($_POST['fecha_nacimiento']);
    $usuario->setEmail($_POST['email']);

    // si escribio password nueva, la cambio (setPassword hashea)
    if (!empty($_POST['password'])) {
        $usuario->setPassword($_POST['password']);
    }

    // foto nueva reemplaza a la vieja solo si subio una
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['foto']['name']);
        $nombreUnico   = time() . "_" . $nombreArchivo;
        $destino       = "uploads/usuarios/" . $nombreUnico;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $usuario->setFoto($destino);
        }
    }

    // guardo (Usuario::guardar detecta que existe y hace UPDATE)
    $resultado = $usuario->guardar();

    if ($resultado) {
        header("Location: editar_perfil.php?ok=1");
    } else {
        header("Location: editar_perfil.php?error=No se pudo actualizar el perfil");
    }
    exit();
}

header("Location: editar_perfil.php");
exit();
?>
