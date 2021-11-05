<?php
    class Calidad extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros

            $this->view->render('calidad/index');
        }

        function llamaIngresoPorId(){
            $nota = $_POST['nota'];

            $result = $this->model->cambiarNota($nota);

            echo json_encode($result);
        }
        
        function llamarDetallesCodigo(){
            $idx = $_POST['index'];

            $result = $this->model->listaDetallesCodigo($idx);

            echo $result;
        }

        function llamarSeries(){
            $idx = $_POST['index'];
            $prod = $_POST['prod'];

            $result = $this->model->listarSeriesProducto($idx,$prod);

            echo $result;
        }

        function llamarAdjuntos(){
            $idx = $_POST['index'];

            $result = $this->model->listarAdjuntosCodigos($idx);

            echo $result;
        }

        function cambiarEstado(){
            $index = $_POST['ningreso'];
            $detalles = $_POST['detalles'];
            $orden = $_POST['detalles'];
            $pedido = $_POST['pedido'];
            $entidad = $_POST['entidad'];
            $puntaje = $_POST['califica'];

            $result = $this->model->actualizarEstadoNota($index,$detalles,$pedido,$orden,$entidad,$puntaje);

            echo $result;
        }

        function refreshMain() {
            $result = $this->model->getMainRecords();

            echo $result;
        }
        
    }
?>