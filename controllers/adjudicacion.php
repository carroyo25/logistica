<?php
    class Adjudicacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            $this->view->render('adjudicacion/index');
        }

        function regById(){
            $cod = $_POST['data'];

            $return = $this->model->getRegById($cod);

            echo json_encode($return);
        }

        function Proformas(){
            $cod = $_POST['cod'];

            $return = $this->model->obtenerProformas($cod);

            echo $return; 
        }

    }
?>