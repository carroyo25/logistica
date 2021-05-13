<?php
    class Ingresos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            //$this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            
            $this->view->render('ingresos/index');
        }
        
    }
?>