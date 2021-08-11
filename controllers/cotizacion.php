<?php
    class Cotizacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->getMainRecords();
            $this->view->render('cotizacion/index');
        }

        function regById(){
            $cod = $_POST['data'];

            $return = $this->model->getRegById($cod);

            echo json_encode($return);
        }

        //detalles del pedido
        function detailsById()
        {
            $cod = $_POST['cod'];
            $tip = $_POST['tip'];

            $return = $this->model->getDetailsById($cod,$tip);

            echo $return;
        }

        //listado de los proveedores
        function distEmails() {
            $return = $this->model->getDistMails();

            echo $return;
        }

        //enviar los correos electronicos
        function mails() {
            $mails = $_POST['mails'];
            $items = $_POST['items'];

            //$result = $this->model->sendMails($mails,$items);

            //echo $result;
        }

        function enviaEnlace(){
            $mails = $_POST['mails'];
            $items = $_POST['items'];

            $result = $this->model->sendMails($mails,$items);

            echo $result;
        }

        //cambiar el estado del pedido
        function close(){
            $cod = $_POST['cod'];

            $result = $this->model->changeStatus($cod);

            echo $result;
        }

        //cambiar prioridad de atencion
        function priority(){
            $cod = $_POST['cod'];

            $result = $this->model->changePriority($cod);

            echo $result;

        }
    }
?>