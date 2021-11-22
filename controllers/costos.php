<?php
    class Costos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->actives    = $this->model->getTotals(1);
            $this->view->inactives  = $this->model->getTotals(0);
            $this->view->proyectos  = $this->model->listarProyectos();
            $this->view->render('costos/index');
        }

        function exist(){
            $cod = $_POST['codigo_centro'];
            $des = $_POST['descripcion_centro'];

            $datos = compact("cod","des");

            $salida = $this->model->checkExist($datos);

            echo $salida;
        }

        function newReg(){
            $cod = $_POST['codigo_centro'];
            $des = $_POST['descripcion_centro'];
            $est = $_POST['estado'];
            $proy = $_POST['cod_proy'];

            $datos = compact("cod","des","est","proy");

            $salida = $this->model->insert($datos);

            echo $salida;
        }

        function updReg(){
            $idx = $_POST['index_costos'];
            $cod = $_POST['codigo_centro'];
            $des = $_POST['descripcion_centro'];
            $est = $_POST['estado'];

            $datos = compact("idx","cod","des","est");

            $salida = $this->model->update($datos);

            echo $salida;
        }

        function delReg(){
            $idx = $_POST['index_costos'];

            $salida = $this->model->delete($idx);

            echo $salida;
        }

        function listAll(){
            $salida = $this->model->getallItem();

            echo $salida;
        }

        function listById(){
            $idx = $_POST['idx'];

            $salida = $this->model->getItemById($idx);

            echo json_encode($salida);
        }
    }
?>