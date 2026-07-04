<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: login.html");
    exit();
}

require_once "clases/Equipo.php";


if (isset($_POST['Guardar'])) {

    // validacion basica de requeridos
    if (empty($_POST['codigo_inventario']) || empty($_POST['marca']) ||
        empty($_POST['modelo']) || empty($_POST['id_sucursal'])) {
        header("Location: alta_equipo.php?error=Faltan campos obligatorios");
        exit();
    }

    // manejo de la foto (opcional)
    $rutaFoto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {

        $nombreArchivo = basename($_FILES['foto']['name']);
        // le agrego un prefijo con la hora para evitar que se pisen fotos con el mismo nombre
        $nombreUnico = time() . "_" . $nombreArchivo;
        $destino     = "uploads/equipos/" . $nombreUnico;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $rutaFoto = $destino;
        }
    }

    // crear el equipo y llenar los datos del form
    $equipo = new Equipo();
    $equipo->setCodigoInventario($_POST['codigo_inventario']);
    $equipo->setMarca($_POST['marca']);
    $equipo->setModelo($_POST['modelo']);
    $equipo->setAnioAdquisicion($_POST['anio_adquisicion']);
    $equipo->setValorEstimado($_POST['valor_estimado']);
    $equipo->setTipo($_POST['tipo']);
    $equipo->setEstado($_POST['estado']);
    $equipo->setIdSucursal($_POST['id_sucursal']);
    $equipo->setFoto($rutaFoto);

    $resultado = $equipo->guardar();

    if ($resultado) {
        header("Location: alta_equipo.php?ok=1");
    } else {
        header("Location: alta_equipo.php?error=No se pudo guardar el equipo");
    }
    exit();
}

header("Location: alta_equipo.php");
exit();
?>
