<?php
    class ordenes extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerOrdenesUser($_SESSION['user']);
            $this->view->almacenes = $this->model->obtenerAlmacenes();
            $this->view->render('ordenes/index');
        }

        function genNumeroOrden(){
            $result = $this->model->generarNumeroOrden();

            echo json_encode($result);
        }


        function pedidosCostos(){
            $user = $_SESSION['id_user'];

            $result = $this->model->verPedidoActivados($user);

            echo $result;
        }

        function caberaPedidoID(){
            $id = $_POST['id'];

            $result = $this->model->obtenerPedidoPorId($id);

            echo json_encode($result);
        }

        function detallesPedido(){
            $id = $_POST['id'];

            $result = $this->model->obtenerDetallesPorId($id);

            echo $result;
        }
        
        function datosProforma(){
            $entidad = $_POST['ident'];
            $pedido = $_POST['pedido'];

            $result = $this->model->consultarProformas($entidad,$pedido);

            echo json_encode($result);
        }

        function enviaDatosOrden(){
            $cabecera = $_POST['cabecera'];
            $detalles = $_POST['detalles'];
            $condicion = $_POST['codicion'];

            $result = $this->model->pasarDatosOrden($cabecera,$detalles,$condicion);

            echo $result;
        }

        function grabaOrden(){
            $cabecera = $_POST['cabecera'];
            $detalles = $_POST['detalles'];

            $result = $this->model->grabarDatosOrden($cabecera,$detalles);

            echo $result;
        }

        function ordenesPorId(){
            $cod = $_POST['cod'];

            $result = $this->model->obtenerOrdenesId($cod);

            echo json_encode($result);
        }

        function detallesOrden(){
            $cod = $_POST['cod'];
            $ped = $_POST['pedido'];

            $result = $this->model->obtenerDetallesOrden($cod,$ped);

            echo $result;
        }


        function observaciones(){
            $observaciones = $_POST['observaciones'];
            
            $result = $this->model->grabarComentarios($observaciones);

            echo $result;
        }

        function consultaObservaciones() {
            $cod = $_POST['orden'];

            $result = $this->model->obtenerComentarios($cod);

            echo $result;
        }

        function correoProveedor(){
            $cabecera = $_POST['cabecera'];
            $detalles = $_POST['detalles'];
            $condicion = $_POST['codicion'];

            $result = $this->model->pasarDatosOrden($cabecera,$detalles,$condicion);

            echo $result;
        }

        function actualizaPrincipal(){
            $result = $this->model->obtenerOrdenesUser($_SESSION['user']);

            echo $result;
        }
        
    }
?>