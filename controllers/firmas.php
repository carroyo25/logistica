<?php
    class Firmas extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->getMainRecords();
            $this->view->render('firmas/index');
        }

        function ordHeader(){
            $codigo = $_POST['cod'];

            $result = $this->model->getOrdHeader($codigo);

            echo json_encode($result);
        }

        function pedHeader(){
            $codigo = $_POST['cod'];
            
            $result = $this->model->getPedHeader($codigo);

            echo json_encode($result);
        }

        function orcDetails(){
            $codigo = $_POST['cod'];
            $pedido = $_POST['pedido'];

            $result = $this->model->getOrderDetails($codigo,$pedido);

            echo $result;
        }

        function pedDetails(){
            $codigo = $_POST['cod'];

            $result = $this->model->getPedDetails($codigo);

            echo $result;
        }

        function adjFiles(){
            $codigo = $_POST['cod'];
        }

        function newComment(){
            $comment = $_POST['comment'];
            $regmov  = $_POST['regmov'];
            $date    = $_POST['date'];

            $result = $this->model->insertComment($comment,$regmov,$date);

            echo $result;

        }

        function listComments(){
            $codigo = $_POST['cod'];

            $result = $this->model->getComments($codigo);

            echo $result;
        }

        function signature(){
            $codigo = $_POST['cod'];
            $cargo = $_POST['car'];

            $result = $this->model->signOrder($codigo,$cargo);

            echo $result;
        }

        function listaOrdenes(){
            $result = $this->model->getMainRecords();

            echo $result;
        }
        
    }
?>