<?php

class PeliculasModel{
    private $conexion;

    public function __construct(){
        $dsn = 'mysql:dbname=peliculasdb;host=35.202.253.51';
        $usuario = 'uvp';
        $pass = 'AsDqWe123!';
        $opciones = [PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'UTF8\''];
        $this->conexion = new PDO($dsn, $usuario, $pass, $opciones);
    }

    function getPeliculas($id = null){
        $where = ($id != null) ? "  WHERE id = '$id' " : ""; //IF simplificado
        $peliculas = [];
        $sql = "SELECT * FROM peliculas ".$where;
        $rows = $this->conexion->query($sql);
        $peliculas = $rows->fetchAll();//Devuelve un array que contiene todas las filas restantes del conjunto de resultados.
        return json_decode(json_encode($peliculas));
    }

    function guardar($pe, $prota, $gen, $cla, $dura, $file){
        $sql = "INSERT INTO peliculas(pelicula, protagonistas, genero, clasificacion, duracion, portada) VALUES (?,?,?,?,?,?)";
        $comando = $this->conexion->prepare($sql);
        $comando->execute([$pe, $prota, $gen, $cla, $dura, 'imagenes']);
        $id = $this->conexion->lastInsertId();
        
        //Imagen
        $ext = substr($file['name'], -3); //Tipo de la imagen que se requiere guardar.
        $img = $id.'.'.$ext;
        $this->guardaImagen($img, $file);
        $this->actualizaImagen($img, $id);
        return $id;
    }
    function editar($id, $pe, $prota, $gen, $cla, $dura, $file){
        $foto = ($file['name'] != '') ? " ,portada = ? " : "";//O vale nombre de la imagen o no vale nada.
        $sql = "UPDATE peliculas SET pelicula = ? , protagonistas = ? , genero = ? , clasificacion = ? , duracion = ? $foto WHERE id = ?";
        $comando = $this->conexion->prepare($sql);

        if($file['name'] != ''){
            //Obtiene el nombre de la imagen.
            $peli = $this->getPeliculas($id);
            $img = $peli[0]->portada;
            //Elimina la imagen.
            $this->eliminarImagen();
            $ext = substr($file['name'], -3); //Tipo de la imagen que se requiere guardar.
            $img = $id.'.'.$ext;
            $this->guardaImagen($img, $file);
        }

        $valores = ($file['name'] != '') ? [$pe, $prota, $gen, $cla, $dura, $img, $id] : [$pe, $prota, $gen, $cla, $dura, $id];
        $comando->execute($valores);
    }
    function eliminar($id){
        //Obtiene el nombre de la imagen.
        $peli = $this->getPeliculas($id);
        $img = $peli[0]->portada;
        //Elimina la película.
        $sql = "DELETE FROM peliculas WHERE id = ?";
        $comando = $this->conexion->prepare($sql);
        $comando->execute([$id]);
        //Elimina la imagen.
        $this->eliminarImagen($img);
    }
    function actualizaImagen($img, $id){
        $sql = "UPDATE peliculas SET portada = ? WHERE id = ?";
        $comando = $this->conexion->prepare($sql);
        $comando->execute([$img, $id]);
    }
    function guardaImagen($img, $file) :void{
        $ubicacion = __DIR__.'/imagenes/'.$img;
        copy($file['tmp_name'], $ubicacion); //tmp es la propiedad de la imagen.
    }
    function eliminarImagen($img) : void{
        unlink(__DIR__.'/imagenes/'.$img);
    }
}