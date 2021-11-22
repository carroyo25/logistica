<?php
    class Evaluacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu   = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerRegistros();
            $this->view->render('evaluacion/index');
        }
        
        function calificacion(){
            $id = $_POST['id'];
            $enti = $_POST['enti'];

            $result = $this->model->calificar($id,$enti);

            echo json_encode($result);
        }
    }
?>