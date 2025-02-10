<?php

class otrosController{
    protected otrosRepositorio $otrosRepositorio;


    public function __construct(otrosRepositorio $otrosRepositorio)
    {
        $this->otrosRepositorio = $otrosRepositorio;
    }

    public function admin(){
        include '../app/views/admin.php';
    }


    public function catalogo(){
        $productos=$this->otrosRepositorio->catalogo();
        include '../app/views/catalogo.php';
    }

    public function index(){
        $productos=$this->otrosRepositorio->index();
        include '../app/views/index.php';

    }


}









?>