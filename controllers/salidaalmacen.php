<?php
    class SalidaAlmacen extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->almacenes = $this->model->obtenerAlmacenes($_SESSION['id_user']);
            //$this->view->registros = $this->model->obtenerGuiasDespacho($_SESSION['id_user']);
            $this->view->render('salidaalmacen/index');
        }

        function solicitante(){
            $doc = $_POST['doc'];

            $result = $this->model->obtenerNombreSolicitante($doc);

            echo json_encode($result);
        }

        function productosPorAlmacen(){
            $almacen = $_POST['cod'];

            $result = $this->model->obtenerStock($almacen);

            echo $result;
        }

        function registroSalida(){

        }

        function actualizaExistencia(){

        }

        function inicialesItems(){
            $result = $this->model->obtenerIncialesProductos();
            echo $result;
        }

        function itemPorInicial(){
            $inic = $_POST['letra'];
            $almacen = $_POST['almacen'];

            $result = $this->model->consultarPorInicial($inic,$almacen);

            echo $result;
        }
        

        function itemPorPalabra(){
            $palabra = $_POST['palabra'];
            $almacen = $_POST['almacen'];

            $result = $this->model->consultarPorPalabra($palabra,$almacen);

            echo $result;
        }
    }
?>