<?php
    class SalidaAlmacen extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->almacenes = $this->model->obtenerAlmacenes($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerRegistros();
            $this->view->render('salidaalmacen/index');
        }

        function salidaPorId(){
            $id = $_POST["id"];

            $result = $this->model->consultarSalidaId($id);

            echo json_encode($result);
        }

        function detallesId(){
            $id = $_POST['id'];

            $result = $this->model->consultarDetalleSalida($id);

            echo $result;
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
            $cabecera = $_POST['cabecera'];
            $detalles = $_POST['detalles'];

            $result = $this->model->grabarSalida($cabecera,$detalles);

            echo $result;
        }

        function inicialesItems(){
            $result = $this->model->obtenerInicialesProductos();
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