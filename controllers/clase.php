<?php
    class Clase extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->groupList = $this->model->getAllGroups();
            $this->view->totalClase = $this->model->gettotalClases();
            $this->view->render('clase/index');
        }

        function getLastClassCode() {
            $cod = $_POST['data'];

            $datos = compact("cod");
            
            $codigo = $this->model->genClassCode($datos);

            echo (int)$codigo + 1;

            return $codigo;
        }

        function existItem() {
            $item = utf8_decode(strtoupper($_POST['descripcion']));

            $datos = compact("item");

            $exist = $this->model->checkExist($datos);

            if ($exist == '0') {
                echo $exist;
            }else{
                echo $exist;
            }
        }

        function newClass(){
            $gcod = $_POST['codigo_grupo'];
            $ccod = $_POST['codigo_clase'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            $niv = '2';

            $datos = compact("gcod","ccod","des","niv");

            $savereg = $this->model->insert($datos);

            if ($savereg) {
                echo "Registro grabado";
            }else {
                echo "Error... registro no grabado";
            }
        }

        function listClasses(){
            $listData = $this->model->listAllClasses();
            
            echo $listData;
        }

        function modifyClass() {
            $ind = $_POST['indice'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            
            $datos = compact("ind","des");

            $saveReg = $this->model->updateClass($datos);

            if ($saveReg) {
                echo "Registro actualizado";
            }else {
                echo "Error... registro no actualizado";
            }
        }
    }
?>