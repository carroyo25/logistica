<?php
    class Tcambio extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->registros=$this->model->getAllRecords();
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            //$this->view->flags = $this->model->getAccess(1,$_SESSION['id_user']);
            $this->view->render('tcambio/index');
        }

        function regTipoCambio(){
            $fec =$_POST['fecha'];
            $mon =$_POST['moneda'];
            $com =$_POST['compra'];
            $ven =$_POST['venta'];

            $data = compact("fec","mon","com","cpr","ven");

            $result = $this->model->insert($data);
        }
    }
?>