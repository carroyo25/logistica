<?php
    class Despacho extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->getMainRecords();
            $this->view->modalidad = $this->model->getParameters(22);
            $this->view->tipoenvio = $this->model->getParameters(7);
            $this->view->almacenes = $this->model->obtenerAlmacenes();
            $this->view->personal  = $this->model->obtenerPersonal();

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