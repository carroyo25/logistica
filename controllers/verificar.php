<?php
    class Verificar extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->verPedidosUser($_SESSION['user']);
            $this->view->render('verificar/index');
        }

        function verPedido(){
            $cod = $_POST["cod"];

            $result = $this->model->llamarCabeceraPedido($cod);

            echo json_encode($result);
        }

        function verDetalle(){
            $cod = $_POST['cod'];
            $tipo = $_POST['tipo'];

            $result = $this->model->llamarDetallePedido($cod,$tipo);

            echo $result;
        }

        function darConformidad(){
            $cod = $_POST['cod'];
            $detalles = $_POST['detalles'];
            
            $result = $this->model->actualizarPedido($cod,$detalles);

            echo $result;
        }
        
    }
?>