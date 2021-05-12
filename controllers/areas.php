<?php
    class Areas extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->actives    = $this->model->getTotals(1);
            $this->view->inactives  = $this->model->getTotals(0);
            $this->view->render('areas/index');
        }

        function createCode(){
            $salida = $this->model->generateCode();

            echo str_pad($salida,2,"0",STR_PAD_LEFT);
        }

        function newReg(){
            $cod = $_POST['codigo_area'];
            $dea = $_POST['descripcion_area'];
            $est = $_POST['estado'];

            $datos = compact("cod","dea","est");

            $salida = $this->model->insert($datos);

            echo $salida;
        }

        function updReg(){
            $idx = $_POST['index_area'];
            $cod = $_POST['codigo_area'];
            $dea = $_POST['descripcion_area'];
            $est = $_POST['estado'];

            $datos = compact("idx","cod","dea","est");

            $salida = $this->model->update($datos);

            echo $salida;
        }

        function delReg(){
            $idx = $_POST['idx'];

            $salida = $this->model->delete($idx);

            echo $salida;
        }

        function listAll(){
            $salida = $this->model->getallItems();

            echo $salida;
        }

        function listById(){
            $idx = $_POST['idx'];

            $salida = $this->model->getItemById($idx);

            echo json_encode($salida);
        }
        
    }
?>