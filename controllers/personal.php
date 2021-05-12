<?php
    class Personal extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->actives    = $this->model->getTotals('AC');
            $this->view->inactives  = $this->model->getTotals('CE');
            $this->view->render('personal/index');
        }
        

        function listAll(){
            $salida =  $this->model->getall();

            echo $salida;
        }

        function listById() {
            $idx = $_POST["idx"];

            $salida = $this->model->getItemById($idx);

            echo json_encode($salida);
        }
    }
?>