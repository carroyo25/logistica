<?php
    class Proyectos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->actives    = $this->model->getTotals(1);
            $this->view->inactives  = $this->model->getTotals(0);
            $this->view->render('proyectos/index');
        }

        function createCode(){
            $salida = $this->model->generateCode();

            echo str_pad($salida,2,"0",STR_PAD_LEFT);
        }

        function exist(){
            $cod = $_POST['codigo'];
            $des = $_POST['descripcion'];

            $result = $this->model->checkExist($cod,$des);

            echo $result;
        }

        function newReg(){
            $cod = $_POST['codigo_proyecto'];
            $dep = $_POST['descripcion_proyecto'];
            $ubi = $_POST['ubicacion_proyecto'];
            $res = $_POST['responsable_proyecto'];
            $est = $_POST['estado'];

            $datos = compact("cod","dep","ubi","res","est");

            $result = $this->model->insert($datos);

            echo $result;
        }

        function updReg(){
            $idx = $_POST['index_proyecto'];
            $cod = $_POST['codigo_proyecto'];
            $dep = $_POST['descripcion_proyecto'];
            $ubi = $_POST['ubicacion_proyecto'];
            $res = $_POST['responsable_proyecto'];
            $est = $_POST['estado'];

            $datos = compact("idx","cod","dep","ubi","res","est");

            $result = $this->model->insert($datos);

            echo $result;
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