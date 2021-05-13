<?php
    class Seguimiento extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->getMainRecords();
            $this->view->render('seguimiento/index');
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

        function genOrder(){
            $codigo = $_POST['orden'];

            $result = $this->model->generateDocument($codigo);

            echo $result;
        }

        function changePed(){
            $idorden  = $_POST['idorden'];
            $pedido   = $_POST['ped'];

            $result = $this->model->updatePed($idorden,$pedido);

            echo $result;
        }

        function mail(){
            $orden = $_POST['idorden'];
            $entidad = $_POST['entidad'];

            $result = $this->model->sendMail($orden,$entidad);

            echo $result;
        }
    }
?>