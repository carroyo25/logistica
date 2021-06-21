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
            $this->view->motivos   = $this->model->getMovs();

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

        function preview(){
            $ingreso =  $_POST['ndoc'];
            $condicion = $_POST['condicion'];
            $fecha = $_POST['fecha'];
            $proyecto = $_POST['proyecto'];
            $origen = $_POST['origen'];
            $movimiento = $_POST['movimiento'];
            $orden = $_POST['orden'];
            $pedido = $_POST['pedido'];
            $guia = $_POST['guia'];
            $autoriza = $_POST['autoriza'];
            $cargo = $_POST['cargo'];
            $entidad = $_POST['entidad'];
            $details = $_POST['details'];
            $tipo = $_POST['tipo'];

            $result = $this->model->genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,$orden,$pedido,$guia,$autoriza,$cargo,$entidad,$details,$tipo);

            echo $result;
        }
        
    }
?>