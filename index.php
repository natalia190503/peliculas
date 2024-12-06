<?php

//require_once 'Procesar.php';
require_once 'PeliculasModel.php';
$bd = new PeliculasModel;
//$procesa = new Procesar();

if(isset($_REQUEST['guardar']) && isset($_POST['pelicula']) && isset($_POST['protagonistas']) && isset($_POST['genero']) && isset($_POST['clasificacion']) && isset($_POST['duracion']) && isset($_FILES['portada'])){
    $msj = $bd->guardar(
        $_POST['pelicula'], $_POST['protagonistas'], $_POST['genero'], $_POST['clasificacion'], $_POST['duracion'], $_FILES['portada']
    );
}
else if(isset($_REQUEST['editar']) && isset($_POST['id']) && isset($_POST['pelicula']) && isset($_POST['protagonistas']) && isset($_POST['genero']) && isset($_POST['clasificacion']) && isset($_POST['duracion'])){
    $msj = $bd->editar(
        $_POST['id'], $_POST['pelicula'], $_POST['protagonistas'], $_POST['genero'], $_POST['clasificacion'], $_POST['duracion'], $_FILES['portada']
    );
}
else if(isset($_REQUEST['eliminar']) && isset($_POST['id'])){
    $msj = $bd->eliminar($_POST['id']);
}

$peliculas = $bd->getPeliculas();
//$peliculas = $procesa->leer();
//print_r($procesa->leer());

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
    <link href="dist/assets/app-DqRQ08a8.css" rel="stylesheet">
</head>
<body>
    <div class="cobtainer-fluid">
        <?php if(isset($msj)):?>
        <div class="row mt-3" id="msj">
            <div class="col-md-2 offset-md-4">
                <div class="alert alert-success text-center">
                    <?=$msj;?>
                </div>
            </div>
        </div>
        <?php endif;?>
        <div class="row mt-3">
            <div class="col-md-4 offset-md-5">
                <a href="registro.php" class="btn btn-success">Añadir</a>
            </div>
        </div>
        <div class="row mt-3 offset-md-5">
            <div class="col-md-3">
                <a href="pdf.php" target="_blank" class="btn btn-danger">Exportar</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>#</th> 
                                <th>Nombre</th>
                                <th>Protagonistas</th>
                                <th>Género</th>
                                <th>Clasificación</th>
                                <th>Duración</th>
                                <th>Portada</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($peliculas as $i=> $row):
                            ?>  <tr>
                                    <td><?=$i+1;?></td>
                                    <td><?=$row->pelicula;?></td>
                                    <td><?=$row->protagonistas;?></td>
                                    <td><?=$row->genero;?></td>
                                    <td><?=$row->clasificacion;?></td>
                                    <td><?=$row->duracion;?></td>
                                    <td><img src="imagenes/<?=$row->portada;?>" height="100px"></td>
                                    <td>
                                        <a class="btn btn-warning" href="editar.php?p=<?=$row->id;?>"><i class="bi bi-pencil-square"></i></a></td>
                                    <td>
                                        <form action="index.php" method="post">
                                            <input type="hidden" name="id" value="<?=$row->id;?>">
                                            <button name="eliminar" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            endforeach
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="dist/assets/app-D8CZHRwK.js"></script>
    <script src="index.js"></script>
</body>
</html>
