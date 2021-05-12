<?php 
    class Errores extends Controller{
        function __construct()
        {
            parent::__construct();
            $this->view->render('errores/index');
            $this->view->mensaje="Error al cargar el recurso";
        }
    }
?>