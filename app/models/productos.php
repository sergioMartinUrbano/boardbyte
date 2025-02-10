<?php

class Productos{
    private $id_producto;
    

    protected $nombre;

    protected $precio;

    protected $fotoPortada;

    protected $descripcionCorta;

    protected $descripcionCompleta;

    protected $arrayFotos;

    public function __construct($id_producto, $nombre, $precio, $fotoPortada, $descripcionCorta, $descripcionCompleta)
    {
        $this->id_producto = $id_producto;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->fotoPortada = $fotoPortada;
        $this->descripcionCorta = $descripcionCorta;
        $this->descripcionCompleta = $descripcionCompleta;
        $this->arrayFotos = [];
    }

    public function getID(){
        return $this->id_producto;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getPrecio(){
        return $this->precio;
    }

    public function getFotoPortada(){
        return $this->fotoPortada;
    }

    public function getDescripcionCorta(){
        return $this->descripcionCorta;
    }

    public function getDescripcionCompleta(){
        return $this->descripcionCompleta;
    }

    public function getFotosAdicionales(){
        return $this->arrayFotos;
    }


    public function addFotos($fotoNombre){
        array_push($this->arrayFotos,$fotoNombre);
    }

    

    


}



