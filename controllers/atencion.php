<?php
    class Atencion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu         = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros    = $this->model->getAllUserRecords($_SESSION['id_user']);
            $this->view->render('atencion/index');
        }

        function consultaDocumentoId(){
            $idx = $_POST['cod'];

            $result = $this->model->obtenerCabecera($idx);

            echo json_encode($result);
        }

        function consultarDetalles(){
            $idx = $_POST['cod'];
            $tipo = $_POST['tipo'];

            $result = $this->model->obtenerDetallesId($idx,$tipo);

            echo $result;
        }

        function almacenUsuario(){
            $cod = $_POST['cod'];

            $result = $this->model->obtenerStock($cod);

            echo $result;
        }

        function grabaAtencion(){
            $idx = $_POST['idx'];
            $detalles = $_POST['detalles'];

            $result = $this->model->actualizaDetalle($idx,$detalles);

            echo $result;
        }
        
    }
?>