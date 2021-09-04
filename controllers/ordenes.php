<?php
    class ordenes extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerOrdenesUser($_SESSION['user']);
            $this->view->render('ordenes/index');
        }

        function genNumeroOrden(){
            $result = $this->model->generarNumeroOrden();

            echo $result;
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
        
    }
?>