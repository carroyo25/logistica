<?php
    class Ingresos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->almacenes  = $this->model->getWarehouses();
            $this->view->motivos    = $this->model->getParameters();
            $this->view->aprueba   = $this->model->getPersonal();
            //$this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            
            $this->view->render('ingresos/index');
        }

        function nroIngreso(){
            $cod = $_POST['data'];

            $result = $this->model->genNumber($cod);

            echo str_pad($result,6,"0",STR_PAD_LEFT);
        }

        function ordenes(){
            $result =  $this->model->getOrders();

            echo $result;
        }

        function detallesOrdenes(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrderDetails($cod);

            echo $result;
        }

        function importOrder(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrdersByNumer($cod);

            echo json_encode($result);
        }

        function detailsOrder(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrderDetails($cod);

            echo $result;
        }
        
    }
?>