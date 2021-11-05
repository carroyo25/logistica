<?php
    class CargoPlan extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu   = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerRegistros();
            $this->view->render('cargoplan/index');
        }
        
    }
?>