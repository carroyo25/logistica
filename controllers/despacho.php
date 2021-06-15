<?php
    class Despacho extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->getMainRecords();
            $this->view->motivos    = $this->model->getMovs();
            $this->view->render('despacho/index');
        }

        function notas(){
            $result = $this->model->llamarNotas();

            echo $result;
        }

        function notaId(){
            $idx = $_POST['idx'];

            $result = $this->model->llamarIngresoPorID($idx);

            echo json_encode($result);
        }

        function detallesIngresosId(){
            $idx = $_POST['idx'];

            $result = $this->model->llamarDetalleIngresoPorID($idx);

            echo $result;
        }
        
    }
?>